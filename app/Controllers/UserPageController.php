<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/PesananModel.php';

class UserPageController extends BaseController {
    private function requireUser($allowAdmin = false) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit();
        }

        if (!$allowAdmin && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            header('Location: login.php');
            exit();
        }
    }

    public function cart() {
        $this->requireUser(true);

        $cart = isset($_SESSION['cart']) && is_array($_SESSION['cart']) ? $_SESSION['cart'] : [];
        $total = 0;
        foreach ($cart as $item) {
            $total += ((float)$item['harga'] * (int)$item['quantity']);
        }

        $this->render('user/keranjang', [
            'cart' => $cart,
            'total' => $total,
        ]);
    }

    public function orders() {
        $this->requireUser(false);

        $model = new PesananModel();
        $items = $model->getByUser((int)$_SESSION['user_id'], 500, 0);

        $this->render('user/pesanan', [
            'orders' => $items,
        ]);
    }

    public function orderDetail() {
        $this->requireUser(false);

        $orderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($orderId < 1) {
            $this->render('user/detail_pesanan', [
                'error' => 'ID pesanan tidak valid.',
                'order' => null,
                'details' => [],
            ]);
            return;
        }

        $model = new PesananModel();
        $order = $model->getById($orderId);
        if (!$order || (int)$order['user_id'] !== (int)$_SESSION['user_id']) {
            $this->render('user/detail_pesanan', [
                'error' => 'Pesanan tidak ditemukan atau Anda tidak memiliki akses.',
                'order' => null,
                'details' => [],
            ]);
            return;
        }

        $details = $model->getDetails($orderId);

        $this->render('user/detail_pesanan', [
            'error' => null,
            'order' => $order,
            'details' => $details,
        ]);
    }

    public function orderSuccess() {
        $this->requireUser(false);

        $orderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $this->render('user/pesanan_sukses', [
            'orderId' => $orderId,
        ]);
    }
}
