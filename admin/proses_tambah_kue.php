<?php
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    $gambar = 'default.png';

    // Upload gambar jika ada
    if (!empty($_FILES["gambar"]["name"])) {
        $target_dir = "../images/";
        $target_file = $target_dir . basename($_FILES["gambar"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["gambar"]["tmp_name"]);
        if($check !== false) {
            if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
                $gambar = basename($_FILES["gambar"]["name"]);
            } else {
                echo "Terjadi error ketika mengirimkankan file";
                exit();
            }
        } else {
            echo "File yang kamu kirim bukan gambar";
            exit();
        }
    }

    $sql = "INSERT INTO kue (nama, deskripsi, harga, stok, gambar) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdis", $nama, $deskripsi, $harga, $stok, $gambar);

    if ($stmt->execute()) {
        header("Location: kelola_kue.php?success=1");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $stmt->close();
}

$conn->close();
?>