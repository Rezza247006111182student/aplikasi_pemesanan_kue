<?php
require_once __DIR__ . '/../Models/TokenModel.php';

class ApiAuth {
    public static function user() {
        $authHeader = isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : '';
        if (strpos($authHeader, 'Bearer ') !== 0) return null;
        $token = substr($authHeader, 7);
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
