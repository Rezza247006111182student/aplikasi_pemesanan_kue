<?php
include '../includes/db.php';
$pageTitle = 'Kelola Kue';
include '../includes/admin_header.php';
?>
        <?php if (isset($_GET['success'])): ?>
            <div class="mb-4 p-4 rounded-md <?php echo ($_GET['success'] == '1') ? 'bg-green-100 text-green-700' : (($_GET['success'] == '2') ? 'bg-blue-100 text-blue-700' : 'bg-red-100 text-red-700'); ?>">
                <?php
                if ($_GET['success'] == '1') echo '<i class="fas fa-check-circle mr-2"></i> Kue berhasil ditambahkan!';
                elseif ($_GET['success'] == '2') echo '<i class="fas fa-edit mr-2"></i> Kue berhasil diupdate!';
                elseif ($_GET['success'] == '3') echo '<i class="fas fa-trash mr-2"></i> Kue berhasil dihapus!';
                ?>
            </div>
        <?php endif; ?>
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-4xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-birthday-cake text-purple-500 mr-3"></i> Kelola Kue
            </h1>
            <a href="tambah_kue.php" class="bg-gradient-to-r from-green-500 to-blue-500 text-white py-2 px-4 rounded-lg hover:from-green-600 hover:to-blue-600 transition duration-300 font-medium inline-flex items-center">
                <i class="fas fa-plus mr-2"></i> Tambah Kue
            </a>
        </div>

        <!-- Search Bar -->
        <div class="mb-6">
            <form method="GET" class="flex">
                <div class="relative flex-1">
                    <input type="text" name="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" placeholder="Cari kue..." class="w-full px-4 py-2 pl-10 pr-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
                <button type="submit" class="ml-2 bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition duration-300">
                    <i class="fas fa-search"></i>
                </button>
                <?php if (isset($_GET['search']) && !empty($_GET['search'])): ?>
                    <a href="kelola_kue.php" class="ml-2 bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-300">
                        <i class="fas fa-times"></i> Reset
                    </a>
                <?php endif; ?>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <table class="w-full table-auto">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gambar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php
                    include '../includes/db.php';

                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $limit = 10; // 10 kue per halaman
                    $offset = ($page - 1) * $limit;
                    $search = isset($_GET['search']) ? trim($_GET['search']) : '';

                    // Hitung total
                    $count_sql = "SELECT COUNT(*) as total FROM kue";
                    if (!empty($search)) {
                        $count_sql .= " WHERE nama LIKE ?";
                    }
                    $count_stmt = $conn->prepare($count_sql);
                    if (!empty($search)) {
                        $search_term = '%' . $search . '%';
                        $count_stmt->bind_param("s", $search_term);
                    }
                    $count_stmt->execute();
                    $count_result = $count_stmt->get_result();
                    $total_row = $count_result->fetch_assoc();
                    $total = $total_row['total'];
                    $total_pages = ceil($total / $limit);

                    $sql = "SELECT * FROM kue";
                    if (!empty($search)) {
                        $sql .= " WHERE nama LIKE ?";
                    }
                    $sql .= " ORDER BY id DESC LIMIT ? OFFSET ?";
                    $stmt = $conn->prepare($sql);
                    if (!empty($search)) {
                        $stmt->bind_param("sii", $search_term, $limit, $offset);
                    } else {
                        $stmt->bind_param("ii", $limit, $offset);
                    }
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $no = $offset + 1;
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td class='px-6 py-4 whitespace-nowrap text-gray-900'>" . $no++ . "</td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap'><img src='../images/" . $row['gambar'] . "' alt='" . $row['nama'] . "' class='w-16 h-16 object-cover rounded-lg'></td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap font-medium text-gray-900'>" . $row['nama'] . "</td>";
                            echo "<td class='px-6 py-4 text-gray-500'>" . substr($row['deskripsi'], 0, 50) . "...</td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap text-gray-900'>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap text-gray-900'>" . $row['stok'] . "</td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap text-sm font-medium'>";
                            echo "<a href='edit_kue.php?id=" . $row['id'] . "' class='text-indigo-600 hover:text-indigo-900 mr-3'><i class='fas fa-edit'></i> Edit</a>";
                            echo "<a href='hapus_kue.php?id=" . $row['id'] . "' class='text-red-600 hover:text-red-900' onclick='return confirm(\"Yakin hapus?\")'><i class='fas fa-trash'></i> Hapus</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='px-6 py-4 text-center text-gray-500'>Tidak ada kue.</td></tr>";
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <div class="flex justify-center items-center gap-2 mt-6">
            <?php
            $query_params = [];
            if (!empty($search)) $query_params['search'] = $search;
            $query_string = http_build_query($query_params);
            $base_url = '?' . ($query_string ? $query_string . '&' : '');
            ?>
            <?php if ($page > 1): ?>
                <a href="<?php echo $base_url; ?>page=<?php echo $page - 1; ?>" class="px-3 py-2 bg-purple-500 text-white rounded hover:bg-purple-600">
                    <i class="fas fa-chevron-left"></i>
                </a>
            <?php endif; ?>

            <?php
            $start_page = max(1, $page - 2);
            $end_page = min($total_pages, $page + 2);

            if ($start_page > 1) {
                echo '<a href="' . $base_url . 'page=1" class="px-3 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">1</a>';
                if ($start_page > 2) {
                    echo '<span class="px-2 py-2">...</span>';
                }
            }

            for ($i = $start_page; $i <= $end_page; $i++) {
                if ($i == $page) {
                    echo '<span class="px-3 py-2 bg-purple-600 text-white rounded font-bold">' . $i . '</span>';
                } else {
                    echo '<a href="' . $base_url . 'page=' . $i . '" class="px-3 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">' . $i . '</a>';
                }
            }

            if ($end_page < $total_pages) {
                if ($end_page < $total_pages - 1) {
                    echo '<span class="px-2 py-2">...</span>';
                }
                echo '<a href="' . $base_url . 'page=' . $total_pages . '" class="px-3 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">' . $total_pages . '</a>';
            }
            ?>

            <?php if ($page < $total_pages): ?>
                <a href="<?php echo $base_url; ?>page=<?php echo $page + 1; ?>" class="px-3 py-2 bg-purple-500 text-white rounded hover:bg-purple-600">
                    <i class="fas fa-chevron-right"></i>
                </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </main>

<?php include '../includes/admin_footer.php'; ?>