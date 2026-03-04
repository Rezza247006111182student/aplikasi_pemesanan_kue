<?php
$pageTitle = 'Keranjang Belanja';
include __DIR__ . '/../../includes/header.php';
?>
<h1 class="text-4xl font-bold text-center text-gray-800 mb-4"><i class="fas fa-shopping-cart text-purple-500 mr-3"></i> Keranjang Belanja</h1>
<p class="text-lg text-center text-gray-600 mb-12">Lihat dan kelola item yang Anda pilih sebelum checkout.</p>

<?php if (!empty($cart)): ?>
    <div class="space-y-6">
        <?php foreach ($cart as $kue_id => $item): ?>
            <?php $subtotal = ((float)$item['harga'] * (int)$item['quantity']); ?>
            <div class="bg-white rounded-xl shadow-lg p-6 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <img src="images/<?php echo htmlspecialchars($item['gambar']); ?>" alt="<?php echo htmlspecialchars($item['nama']); ?>" class="w-20 h-20 object-cover rounded-lg">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800"><?php echo htmlspecialchars($item['nama']); ?></h3>
                        <p class="text-gray-600">Rp <?php echo number_format($item['harga'], 0, ',', '.'); ?></p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <button onclick="updateQuantity(<?php echo (int)$kue_id; ?>, <?php echo ((int)$item['quantity'] - 1); ?>)" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-1 rounded">-</button>
                    <span class="text-lg font-semibold" id="qty-<?php echo (int)$kue_id; ?>"><?php echo (int)$item['quantity']; ?></span>
                    <button onclick="updateQuantity(<?php echo (int)$kue_id; ?>, <?php echo ((int)$item['quantity'] + 1); ?>)" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-1 rounded">+</button>
                </div>
                <div class="text-right">
                    <p class="text-xl font-bold text-purple-600">Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></p>
                    <button onclick="removeItem(<?php echo (int)$kue_id; ?>)" class="text-red-500 hover:text-red-700 mt-2"><i class="fas fa-trash"></i> Hapus</button>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="bg-white rounded-xl shadow-lg p-6 text-right">
            <p class="text-2xl font-bold text-gray-800">Total: Rp <?php echo number_format($total, 0, ',', '.'); ?></p>
            <a href="buat_pesanan.php" class="bg-gradient-to-r from-green-500 to-blue-500 text-white py-3 px-6 rounded-lg hover:from-green-600 hover:to-blue-600 transition duration-300 font-medium inline-flex items-center mt-4">
                <i class="fas fa-paper-plane mr-2"></i> Buat Pesanan
            </a>
        </div>
    </div>
<?php else: ?>
    <div class="bg-white rounded-xl shadow-lg p-12 text-center">
        <i class="fas fa-shopping-cart text-6xl text-gray-400 mb-6"></i>
        <h3 class="text-2xl font-semibold text-gray-800 mb-4">Keranjang Anda Kosong</h3>
        <p class="text-gray-600 mb-8">Belum ada item di keranjang. Mulai berbelanja sekarang!</p>
        <a href="index.php" class="bg-gradient-to-r from-purple-500 to-pink-500 text-white py-3 px-6 rounded-lg hover:from-purple-600 hover:to-pink-600 transition duration-300 font-medium inline-flex items-center">
            <i class="fas fa-birthday-cake mr-2"></i> Mulai Belanja
        </a>
    </div>
<?php endif; ?>

<script>
function updateQuantity(kueId, newQty) {
    if (newQty < 1) return;
    $.ajax({
        url: 'update_cart.php',
        type: 'POST',
        data: { kue_id: kueId, quantity: newQty },
        dataType: 'json',
        success: function() { location.reload(); },
        error: function() { alert('Terjadi kesalahan'); }
    });
}

function removeItem(kueId) {
    if (!confirm('Yakin hapus item ini?')) return;
    $.ajax({
        url: 'remove_cart.php',
        type: 'POST',
        data: { kue_id: kueId },
        dataType: 'json',
        success: function() { location.reload(); },
        error: function() { alert('Terjadi kesalahan'); }
    });
}
</script>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
