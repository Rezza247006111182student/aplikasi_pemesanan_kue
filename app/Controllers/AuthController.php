<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/UserModel.php';

class AuthController extends BaseController {
    public function login() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = isset($_POST['username']) ? trim($_POST['username']) : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';

            if ($username === '' || $password === '') {
                $error = 'Username dan password wajib diisi!';
            } else {
                $userModel = new UserModel();
                $user = $userModel->findByUsername($username);

                if (!$user || !password_verify($password, $user['password'])) {
                    $error = 'Username atau password salah!';
                } else {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];

                    if ($user['role'] === 'admin') {
                        header('Location: admin/index.php');
                    } else {
                        header('Location: index.php');
                    }
                    exit();
                }
            }
        }

        $this->render('auth/login', ['error' => $error]);
    }

    public function register() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = isset($_POST['username']) ? trim($_POST['username']) : '';
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $passwordRaw = isset($_POST['password']) ? $_POST['password'] : '';

            if ($username === '' || $email === '' || $passwordRaw === '') {
                $error = 'Username, email, dan password wajib diisi!';
            } else {
                $userModel = new UserModel();
                $exists = $userModel->findByUsernameOrEmail($username, $email);

                if ($exists) {
                    $error = 'Username atau email sudah terdaftar!';
                } else {
                    $password = password_hash($passwordRaw, PASSWORD_DEFAULT);
                    $userModel->create($username, $email, $password);
                    $success = 'Pendaftaran berhasil! Silakan login.';
                }
            }
        }

        $this->render('auth/register', ['error' => $error, 'success' => $success]);
    }
}
