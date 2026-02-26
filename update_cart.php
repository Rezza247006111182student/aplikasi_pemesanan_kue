<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kue_id = (int)$_POST['kue_id'];
    $quantity = (int)$_POST['quantity'];

    if (isset($_SESSION['cart'][$kue_id])) {
        if ($quantity > 0) {
            $_SESSION['cart'][$kue_id]['quantity'] = $quantity;
            echo json_encode(array('success' => true));
        } else {
            unset($_SESSION['cart'][$kue_id]);
            echo json_encode(array('success' => true));
        }
    } else {
        echo json_encode(array('success' => false, 'message' => 'Item tidak ditemukan'));
    }
}
?>