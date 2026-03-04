<?php
// Simple server-side rendered home view for MVC demo
$pageTitle = 'Aplikasi Pemesanan Kue - MVC';
include __DIR__ . '/../includes/header.php';
?>
    <h1 class="text-4xl font-bold text-center text-gray-800 mb-4">Selamat Datang di Toko Kue Manis</h1>
    <p class="text-lg text-center text-gray-600 mb-12">Nikmati berbagai macam kue lezat untuk setiap acara spesial Anda!</p>

    <h2 class="text-3xl font-semibold text-gray-800 mb-8 flex items-center justify-center">
        <i class="fas fa-birthday-cake text-pink-500 mr-3"></i> Daftar Kue
    </h2>

    <div class="mb-8 flex justify-center">
        <div class="relative w-full max-w-md">
            <input type="text" id="search-kue" placeholder="Cari kue..." class="w-full px-4 py-2 pl-10 pr-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
        </div>
    </div>

    <div id="kue-container" class="flex flex-wrap justify-center gap-8">
<?php if (!empty($kueList)): ?>
    <?php foreach ($kueList as $kue): ?>
        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition duration-300 transform hover:-translate-y-1 w-60 flex flex-col">
            <img src="images/<?php echo htmlspecialchars($kue['gambar']); ?>" alt="<?php echo htmlspecialchars($kue['nama']); ?>" class="w-full aspect-square object-cover">
            <div class="p-6 flex-1 flex flex-col">
                <h3 class="text-xl font-semibold text-gray-800 mb-2"><?php echo htmlspecialchars($kue['nama']); ?></h3>
                <p class="text-gray-600 mb-2 line-clamp-3"><?php echo htmlspecialchars($kue['deskripsi']); ?></p>
                <button onclick="showDescriptionModal('<?php echo addslashes($kue['nama']); ?>', '<?php echo addslashes($kue['deskripsi']); ?>')" class="text-blue-600 hover:text-blue-800 text-sm font-medium mb-4 text-align-left">Lihat Selengkapnya →</button>
                <div class="flex justify-between items-center mt-auto">
                    <span class="text-2xl font-bold text-purple-600">Rp <?php echo number_format($kue['harga'],0,',','.'); ?></span>
                    <span class="text-sm text-gray-500">Stok: <?php echo (int)$kue['stok']; ?></span>
                </div>
            </div>
            <div class="flex items-center justify-between p-4 bg-gray-50">
                <div class="flex items-center">
                    <label class="text-sm mr-2">Qty:</label>
                    <input type="number" id="qty-<?php echo (int)$kue['id']; ?>" class="w-16 px-2 py-1 border rounded" value="1" min="1" max="<?php echo (int)$kue['stok']; ?>">
                </div>
                <button onclick="addToCart(<?php echo (int)$kue['id']; ?>)" class="w-12 h-12 bg-gradient-to-r from-green-500 to-blue-500 text-white rounded-full hover:from-green-600 hover:to-blue-600 transition duration-300 flex items-center justify-center"><i class="fas fa-cart-plus"></i></button>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class='col-span-full text-center py-12'>
        <i class='fas fa-exclamation-triangle text-6xl text-gray-400 mb-4'></i>
        <p class='text-xl text-gray-600'>Tidak ada kue ditemukan.</p>
    </div>
<?php endif; ?>
    </div>

    <div id="description-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4 shadow-lg">
            <div class="flex justify-between items-start">
                <h3 id="modal-title" class="text-xl font-bold text-gray-800"></h3>
                <button onclick="closeDescriptionModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            </div>
            <p id="modal-description" class="text-gray-600 mt-4 whitespace-pre-wrap"></p>
            <button onclick="closeDescriptionModal()" class="mt-6 w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-300">
                Tutup
            </button>
        </div>
    </div>

    <script>
        var isLoggedIn = <?php echo isset($_SESSION['username']) ? 'true' : 'false'; ?>;

        function addToCart(kueId) {
            if (!isLoggedIn) {
                if (confirm('Anda perlu login terlebih dahulu. Login sekarang?')) {
                    window.location.href = 'login.php';
                }
                return;
            }

            var qtyInput = document.getElementById('qty-' + kueId);
            var quantity = qtyInput ? parseInt(qtyInput.value, 10) : 1;
            if (!quantity || quantity < 1) {
                alert('Quantity tidak valid');
                return;
            }

            $.ajax({
                url: 'add_to_cart.php',
                type: 'POST',
                data: { kue_id: kueId, quantity: quantity },
                dataType: 'json',
                success: function(response) {
                    if (response && response.success) {
                        alert(response.message || 'Berhasil ditambahkan ke keranjang');
                    } else {
                        alert((response && response.message) ? response.message : 'Gagal menambahkan ke keranjang');
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan saat menambah ke keranjang');
                }
            });
        }

        function showDescriptionModal(nama, deskripsi) {
            $('#modal-title').text(nama);
            $('#modal-description').text(deskripsi);
            $('#description-modal').removeClass('hidden');
        }

        function closeDescriptionModal() {
            $('#description-modal').addClass('hidden');
        }
    </script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
