<?php
require_once __DIR__ . '/BaseApiController.php';
require_once __DIR__ . '/../../Models/PesananModel.php';
require_once __DIR__ . '/../../Middleware/ApiAuth.php';

class OrderApiController extends BaseApiController {
    public function index() {
        $user = ApiAuth::requireAuth();
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $per_page = isset($_GET['per_page']) ? max(1, (int)$_GET['per_page']) : 10;
        $offset = ($page - 1) * $per_page;
        $model = new PesananModel();
        if (isset($user['role']) && $user['role'] === 'admin') {
            $items = $model->getAll($per_page, $offset);
            $total = $model->countAll();
        } else {
            $items = $model->getByUser((int)$user['id'], $per_page, $offset);
            $total = $model->countByUser((int)$user['id']);
        }
        $this->json(['data'=>$items,'meta'=>['page'=>$page,'per_page'=>$per_page,'total'=> (int)$total]]);
    }

    public function show($id) {
        $user = ApiAuth::requireAuth();
        $model = new PesananModel();
        $order = $model->getById((int)$id);
        if (!$order) return $this->error('Pesanan tidak ditemukan',404);
        if ($user['role'] !== 'admin' && $order['user_id'] != $user['id']) {
            return $this->error('Forbidden',403);
        }
        $order['details'] = $model->getDetails((int)$id);
        $this->json($order);
    }

    public function create() {
        $user = ApiAuth::requireAuth();
        $input = json_decode(file_get_contents('php://input'), true);
        if (empty($input['items']) || !is_array($input['items'])) {
            return $this->error('Items of order required',400);
        }
        try {
            $model = new PesananModel();
            $id = $model->create((int)$user['id'], $input['items']);
            $this->json(['id'=>$id,'message'=>'Pesanan dibuat'],201);
        } catch (Exception $e) {
            $this->error($e->getMessage(),400);
        }
    }

    public function update($id) {
        $user = ApiAuth::requireAuth();
        if ($user['role'] !== 'admin') {
            return $this->error('Forbidden',403);
        }
        $input = json_decode(file_get_contents('php://input'), true);
        if (empty($input['status'])) {
            return $this->error('Status required',400);
        }
        $model = new PesananModel();
        $affected = $model->updateStatus((int)$id, $input['status']);
        if ($affected) {
            $this->json(['message'=>'Status diperbarui']);
        } else {
            $this->error('Tidak ada perubahan atau pesanan tidak ditemukan',404);
        }
    }

    public function delete($id) {
        $user = ApiAuth::requireAuth();
        if ($user['role'] !== 'admin') {
            return $this->error('Forbidden',403);
        }
        $model = new PesananModel();
        $affected = $model->delete((int)$id);
        if ($affected) {
            $this->json(['message'=>'Pesanan dihapus']);
        } else {
            $this->error('Pesanan tidak ditemukan',404);
        }
    }
}
