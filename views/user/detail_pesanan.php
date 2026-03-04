<?php
$pageTitle = 'Detail Pesanan';
include __DIR__ . '/../../includes/header.php';
?>
<div class="flex justify-between items-center mb-8">
    <h1 class="text-4xl font-bold text-gray-800 flex items-center">
        <i class="fas fa-list text-purple-500 mr-3"></i> Detail Pesanan
    </h1>
</div>

<?php if (!empty($error)): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
        <i class="fas fa-exclamation-triangle mr-2"></i> <?php echo htmlspecialchars($error); ?>
    </div>
<?php else: ?>
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Informasi Pesanan</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><strong>ID Pesanan:</strong> #<?php echo str_pad($order['id'], 4, '0', STR_PAD_LEFT); ?></div>
            <div><strong>Tanggal:</strong> <?php echo date('d/m/Y H:i', strtotime($order['tanggal'])); ?></div>
            <div><strong>Total:</strong> Rp <?php echo number_format($order['total'], 0, ',', '.'); ?></div>
            <div>
                <strong>Status:</strong>
                <?php
                $status_class = $order['status'] == 'pending' ? 'bg-yellow-100 text-yellow-800' : ($order['status'] == 'confirmed' ? 'bg-green-100 text-green-800' : ($order['status'] == 'canceled' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800'));
                $status_text = $order['status'] == 'pending' ? 'Menunggu Konfirmasi' : ($order['status'] == 'confirmed' ? 'Dikonfirmasi' : ($order['status'] == 'canceled' ? 'Dibatalkan' : 'Selesai'));
                ?>
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $status_class; ?>"><?php echo $status_text; ?></span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <h2 class="text-2xl font-bold text-gray-800 p-6 pb-0">Item Pesanan</h2>
        <table class="w-full table-auto">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kue</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($details as $detail): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900"><?php echo htmlspecialchars($detail['nama']); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-900">Rp <?php echo number_format($detail['harga'], 0, ',', '.'); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-900"><?php echo (int)$detail['quantity']; ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-900">Rp <?php echo number_format($detail['harga'] * $detail['quantity'], 0, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<div class="text-center mt-6">
    <a href="pesanan_user.php" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Pesanan Saya
    </a>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
