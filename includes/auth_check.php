<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
// Opsional: Cek role jika diperlukan
// if ($_SESSION['role'] !== 'admin') { header("Location: index.php"); exit(); }
?>