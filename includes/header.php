<?php
// generic header and navigation
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'Toko Kue Manis'; ?></title>
    <link rel="stylesheet" href="/aplikasi_pemesanan_kue/css/style.css">
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
            <a href="/aplikasi_pemesanan_kue/index.php" class="text-2xl font-bold text-purple-600 hover:text-purple-800 transition duration-300">
                <i class="fas fa-birthday-cake text-pink-500"></i> Toko Kue Manis
            </a>
            <nav class="flex space-x-6">
                <?php $current_page = basename($_SERVER['PHP_SELF']); ?>
                <a href="/aplikasi_pemesanan_kue/index.php" class="text-gray-700 hover:text-purple-600 transition duration-300 flex items-center <?php echo ($current_page == 'index.php') ? 'bg-purple-100 text-purple-800 font-semibold' : ''; ?>">
                    <i class="fas fa-home mr-2"></i> Beranda
                </a>
                <a href="/aplikasi_pemesanan_kue/keranjang.php" class="text-gray-700 hover:text-purple-600 transition duration-300 flex items-center <?php echo ($current_page == 'keranjang.php') ? 'bg-purple-100 text-purple-800 font-semibold' : ''; ?>">
                    <i class="fas fa-shopping-cart mr-2"></i> Keranjang
                </a>
                <?php if (isset($_SESSION['username']) && $_SESSION['role'] != 'admin'): ?>
                <a href="/aplikasi_pemesanan_kue/pesanan_user.php" class="text-gray-700 hover:text-purple-600 transition duration-300 flex items-center <?php echo ($current_page == 'pesanan_user.php') ? 'bg-purple-100 text-purple-800 font-semibold' : ''; ?>">
                    <i class="fas fa-shopping-bag mr-2"></i> Pesanan
                </a>
                <?php endif; ?>
                <?php
                if (isset($_SESSION['username'])) {
                    echo "<span class='text-gray-700 flex items-center'><i class='fas fa-user mr-2'></i> " . htmlspecialchars($_SESSION['username']) . "</span>";
                    echo "<a href='/aplikasi_pemesanan_kue/logout.php' class='text-gray-700 hover:text-purple-600 transition duration-300 flex items-center'><i class='fas fa-sign-out-alt mr-2'></i> Logout</a>";
                    if ($_SESSION['role'] == 'admin') {
                        echo "<a href='/aplikasi_pemesanan_kue/admin/index.php' class='text-gray-700 hover:text-purple-600 transition duration-300 flex items-center " . (($current_page == 'index.php' && strpos($_SERVER['REQUEST_URI'], 'admin') !== false) ? 'bg-purple-100 text-purple-800 font-semibold' : '') . "'><i class='fas fa-cog mr-2'></i> Admin</a>";
                    }
                } else {
                    echo "<a href='/aplikasi_pemesanan_kue/login.php' class='text-gray-700 hover:text-purple-600 transition duration-300 flex items-center " . ($current_page == 'login.php' ? 'bg-purple-100 text-purple-800 font-semibold' : '') . "'><i class='fas fa-sign-in-alt mr-2'></i> Login</a>";
                    echo "<a href='/aplikasi_pemesanan_kue/register.php' class='text-gray-700 hover:text-purple-600 transition duration-300 flex items-center " . ($current_page == 'register.php' ? 'bg-purple-100 text-purple-800 font-semibold' : '') . "'><i class='fas fa-user-plus mr-2'></i> Register</a>";
                }
                ?>
            </nav>
        </div>
    </div>
</header>
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 flex-grow">
