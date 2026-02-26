<?php
include '../includes/db.php';
$pageTitle = 'Kelola Pesanan';
include '../includes/admin_header.php';
?>
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-4xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-shopping-bag text-purple-500 mr-3"></i> Kelola Pesanan
            </h1>
        </div>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <table class="w-full table-auto">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php
                    include '../includes/db.php';

                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $limit = 10; // 10 pesanan per halaman
                    $offset = ($page - 1) * $limit;

                    // Hitung total
                    $count_sql = "SELECT COUNT(*) as total FROM pesanan p JOIN users u ON p.user_id = u.id";
                    $count_result = $conn->query($count_sql);
                    $total_row = $count_result->fetch_assoc();
                    $total = $total_row['total'];
                    $total_pages = ceil($total / $limit);

                    $sql = "SELECT p.*, u.username FROM pesanan p JOIN users u ON p.user_id = u.id ORDER BY p.tanggal DESC LIMIT ? OFFSET ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ii", $limit, $offset);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $no = $offset + 1;
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td class='px-6 py-4 whitespace-nowrap font-medium text-gray-900'>" . $no++ . "</td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap text-gray-900'>" . $row['username'] . "</td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap text-gray-900'>" . date('d/m/Y H:i', strtotime($row['tanggal'])) . "</td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap text-gray-900'>Rp " . number_format($row['total'], 0, ',', '.') . "</td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap'>";
                            $status_class = $row['status'] == 'pending' ? 'bg-yellow-100 text-yellow-800' : ($row['status'] == 'confirmed' ? 'bg-green-100 text-green-800' : ($row['status'] == 'canceled' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800'));
                            echo "<span class='px-2 inline-flex text-xs leading-5 font-semibold rounded-full " . $status_class . "'>" . ucfirst($row['status']) . "</span>";
                            echo "</td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap text-sm font-medium'>";
                            if ($row['status'] == 'pending') {
                                echo "<a href='konfirmasi_pesanan.php?id=" . $row['id'] . "' class='text-green-600 hover:text-green-900 mr-3'>Konfirmasi</a>";
                                echo "<a href='batalkan_pesanan.php?id=" . $row['id'] . "' class='text-red-600 hover:text-red-900 mr-3' onclick='return confirm(\"Apakah Anda yakin ingin membatalkan pesanan ini?\")'>Batalkan</a>";
                            }
                            echo "<a href='detail_pesanan.php?id=" . $row['id'] . "' class='text-blue-600 hover:text-blue-900'>Detail</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='px-6 py-4 text-center text-gray-500'>Tidak ada pesanan.</td></tr>";
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <div class="flex justify-center items-center gap-2 mt-6">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>" class="px-3 py-2 bg-purple-500 text-white rounded hover:bg-purple-600">
                    <i class="fas fa-chevron-left"></i>
                </a>
            <?php endif; ?>

            <?php
            $start_page = max(1, $page - 2);
            $end_page = min($total_pages, $page + 2);

            if ($start_page > 1) {
                echo '<a href="?page=1" class="px-3 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">1</a>';
                if ($start_page > 2) {
                    echo '<span class="px-2 py-2">...</span>';
                }
            }

            for ($i = $start_page; $i <= $end_page; $i++) {
                if ($i == $page) {
                    echo '<span class="px-3 py-2 bg-purple-600 text-white rounded font-bold">' . $i . '</span>';
                } else {
                    echo '<a href="?page=' . $i . '" class="px-3 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">' . $i . '</a>';
                }
            }

            if ($end_page < $total_pages) {
                if ($end_page < $total_pages - 1) {
                    echo '<span class="px-2 py-2">...</span>';
                }
                echo '<a href="?page=' . $total_pages . '" class="px-3 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">' . $total_pages . '</a>';
            }
            ?>

            <?php if ($page < $total_pages): ?>
                <a href="?page=<?php echo $page + 1; ?>" class="px-3 py-2 bg-purple-500 text-white rounded hover:bg-purple-600">
                    <i class="fas fa-chevron-right"></i>
                </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </main>

<?php include '../includes/admin_footer.php'; ?>