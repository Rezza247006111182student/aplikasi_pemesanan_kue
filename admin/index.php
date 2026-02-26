<?php
include '../includes/db.php';
$pageTitle = 'Dashboard Admin';
include '../includes/admin_header.php';
?>

        <h1 class="text-4xl font-bold text-center text-gray-800 mb-4"><i class="fas fa-tachometer-alt text-purple-500 mr-3"></i> Dashboard Admin</h1>
        <p class="text-lg text-center text-gray-600 mb-12">Kelola toko kue Anda dengan mudah dari sini.</p>
        
        <?php
        // Hitung statistik
        $total_kue = $conn->query("SELECT COUNT(*) as count FROM kue")->fetch_assoc()['count'];
        $pesanan_hari_ini = $conn->query("SELECT COUNT(*) as count FROM pesanan WHERE DATE(tanggal) = CURDATE()")->fetch_assoc()['count'];
        $total_pengguna = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'user'")->fetch_assoc()['count'];
        $pendapatan_hari_ini = $conn->query("SELECT SUM(total) as total FROM pesanan WHERE DATE(tanggal) = CURDATE() AND status = 'confirmed'")->fetch_assoc()['total'] ?? 0;
        $pesanan_pending = $conn->query("SELECT COUNT(*) as count FROM pesanan WHERE status = 'pending'")->fetch_assoc()['count'];
        ?>
        
        <!-- Statistik Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
            <div class="bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-2xl transition duration-300">
                <i class="fas fa-birthday-cake text-4xl text-pink-500 mb-4"></i>
                <h3 class="text-2xl font-bold text-gray-800"><?php echo $total_kue; ?></h3>
                <p class="text-gray-600">Total Kue</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-2xl transition duration-300">
                <i class="fas fa-shopping-bag text-4xl text-purple-500 mb-4"></i>
                <h3 class="text-2xl font-bold text-gray-800"><?php echo $pesanan_hari_ini; ?></h3>
                <p class="text-gray-600">Pesanan Hari Ini</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-2xl transition duration-300">
                <i class="fas fa-users text-4xl text-blue-500 mb-4"></i>
                <h3 class="text-2xl font-bold text-gray-800"><?php echo $total_pengguna; ?></h3>
                <p class="text-gray-600">Total Pengguna</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-2xl transition duration-300">
                <i class="fas fa-dollar-sign text-4xl text-green-500 mb-4"></i>
                <h3 class="text-2xl font-bold text-gray-800">Rp <?php echo number_format($pendapatan_hari_ini, 0, ',', '.'); ?></h3>
                <p class="text-gray-600">Pendapatan Hari Ini</p>
            </div>
        </div>
        
        <!-- Pending Orders Alert -->
        <?php if ($pesanan_pending > 0): ?>
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-8 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle mr-3"></i>
                <div>
                    <p class="font-bold">Ada <?php echo $pesanan_pending; ?> pesanan yang menunggu konfirmasi</p>
                    <p class="text-sm">Silakan periksa dan konfirmasi pesanan untuk memprosesnya.</p>
                </div>
                <a href="kelola_pesanan.php" class="ml-auto bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition duration-300">
                    Lihat Pesanan
                </a>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-birthday-cake text-pink-500 mr-2"></i> Kelola Kue
                </h3>
                <p class="text-gray-600 mb-4">Tambah, edit, atau hapus kue dari katalog.</p>
                <a href="kelola_kue.php" class="bg-gradient-to-r from-purple-500 to-pink-500 text-white py-2 px-4 rounded-lg hover:from-purple-600 hover:to-pink-600 transition duration-300 font-medium inline-flex items-center">
                    <i class="fas fa-edit mr-2"></i> Kelola Kue
                </a>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-shopping-bag text-purple-500 mr-2"></i> Kelola Pesanan
                </h3>
                <p class="text-gray-600 mb-4">Lihat dan update status pesanan pelanggan.</p>
                <a href="kelola_pesanan.php" class="bg-gradient-to-r from-purple-500 to-pink-500 text-white py-2 px-4 rounded-lg hover:from-purple-600 hover:to-pink-600 transition duration-300 font-medium inline-flex items-center">
                    <i class="fas fa-list mr-2"></i> Kelola Pesanan
                </a>
            </div>
        </div>
    </main>

<?php include '../includes/admin_footer.php'; ?>