<?php
// Script to clear all data from all tables except the 'kue' table.
// This will remove users as well, so use with caution.
// Run once from command line: php reset_records.php
include 'includes/db.php';

// List every table we want to truncate except kue.
$tables = ['users', 'pesanan', 'detail_pesanan'];
foreach ($tables as $table) {
    $sql = "TRUNCATE TABLE $table";
    if ($conn->query($sql) === TRUE) {
        echo "Table $table truncated successfully\n";
    } else {
        echo "Error truncating $table: " . $conn->error . "\n";
    }
}

// reset auto-increments as a safety measure
$conn->query("ALTER TABLE users AUTO_INCREMENT = 1");
$conn->query("ALTER TABLE pesanan AUTO_INCREMENT = 1");
$conn->query("ALTER TABLE detail_pesanan AUTO_INCREMENT = 1");

$conn->close();
echo "Done. Only 'kue' table retains its data.\n";
