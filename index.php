<?php
$pageTitle = 'Aplikasi Pemesanan Kue';
include 'includes/header.php';
?>        <h1 class="text-4xl font-bold text-center text-gray-800 mb-4">Selamat Datang di Toko Kue Manis</h1>
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
            <!-- Kue akan dimuat di sini -->
        </div>
        
        <div id="pagination" class="flex justify-center items-center gap-2 mt-12">
            <!-- Pagination akan dimuat di sini -->
        </div>
    </main>

    <!-- Modal Login Popup -->
    <div id="login-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 overflow-hidden">
            <div class="bg-purple-600 text-white p-6">
                <h3 class="text-xl font-bold flex items-center">
                    <i class="fas fa-sign-in-alt mr-2"></i> Login Diperlukan
                </h3>
            </div>
            <div class="p-6">
                <p class="text-gray-600 mb-6">Untuk menambahkan item ke keranjang, Anda perlu login terlebih dahulu.</p>
                <div class="flex space-x-4">
                    <button onclick="closeLoginPopup()" class="flex-1 bg-gray-200 text-gray-800 py-2 px-4 rounded-lg hover:bg-gray-300 transition duration-300">
                        Batal
                    </button>
                    <a href="login.php" class="flex-1 bg-purple-600 text-white py-2 px-4 rounded-lg hover:bg-purple-700 transition duration-300 text-center">
                        Login Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>

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
        $(document).ready(function() {
            loadKue('', 1); // Load semua kue awal halaman 1

            $('#search-kue').on('input', function() {
                var query = $(this).val();
                loadKue(query, 1); // Reset ke halaman 1 saat search
            });
        });

        var isLoggedIn = <?php echo isset($_SESSION['username']) ? 'true' : 'false'; ?>;

        function loadKue(query, page) {
            $.ajax({
                url: 'search_kue.php',
                type: 'GET',
                data: { q: query, page: page },
                dataType: 'json',
                success: function(response) {
                    var kueContainer = $('#kue-container');
                    kueContainer.empty();
                    
                    var data = response.data;
                    if (data.length > 0) {
                        data.forEach(function(kue) {
                            var kueHtml = `
                                <div class='bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition duration-300 transform hover:-translate-y-1 w-60 flex flex-col'>
                                    <img src='images/${kue.gambar}' alt='${kue.nama}' class='w-full aspect-square object-cover'>
                                    <div class='p-6 flex-1 flex flex-col'>
                                        <h3 class='text-xl font-semibold text-gray-800 mb-2'>${kue.nama}</h3>
                                        <p class='text-gray-600 mb-2 line-clamp-3'>${kue.deskripsi}</p>
                                        <button onclick="showDescriptionModal('${kue.nama}', '${kue.deskripsi.replace(/'/g, "\\'\'")}')" class='text-blue-600 hover:text-blue-800 text-sm font-medium mb-4 text-align-left'>Lihat Selengkapnya →</button>
                                        <div class='flex justify-between items-center mt-auto'>
                                            <span class='text-2xl font-bold text-purple-600'>Rp ${kue.harga_formatted}</span>
                                            <span class='text-sm text-gray-500'>Stok: ${kue.stok}</span>
                                        </div>
                                    </div>
                                    <div class='flex items-center justify-between p-4 bg-gray-50'>
                                        <div class='flex items-center'>
                                            <label class='text-sm mr-2'>Qty:</label>
                                            <input type='number' id='qty-${kue.id}' class='w-16 px-2 py-1 border rounded' value='1' min='1' max='${kue.stok}'>
                                        </div>
                                        <button onclick='addToCart(${kue.id})' class='w-12 h-12 bg-gradient-to-r from-green-500 to-blue-500 text-white rounded-full hover:from-green-600 hover:to-blue-600 transition duration-300 flex items-center justify-center'><i class='fas fa-cart-plus'></i></button>
                                    </div>
                                </div>
                            `;
                            kueContainer.append(kueHtml);
                        });
                    } else {
                        kueContainer.html(`
                            <div class='col-span-full text-center py-12'>
                                <i class='fas fa-exclamation-triangle text-6xl text-gray-400 mb-4'></i>
                                <p class='text-xl text-gray-600'>Tidak ada kue ditemukan.</p>
                            </div>
                        `);
                    }
                    
                    // Render pagination
                    renderPagination(query, response.currentPage, response.totalPages);
                },
                error: function() {
                    $('#kue-container').html(`
                        <div class='col-span-full text-center py-12'>
                            <i class='fas fa-exclamation-triangle text-6xl text-gray-400 mb-4'></i>
                            <p class='text-xl text-gray-600'>Terjadi kesalahan saat memuat data.</p>
                        </div>
                    `);
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

        $(document).click(function(e) {
            var modal = $('#description-modal');
            if (e.target == modal[0]) {
                closeDescriptionModal();
            }
        });

        function addToCart(kueId) {
            if (!isLoggedIn) {
                showLoginPopup();
                return;
            }
            var quantity = $('#qty-' + kueId).val();
            $.ajax({
                url: 'add_to_cart.php',
                type: 'POST',
                data: { kue_id: kueId, quantity: quantity },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showSuccessPopup(response.message);
                        // Update cart count di navbar jika ada
                    } else {
                        showErrorPopup(response.message);
                    }
                },
                error: function() {
                    showErrorPopup('Terjadi kesalahan saat menambah ke keranjang');
                }
            });
        }

        function renderPagination(query, currentPage, totalPages) {
            var pagination = $('#pagination');
            pagination.empty();
            
            if (totalPages <= 1) return;
            
            // Previous button
            if (currentPage > 1) {
                pagination.append(`<button class='px-3 py-2 bg-purple-500 text-white rounded hover:bg-purple-600' onclick="loadKue('${query}', ${currentPage - 1})"><i class='fas fa-chevron-left'></i></button>`);
            }
            
            // Page buttons
            var startPage = Math.max(1, currentPage - 2);
            var endPage = Math.min(totalPages, currentPage + 2);
            
            if (startPage > 1) {
                pagination.append(`<button class='px-3 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300' onclick="loadKue('${query}', 1)">1</button>`);
                if (startPage > 2) {
                    pagination.append(`<span class='px-2 py-2'>...</span>`);
                }
            }
            
            for (var i = startPage; i <= endPage; i++) {
                if (i === currentPage) {
                    pagination.append(`<button class='px-3 py-2 bg-purple-600 text-white rounded font-bold'>${i}</button>`);
                } else {
                    pagination.append(`<button class='px-3 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300' onclick="loadKue('${query}', ${i})">${i}</button>`);
                }
            }
            
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    pagination.append(`<span class='px-2 py-2'>...</span>`);
                }
                pagination.append(`<button class='px-3 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300' onclick="loadKue('${query}', ${totalPages})">${totalPages}</button>`);
            }
            
            // Next button
            if (currentPage < totalPages) {
                pagination.append(`<button class='px-3 py-2 bg-purple-500 text-white rounded hover:bg-purple-600' onclick="loadKue('${query}', ${currentPage + 1})"><i class='fas fa-chevron-right'></i></button>`);
            }
        }

        function showLoginPopup() {
            $('#login-modal').removeClass('hidden');
        }

        function closeLoginPopup() {
            $('#login-modal').addClass('hidden');
        }

        function showSuccessPopup(message) {
            $('#success-message').text(message);
            $('#success-modal').removeClass('hidden');
        }

        function closeSuccessPopup() {
            $('#success-modal').addClass('hidden');
        }

        function showErrorPopup(message) {
            $('#error-message').text(message);
            $('#error-modal').removeClass('hidden');
        }

        function closeErrorPopup() {
            $('#error-modal').addClass('hidden');
        }
    </script>

    <!-- Description Modal -->
    <div id='description-modal' class='hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50'>
        <div class='bg-white rounded-lg p-6 max-w-md w-full mx-4 shadow-lg'>
            <div class='flex justify-between items-start'>
                <h3 id='modal-title' class='text-xl font-bold text-gray-800'></h3>
                <button onclick='closeDescriptionModal()' class='text-gray-500 hover:text-gray-700 text-2xl'>&times;</button>
            </div>
            <p id='modal-description' class='text-gray-600 mt-4 whitespace-pre-wrap'></p>
            <button onclick='closeDescriptionModal()' class='mt-6 w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-300'>
                Tutup
            </button>
        </div>
    </div>

<?php include 'includes/footer.php'; ?>