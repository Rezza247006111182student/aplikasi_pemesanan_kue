<?php
$pageTitle = 'Pesanan Saya';
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
?>        <div class="flex justify-between items-center mb-8">
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
                    <?php
                    include 'includes/db.php';

                    $user_id = $_SESSION['user_id'];
                    $sql = "SELECT * FROM pesanan WHERE user_id = ? ORDER BY tanggal DESC";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td class='px-6 py-4 whitespace-nowrap font-medium text-gray-900'>#" . str_pad($row['id'], 4, '0', STR_PAD_LEFT) . "</td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap text-gray-900'>" . date('d/m/Y H:i', strtotime($row['tanggal'])) . "</td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap text-gray-900'>Rp " . number_format($row['total'], 0, ',', '.') . "</td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap'>";
                            $status_class = $row['status'] == 'pending' ? 'bg-yellow-100 text-yellow-800' : ($row['status'] == 'confirmed' ? 'bg-green-100 text-green-800' : ($row['status'] == 'canceled' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800'));
                            $status_text = $row['status'] == 'pending' ? 'Menunggu Konfirmasi' : ($row['status'] == 'confirmed' ? 'Dikonfirmasi' : ($row['status'] == 'canceled' ? 'Dibatalkan' : 'Selesai'));
                            echo "<span class='px-2 inline-flex text-xs leading-5 font-semibold rounded-full " . $status_class . "'>" . $status_text . "</span>";
                            echo "</td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap text-sm font-medium'>";
                            echo "<a href='detail_pesanan_user.php?id=" . $row['id'] . "' class='text-blue-600 hover:text-blue-900 mr-3'>Lihat Detail</a>";
                            if ($row['status'] == 'pending') {
                                echo "<a href='batalkan_pesanan_user.php?id=" . $row['id'] . "' class='text-red-600 hover:text-red-900 mr-3' onclick='return confirm(\"Apakah Anda yakin ingin membatalkan pesanan ini?\")'>Batalkan</a>";
                            } elseif ($row['status'] == 'canceled') {
                                echo "<a href='hapus_pesanan_user.php?id=" . $row['id'] . "' class='text-red-600 hover:text-red-900' onclick='return confirm(\"Apakah Anda yakin ingin menghapus pesanan ini secara permanen?\")'>Hapus</a>";
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='px-6 py-4 text-center text-gray-500'>Belum ada pesanan.</td></tr>";
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>

        <?php if ($result->num_rows == 0): ?>
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
    </main>

<?php include 'includes/footer.php'; ?>