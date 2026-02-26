<?php
$pageTitle = 'Pesanan Berhasil';
include 'includes/header.php';
?>

<?php include 'includes/auth_check.php'; ?>
        <div class="bg-white rounded-xl shadow-lg p-12 text-center">
            <i class="fas fa-check-circle text-6xl text-green-500 mb-6"></i>
            <h1 class="text-3xl font-bold text-gray-800 mb-4">Pesanan Berhasil Dibuat!</h1>
            <p class="text-lg text-gray-600 mb-8">Pesanan Anda telah dikirim dan menunggu konfirmasi dari admin. ID Pesanan: <strong><?php echo $_GET['id']; ?></strong></p>
            <a href="index.php" class="bg-gradient-to-r from-purple-500 to-pink-500 text-white py-3 px-6 rounded-lg hover:from-purple-600 hover:to-pink-600 transition duration-300 font-medium inline-flex items-center">
                <i class="fas fa-home mr-2"></i> Kembali ke Beranda
            </a>
        </div>
    </main>

<?php include 'includes/footer.php'; ?>