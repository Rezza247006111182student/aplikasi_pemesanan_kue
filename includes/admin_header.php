<?php
// Admin header and navigation
include '../includes/auth_check_admin.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'Admin Panel - Toko Kue Manis'; ?></title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-pink-50 to-purple-100 min-h-screen flex flex-col">
<header class="bg-white shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">
            <a href="index.php" class="text-2xl font-bold text-purple-600 hover:text-purple-800 transition duration-300">
                <i class="fas fa-cog text-pink-500"></i> Admin Panel
            </a>
            <nav class="flex space-x-6">
                <?php $current_page = basename($_SERVER['PHP_SELF']); ?>
                <a href="index.php" class="text-gray-700 hover:text-purple-600 transition duration-300 flex items-center <?php echo ($current_page == 'index.php') ? 'bg-purple-100 text-purple-800 font-semibold' : ''; ?>">
                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                </a>
                <a href="kelola_kue.php" class="text-gray-700 hover:text-purple-600 transition duration-300 flex items-center <?php echo ($current_page == 'kelola_kue.php') ? 'bg-purple-100 text-purple-800 font-semibold' : ''; ?>">
                    <i class="fas fa-birthday-cake mr-2"></i> Kelola Kue
                </a>
                <a href="kelola_pesanan.php" class="text-gray-700 hover:text-purple-600 transition duration-300 flex items-center <?php echo ($current_page == 'kelola_pesanan.php') ? 'bg-purple-100 text-purple-800 font-semibold' : ''; ?>">
                    <i class="fas fa-shopping-bag mr-2"></i> Kelola Pesanan
                </a>
                <a href="../logout.php" class="text-gray-700 hover:text-purple-600 transition duration-300 flex items-center"><i class="fas fa-sign-out-alt mr-2"></i> Logout</a>
            </nav>
        </div>
    </div>
</header>
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 flex-grow">
