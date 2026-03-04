<?php
$pageTitle = 'Pesanan Saya';
include __DIR__ . '/../../includes/header.php';
?>
<div class="flex justify-between items-center mb-8">
    <h1 class="text-4xl font-bold text-gray-800 flex items-center">
        <i class="fas fa-shopping-bag text-purple-500 mr-3"></i> Pesanan Saya
    </h1>
</div>

<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <table class="w-full table-auto">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Pesanan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php if (!empty($orders)): ?>
                <?php foreach ($orders as $row): ?>
                    <?php
                    $status_class = $row['status'] == 'pending' ? 'bg-yellow-100 text-yellow-800' : ($row['status'] == 'confirmed' ? 'bg-green-100 text-green-800' : ($row['status'] == 'canceled' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800'));
                    $status_text = $row['status'] == 'pending' ? 'Menunggu Konfirmasi' : ($row['status'] == 'confirmed' ? 'Dikonfirmasi' : ($row['status'] == 'canceled' ? 'Dibatalkan' : 'Selesai'));
                    ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">#<?php echo str_pad($row['id'], 4, '0', STR_PAD_LEFT); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-900"><?php echo date('d/m/Y H:i', strtotime($row['tanggal'])); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-900">Rp <?php echo number_format($row['total'], 0, ',', '.'); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $status_class; ?>"><?php echo $status_text; ?></span></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="detail_pesanan_user.php?id=<?php echo (int)$row['id']; ?>" class="text-blue-600 hover:text-blue-900 mr-3">Lihat Detail</a>
                            <?php if ($row['status'] === 'pending'): ?>
                                <a href="batalkan_pesanan_user.php?id=<?php echo (int)$row['id']; ?>" class="text-red-600 hover:text-red-900 mr-3" onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">Batalkan</a>
                            <?php elseif ($row['status'] === 'canceled'): ?>
                                <a href="hapus_pesanan_user.php?id=<?php echo (int)$row['id']; ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Apakah Anda yakin ingin menghapus pesanan ini secara permanen?')">Hapus</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada pesanan.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if (empty($orders)): ?>
<div class="text-center mt-8">
    <div class="bg-gray-100 rounded-lg p-8">
        <i class="fas fa-shopping-bag text-6xl text-gray-400 mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-600 mb-2">Belum Ada Pesanan</h3>
        <p class="text-gray-500 mb-4">Anda belum membuat pesanan apapun.</p>
        <a href="index.php" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
            <i class="fas fa-birthday-cake mr-2"></i> Mulai Belanja
        </a>
    </div>
</div>
<?php endif; ?>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
