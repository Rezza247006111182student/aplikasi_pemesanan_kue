<?php
include 'includes/auth_check.php';
$pageTitle = 'Keranjang Belanja';
include 'includes/header.php';
?>        <h1 class="text-4xl font-bold text-center text-gray-800 mb-4"><i class="fas fa-shopping-cart text-purple-500 mr-3"></i> Keranjang Belanja</h1>
        <p class="text-lg text-center text-gray-600 mb-12">Lihat dan kelola item yang Anda pilih sebelum checkout.</p>
        
        <?php
        $total = 0;
        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            echo '<div class="space-y-6">';
            foreach ($_SESSION['cart'] as $kue_id => $item) {
                $subtotal = $item['harga'] * $item['quantity'];
                $total += $subtotal;
                echo '
                <div class="bg-white rounded-xl shadow-lg p-6 flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <img src="images/' . $item['gambar'] . '" alt="' . $item['nama'] . '" class="w-20 h-20 object-cover rounded-lg">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800">' . $item['nama'] . '</h3>
                            <p class="text-gray-600">Rp ' . number_format($item['harga'], 0, ',', '.') . '</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button onclick="updateQuantity(' . $kue_id . ', ' . ($item['quantity'] - 1) . ')" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-1 rounded">-</button>
                        <span class="text-lg font-semibold" id="qty-' . $kue_id . '">' . $item['quantity'] . '</span>
                        <button onclick="updateQuantity(' . $kue_id . ', ' . ($item['quantity'] + 1) . ')" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-1 rounded">+</button>
                    </div>
                    <div class="text-right">
                        <p class="text-xl font-bold text-purple-600">Rp ' . number_format($subtotal, 0, ',', '.') . '</p>
                        <button onclick="removeItem(' . $kue_id . ')" class="text-red-500 hover:text-red-700 mt-2"><i class="fas fa-trash"></i> Hapus</button>
                    </div>
                </div>';
            }
            echo '
            <div class="bg-white rounded-xl shadow-lg p-6 text-right">
                <p class="text-2xl font-bold text-gray-800">Total: Rp ' . number_format($total, 0, ',', '.') . '</p>
                <a href="buat_pesanan.php" class="bg-gradient-to-r from-green-500 to-blue-500 text-white py-3 px-6 rounded-lg hover:from-green-600 hover:to-blue-600 transition duration-300 font-medium inline-flex items-center mt-4">
                    <i class="fas fa-paper-plane mr-2"></i> Buat Pesanan
                </a>
            </div>
            </div>';
        } else {
            echo '
            <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                <i class="fas fa-shopping-cart text-6xl text-gray-400 mb-6"></i>
                <h3 class="text-2xl font-semibold text-gray-800 mb-4">Keranjang Anda Kosong</h3>
                <p class="text-gray-600 mb-8">Belum ada item di keranjang. Mulai berbelanja sekarang!</p>
                <a href="index.php" class="bg-gradient-to-r from-purple-500 to-pink-500 text-white py-3 px-6 rounded-lg hover:from-purple-600 hover:to-pink-600 transition duration-300 font-medium inline-flex items-center">
                    <i class="fas fa-birthday-cake mr-2"></i> Mulai Belanja
                </a>
            </div>';
        }
        ?>
    </main>

    <!-- Modal Success Popup -->
    <div id="success-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 overflow-hidden">
            <div class="bg-green-600 text-white p-6">
                <h3 class="text-xl font-bold flex items-center">
                    <i class="fas fa-check-circle mr-2"></i> Berhasil
                </h3>
            </div>
            <div class="p-6">
                <p id="success-message" class="text-gray-600 mb-6"></p>
                <div class="flex justify-end">
                    <button onclick="closeSuccessPopup()" class="bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition duration-300">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Error Popup -->
    <div id="error-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 overflow-hidden">
            <div class="bg-red-600 text-white p-6">
                <h3 class="text-xl font-bold flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i> Error
                </h3>
            </div>
            <div class="p-6">
                <p id="error-message" class="text-gray-600 mb-6"></p>
                <div class="flex justify-end">
                    <button onclick="closeErrorPopup()" class="bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition duration-300">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateQuantity(kueId, newQty) {
            if (newQty < 1) return;
            $.ajax({
                url: 'update_cart.php',
                type: 'POST',
                data: { kue_id: kueId, quantity: newQty },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showSuccessPopup(response.message);
                    } else {
                        showErrorPopup(response.message);
                    }
                },
                error: function() {
                    showErrorPopup('Terjadi kesalahan');
                }
            });
        }

        function removeItem(kueId) {
            if (confirm('Yakin hapus item ini?')) {
                $.ajax({
                    url: 'remove_cart.php',
                    type: 'POST',
                    data: { kue_id: kueId },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            showSuccessPopup(response.message);
                        } else {
                            showErrorPopup(response.message);
                        }
                    },
                    error: function() {
                        showErrorPopup('Terjadi kesalahan');
                    }
                });
            }
        }

        function showSuccessPopup(message) {
            $('#success-message').text(message);
            $('#success-modal').removeClass('hidden');
        }

        function closeSuccessPopup() {
            $('#success-modal').addClass('hidden');
            location.reload();
        }

        function showErrorPopup(message) {
            $('#error-message').text(message);
            $('#error-modal').removeClass('hidden');
        }

        function closeErrorPopup() {
            $('#error-modal').addClass('hidden');
        }
    </script>
<?php include 'includes/footer.php'; ?>