<?php
$pageTitle = 'Detail Pesanan';
include 'includes/header.php';
?>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['username']) || $_SESSION['role'] == 'admin') {
    header("Location: login.php");
    exit();
}
?>
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-4xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-list text-purple-500 mr-3"></i> Detail Pesanan
            </h1>
        </div>

        <?php
        include 'includes/db.php';

        if (isset($_GET['id'])) {
            $pesanan_id = $_GET['id'];
            $user_id = $_SESSION['user_id'];

            // Ambil detail pesanan dan pastikan milik user yang sedang login
            $sql = "SELECT * FROM pesanan WHERE id = ? AND user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $pesanan_id, $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $pesanan = $result->fetch_assoc();

                echo "<div class='bg-white rounded-xl shadow-lg p-6 mb-6'>";
                echo "<h2 class='text-2xl font-bold text-gray-800 mb-4'>Informasi Pesanan</h2>";
                echo "<div class='grid grid-cols-1 md:grid-cols-2 gap-4'>";
                echo "<div><strong>ID Pesanan:</strong> #" . str_pad($pesanan['id'], 4, '0', STR_PAD_LEFT) . "</div>";
                echo "<div><strong>Tanggal:</strong> " . date('d/m/Y H:i', strtotime($pesanan['tanggal'])) . "</div>";
                echo "<div><strong>Total:</strong> Rp " . number_format($pesanan['total'], 0, ',', '.') . "</div>";
                echo "<div><strong>Status:</strong> ";
                $status_class = $pesanan['status'] == 'pending' ? 'bg-yellow-100 text-yellow-800' : ($pesanan['status'] == 'confirmed' ? 'bg-green-100 text-green-800' : ($pesanan['status'] == 'canceled' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800'));
                $status_text = $pesanan['status'] == 'pending' ? 'Menunggu Konfirmasi' : ($pesanan['status'] == 'confirmed' ? 'Dikonfirmasi' : ($pesanan['status'] == 'canceled' ? 'Dibatalkan' : 'Selesai'));
                echo "<span class='px-2 inline-flex text-xs leading-5 font-semibold rounded-full " . $status_class . "'>" . $status_text . "</span>";
                echo "</div>";
                echo "</div>";
                echo "</div>";

                // Ambil detail item pesanan
                $detail_sql = "SELECT dp.*, k.nama, k.harga FROM detail_pesanan dp JOIN kue k ON dp.kue_id = k.id WHERE dp.pesanan_id = ?";
                $detail_stmt = $conn->prepare($detail_sql);
                $detail_stmt->bind_param("i", $pesanan_id);
                $detail_stmt->execute();
                $detail_result = $detail_stmt->get_result();

                echo "<div class='bg-white rounded-xl shadow-lg overflow-hidden'>";
                echo "<h2 class='text-2xl font-bold text-gray-800 p-6 pb-0'>Item Pesanan</h2>";
                echo "<table class='w-full table-auto'>";
                echo "<thead class='bg-gray-50'>";
                echo "<tr>";
                echo "<th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>Nama Kue</th>";
                echo "<th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>Harga</th>";
                echo "<th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>Jumlah</th>";
                echo "<th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>Subtotal</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody class='bg-white divide-y divide-gray-200'>";

                while ($detail = $detail_result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td class='px-6 py-4 whitespace-nowrap font-medium text-gray-900'>" . $detail['nama'] . "</td>";
                    echo "<td class='px-6 py-4 whitespace-nowrap text-gray-900'>Rp " . number_format($detail['harga'], 0, ',', '.') . "</td>";
                    echo "<td class='px-6 py-4 whitespace-nowrap text-gray-900'>" . $detail['quantity'] . "</td>";
                    echo "<td class='px-6 py-4 whitespace-nowrap text-gray-900'>Rp " . number_format($detail['harga'] * $detail['quantity'], 0, ',', '.') . "</td>";
                    echo "</tr>";
                }

                echo "</tbody>";
                echo "</table>";
                echo "</div>";
            } else {
                echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6'>
                        <i class='fas fa-exclamation-triangle mr-2'></i> Pesanan tidak ditemukan atau Anda tidak memiliki akses.
                      </div>";
            }
        } else {
            echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6'>
                    <i class='fas fa-exclamation-triangle mr-2'></i> ID pesanan tidak valid.
                  </div>";
        }

        $conn->close();
        ?>

        <div class="text-center mt-6">
            <a href="pesanan_user.php" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Pesanan Saya
            </a>
        </div>
    </main>

<?php include 'includes/footer.php'; ?>