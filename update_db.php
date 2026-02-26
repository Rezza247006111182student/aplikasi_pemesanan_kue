<?php
include 'includes/db.php';

// update status enum on pesanan
$sql1 = "ALTER TABLE pesanan MODIFY COLUMN status ENUM('pending', 'confirmed', 'delivered', 'canceled') DEFAULT 'pending'";
if ($conn->query($sql1) === TRUE) {
    echo 'Status enum updated successfully<br>';
} else {
    echo 'Error updating status enum: ' . $conn->error . '<br>';
}

// rename jumlah column to quantity in detail_pesanan if it still exists
// this keeps existing data intact and aligns with session field name
$sql2 = "SHOW COLUMNS FROM detail_pesanan LIKE 'jumlah'";
$result = $conn->query($sql2);
if ($result && $result->num_rows > 0) {
    $alter = "ALTER TABLE detail_pesanan CHANGE jumlah quantity INT";
    if ($conn->query($alter) === TRUE) {
        echo 'Renamed jumlah to quantity successfully<br>';
    } else {
        echo 'Error renaming column: ' . $conn->error . '<br>';
    }
} else {
    echo 'Column jumlah not found, skipping rename.<br>';
}

$conn->close();
?>