<?php
include '../includes/db.php';
$id = $_GET['id'];
$sql = "SELECT * FROM kue WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$kue = $result->fetch_assoc();
$stmt->close();
$conn->close();
$pageTitle = 'Edit Kue';
include '../includes/admin_header.php';
?>
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-4xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-edit text-blue-500 mr-3"></i> Edit Kue
            </h1>
            <a href="kelola_kue.php" class="bg-gray-500 text-white py-2 px-4 rounded-lg hover:bg-gray-600 transition duration-300 font-medium inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-8">
            <form action="proses_edit_kue.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $kue['id']; ?>">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama Kue</label>
                        <input type="text" id="nama" name="nama" value="<?php echo $kue['nama']; ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div>
                        <label for="harga" class="block text-sm font-medium text-gray-700 mb-2">Harga</label>
                        <input type="number" id="harga" name="harga" value="<?php echo $kue['harga']; ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div class="md:col-span-2">
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"><?php echo $kue['deskripsi']; ?></textarea>
                    </div>
                    <div>
                        <label for="stok" class="block text-sm font-medium text-gray-700 mb-2">Stok</label>
                        <input type="number" id="stok" name="stok" value="<?php echo $kue['stok']; ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div>
                        <label for="gambar" class="block text-sm font-medium text-gray-700 mb-2">Gambar Baru (opsional)</label>
                        <input type="file" id="gambar" name="gambar" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <p class="text-sm text-gray-500 mt-1">Gambar saat ini: <img src="../images/<?php echo $kue['gambar']; ?>" alt="Current" class="w-16 h-16 object-cover rounded inline-block ml-2"></p>
                    </div>
                </div>
                <div class="mt-6">
                    <button type="submit" class="bg-gradient-to-r from-blue-500 to-purple-500 text-white py-2 px-6 rounded-lg hover:from-blue-600 hover:to-purple-600 transition duration-300 font-medium">
                        <i class="fas fa-save mr-2"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </main>

<?php include '../includes/admin_footer.php'; ?>