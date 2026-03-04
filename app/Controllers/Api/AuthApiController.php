<?php
require_once __DIR__ . '/BaseApiController.php';
require_once __DIR__ . '/../../Models/TokenModel.php';
require_once __DIR__ . '/../../Middleware/ApiAuth.php';

class AuthApiController extends BaseApiController {
    public function login() {
        $input = json_decode(file_get_contents('php://input'), true);
        if (empty($input['email']) || empty($input['password'])) {
            return $this->error('Email dan password diperlukan', 400);
        }

        $email = $input['email'];
        $password = $input['password'];

        // find user
        $db = \Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $res = $stmt->get_result();
        $user = $res->fetch_assoc();
        $stmt->close();

        if (!$user) {
            return $this->error('Email atau password salah', 401);
        }

        // assuming passwords stored hashed using password_hash
        if (!password_verify($password, $user['password'])) {
            return $this->error('Email atau password salah', 401);
        }

        // create token
        $tokenModel = new TokenModel();
        $token = $tokenModel->createToken((int)$user['id']);

        // return token + basic user info
        unset($user['password']);
        $this->json(['token' => $token, 'user' => $user]);
    }

    public function logout() {
        $token = ApiAuth::getBearerToken();
        if ($token) {
            $tm = new TokenModel();
            $tm->revokeToken($token);
            return $this->json(['message' => 'Logged out']);
        }
        return $this->error('Token tidak ditemukan', 400);
    }
}
