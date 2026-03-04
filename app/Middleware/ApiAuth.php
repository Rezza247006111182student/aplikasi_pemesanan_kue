<?php
require_once __DIR__ . '/../Models/TokenModel.php';

class ApiAuth {
    public static function getBearerToken() {
        $authHeader = '';

        if (!empty($_SERVER['HTTP_AUTHORIZATION'])) {
            $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
        } elseif (!empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
            $authHeader = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        } elseif (function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
            if (is_array($headers)) {
                foreach ($headers as $key => $value) {
                    if (strtolower($key) === 'authorization') {
                        $authHeader = $value;
                        break;
                    }
                }
            }
        }

        if (stripos($authHeader, 'Bearer ') !== 0) {
            return null;
        }

        $token = trim(substr($authHeader, 7));
        return $token !== '' ? $token : null;
    }

    public static function user() {
        $token = self::getBearerToken();
        if (!$token) return null;
        $tm = new TokenModel();
        $user = $tm->getUserByToken($token);
        return $user ?: null;
    }

    public static function requireAuth() {
        $user = self::user();
        if (!$user) {
            http_response_code(401);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['error' => 'Unauthorized'], JSON_UNESCAPED_UNICODE);
            exit();
        }
        return $user;
    }
}
