<?php
session_start();
include 'includes/db.php';

// Inisialisasi keranjang jika belum ada
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kue_id = (int)$_POST['kue_id'];
    $quantity = (int)$_POST['quantity'];

    // Cek apakah kue ada dan stok cukup
    $sql = "SELECT * FROM kue WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $kue_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $kue = $result->fetch_assoc();
        
        if ($kue['stok'] >= $quantity) {
            // Tambah ke keranjang
            if (isset($_SESSION['cart'][$kue_id])) {
                $_SESSION['cart'][$kue_id]['quantity'] += $quantity;
            } else {
                $_SESSION['cart'][$kue_id] = array(
                    'nama' => $kue['nama'],
                    'harga' => $kue['harga'],
                    'gambar' => $kue['gambar'],
                    'quantity' => $quantity
                );
            }
            
            echo json_encode(array('success' => true, 'message' => 'Item ditambahkan ke keranjang'));
        } else {
            echo json_encode(array('success' => false, 'message' => 'Stok tidak cukup'));
        }
    } else {
        echo json_encode(array('success' => false, 'message' => 'Kue tidak ditemukan'));
    }

    $stmt->close();
}

$conn->close();
?>