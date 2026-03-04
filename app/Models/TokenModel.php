<?php
require_once __DIR__ . '/Model.php';

class TokenModel extends Model {
    private $table = 'api_tokens';

    public function __construct() {
        parent::__construct();
        // create tokens table if not exists
        $sql = "CREATE TABLE IF NOT EXISTS `{$this->table}` (
            `id` int NOT NULL AUTO_INCREMENT,
            `user_id` int NOT NULL,
            `token` varchar(128) NOT NULL,
            `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `token_idx` (`token`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        $this->db->query($sql);
    }

    public function createToken($userId) {
        $token = bin2hex(random_bytes(32));
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (user_id, token) VALUES (?, ?)");
        $stmt->bind_param('is', $userId, $token);
        $stmt->execute();
        $stmt->close();
        return $token;
    }

    public function getUserByToken($token) {
        $stmt = $this->db->prepare("SELECT u.* FROM {$this->table} t JOIN users u ON u.id = t.user_id WHERE t.token = ? LIMIT 1");
        $stmt->bind_param('s', $token);
        $stmt->execute();
        $res = $stmt->get_result();
        $user = $res->fetch_assoc();
        $stmt->close();
        return $user;
    }

    public function revokeToken($token) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE token = ?");
        $stmt->bind_param('s', $token);
        $stmt->execute();
        $stmt->close();
        return true;
    }
}
