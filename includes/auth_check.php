<?php
// ensure a user is logged in; used for frontend pages
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    // relative to caller, so this file is included from root pages
    header("Location: login.php");
    exit();
}
// we do not enforce role here, regular users are allowed
?>