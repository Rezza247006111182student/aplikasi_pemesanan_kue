<?php
class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        $host = 'localhost';
        $user = 'root';
        $pass = '';
        $dbname = 'aplikasi_pemesanan_kue';

        $this->conn = new mysqli($host, $user, $pass, $dbname);
        if ($this->conn->connect_error) {
            die("Koneksi gagal: " . $this->conn->connect_error);
        }
        // Set charset
        $this->conn->set_charset('utf8mb4');
    }

    public static function getConnection() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->conn;
    }

    private function __clone() {}
    public function __wakeup() {}
}
