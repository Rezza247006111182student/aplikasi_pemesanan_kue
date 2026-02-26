<?php
session_start();
include 'includes/auth_check.php';
include 'includes/db.php';

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: keranjang.php");
    exit();
}

$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['harga'] * $item['quantity'];
}

// Insert pesanan
$sql = "INSERT INTO pesanan (user_id, total, status) VALUES (?, ?, 'pending')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("id", $_SESSION['user_id'], $total);

if ($stmt->execute()) {
    $pesanan_id = $conn->insert_id;
    
    // Insert detail pesanan
    foreach ($_SESSION['cart'] as $kue_id => $item) {
        $detail_sql = "INSERT INTO detail_pesanan (pesanan_id, kue_id, quantity, harga) VALUES (?, ?, ?, ?)";
        $detail_stmt = $conn->prepare($detail_sql);
        $detail_stmt->bind_param("iiid", $pesanan_id, $kue_id, $item['quantity'], $item['harga']);
        $detail_stmt->execute();
        $detail_stmt->close();
        
        // Kurangi stok kue
        $update_sql = "UPDATE kue SET stok = stok - ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ii", $item['quantity'], $kue_id);
        $update_stmt->execute();
        $update_stmt->close();
    }
    
    // Clear cart
    unset($_SESSION['cart']);
    header("Location: pesanan_sukses.php?id=" . $pesanan_id);
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$stmt->close();
$conn->close();
?>