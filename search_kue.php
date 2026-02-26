<?php
include 'includes/db.php';

$query = isset($_GET['q']) ? $_GET['q'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 12; // 3 baris x 4 kolom
$offset = ($page - 1) * $limit;

// Hitung total
$countSql = "SELECT COUNT(*) as total FROM kue WHERE nama LIKE ?";
$countStmt = $conn->prepare($countSql);
$searchTerm = '%' . $query . '%';
$countStmt->bind_param("s", $searchTerm);
$countStmt->execute();
$countResult = $countStmt->get_result();
$totalRow = $countResult->fetch_assoc();
$total = $totalRow['total'];
$totalPages = ceil($total / $limit);

// Ambil data
$sql = "SELECT * FROM kue WHERE nama LIKE ? ORDER BY nama ASC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sii", $searchTerm, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

$kue = array();
while ($row = $result->fetch_assoc()) {
    $row['harga_formatted'] = number_format($row['harga'], 0, ',', '.');
    $kue[] = $row;
}

$response = array(
    'data' => $kue,
    'currentPage' => $page,
    'totalPages' => $totalPages,
    'total' => $total
);

$countStmt->close();
$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>