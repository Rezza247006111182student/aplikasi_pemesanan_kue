<?php
require_once __DIR__ . '/../app/Middleware/AuthAdmin.php';
// Delegate admin auth check to middleware
AuthAdmin::handle();
?>