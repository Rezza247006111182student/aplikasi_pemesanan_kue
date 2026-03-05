<?php
require_once __DIR__ . '/BaseApiController.php';
require_once __DIR__ . '/../../Models/TokenModel.php';
require_once __DIR__ . '/../../Middleware/ApiAuth.php';

class AuthApiController extends BaseApiController {
    public function register() {
        $input = json_decode(file_get_contents('php://input'), true);
        if (empty($input['username']) || empty($input['email']) || empty($input['password'])) {
            return $this->error('Username, email, dan password diperlukan', 400);
        }

        $username = trim($input['username']);
        $email = trim($input['email']);
        $password = (string)$input['password'];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->error('Format email tidak valid', 400);
        }
        if (strlen($password) < 6) {
            return $this->error('Password minimal 6 karakter', 400);
        }

        $db = \Database::getConnection();

        $checkStmt = $db->prepare("SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1");
        $checkStmt->bind_param('ss', $username, $email);
        $checkStmt->execute();
        $exists = $checkStmt->get_result()->fetch_assoc();
        $checkStmt->close();

        if ($exists) {
            return $this->error('Username atau email sudah terdaftar', 409);
        }

        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $insertStmt = $db->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')");
        $insertStmt->bind_param('sss', $username, $email, $hashed);
        $insertStmt->execute();
        $userId = (int)$insertStmt->insert_id;
        $insertStmt->close();

        $tokenModel = new TokenModel();
        $token = $tokenModel->createToken($userId);

        $this->json([
            'message' => 'Registrasi berhasil',
            'token' => $token,
            'user' => [
                'id' => $userId,
                'username' => $username,
                'email' => $email,
                'role' => 'user'
            ]
        ], 201);
    }

    public function login() {
        $input = json_decode(file_get_contents('php://input'), true);
        if (empty($input['email']) || empty($input['password'])) {
            return $this->error('Email dan password diperlukan', 400);
        }

        $email = $input['email'];
        $password = $input['password'];

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

        if (!password_verify($password, $user['password'])) {
            return $this->error('Email atau password salah', 401);
        }

        $tokenModel = new TokenModel();
        $token = $tokenModel->createToken((int)$user['id']);

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
