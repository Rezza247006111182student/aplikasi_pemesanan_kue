<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] == 'admin') {
    header("Location: login.php");
    exit();
}
$pageTitle = "Hapus Pesanan";
include 'includes/header.php';
?>

<!-- main content -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-4xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-trash text-red-500 mr-3"></i> Hapus Pesanan
            </h1>
        </div>

        <?php
        include 'includes/db.php';

        if (isset($_GET['id'])) {
            $pesanan_id = $_GET['id'];
            $user_id = $_SESSION['user_id'];

            // Ambil detail pesanan dan pastikan milik user dan status canceled
            $sql = "SELECT * FROM pesanan WHERE id = ? AND user_id = ? AND status = 'canceled'";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $pesanan_id, $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Hapus detail pesanan dulu
                $delete_detail_sql = "DELETE FROM detail_pesanan WHERE pesanan_id = ?";
                $delete_detail_stmt = $conn->prepare($delete_detail_sql);
                $delete_detail_stmt->bind_param("i", $pesanan_id);
                $delete_detail_stmt->execute();

                // Hapus pesanan
                $delete_sql = "DELETE FROM pesanan WHERE id = ?";
                $delete_stmt = $conn->prepare($delete_sql);
                $delete_stmt->bind_param("i", $pesanan_id);
                $delete_stmt->execute();

                echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6'>
                        <i class='fas fa-trash mr-2'></i> Pesanan berhasil dihapus secara permanen.
                      </div>";
            } else {
                echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6'>
                        <i class='fas fa-exclamation-triangle mr-2'></i> Pesanan tidak ditemukan, tidak milik Anda, atau belum dibatalkan.
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
            <a href="pesanan_user.php" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Pesanan Saya
            </a>
        </div>
    </main>


<?php include 'includes/footer.php'; ?>