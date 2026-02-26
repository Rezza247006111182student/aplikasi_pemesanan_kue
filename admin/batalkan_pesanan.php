<?php
$pageTitle = 'Batalkan Pesanan';
include '../includes/admin_header.php';
?>
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-4xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-times-circle text-red-500 mr-3"></i> Batalkan Pesanan
            </h1>
        </div>

        <?php
        include '../includes/db.php';

        if (isset($_GET['id'])) {
            $pesanan_id = $_GET['id'];

            // Ambil detail pesanan
            $sql = "SELECT p.*, u.username FROM pesanan p JOIN users u ON p.user_id = u.id WHERE p.id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $pesanan_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $pesanan = $result->fetch_assoc();

                if ($pesanan['status'] == 'pending') {
                    // Batalkan pesanan
                    $update_sql = "UPDATE pesanan SET status = 'canceled' WHERE id = ?";
                    $update_stmt = $conn->prepare($update_sql);
                    $update_stmt->bind_param("i", $pesanan_id);
                    $update_stmt->execute();

                    // Kembalikan stok kue
                    $detail_sql = "SELECT * FROM detail_pesanan WHERE pesanan_id = ?";
                    $detail_stmt = $conn->prepare($detail_sql);
                    $detail_stmt->bind_param("i", $pesanan_id);
                    $detail_stmt->execute();
                    $detail_result = $detail_stmt->get_result();

                    while ($detail = $detail_result->fetch_assoc()) {
                        $stok_sql = "UPDATE kue SET stok = stok + ? WHERE id = ?";
                        $stok_stmt = $conn->prepare($stok_sql);
                        $stok_stmt->bind_param("ii", $detail['quantity'], $detail['kue_id']);
                        $stok_stmt->execute();
                    }

                    echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6'>
                            <i class='fas fa-times-circle mr-2'></i> Pesanan berhasil dibatalkan dan stok kue telah dikembalikan.
                          </div>";
                } else {
                    echo "<div class='bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-6'>
                            <i class='fas fa-exclamation-triangle mr-2'></i> Pesanan tidak dapat dibatalkan karena status sudah berubah.
                          </div>";
                }
            } else {
                echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6'>
                        <i class='fas fa-exclamation-triangle mr-2'></i> Pesanan tidak ditemukan.
                      </div>";
            }
        } else {
            echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6'>
                    <i class='fas fa-exclamation-triangle mr-2'></i> ID pesanan tidak valid.
                  </div>";
        }

        $conn->close();
        ?>

        <div class="text-center">
            <a href="kelola_pesanan.php" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Kelola Pesanan
            </a>
        </div>
    </main>

<?php include '../includes/admin_footer.php'; ?>