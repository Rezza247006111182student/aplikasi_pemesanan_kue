<?php
require_once __DIR__ . '/BaseApiController.php';
require_once __DIR__ . '/../../Models/KueModel.php';
require_once __DIR__ . '/../../Middleware/ApiAuth.php';

class KueApiController extends BaseApiController {
    public function index() {
        $q = isset($_GET['q']) ? trim($_GET['q']) : '';
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $per_page = isset($_GET['per_page']) ? max(1, (int)$_GET['per_page']) : 10;
        $offset = ($page - 1) * $per_page;

        $model = new KueModel();
        if ($q !== '') {
            $items = $model->search($q, $per_page, $offset);
            $total = $model->countSearch($q);
        } else {
            $items = $model->getAll($per_page, $offset);
            $total = $model->countAll();
        }

        foreach ($items as &$it) {
            $it['harga_formatted'] = number_format($it['harga'], 0, ',', '.');
            $it['deskripsi_truncated'] = mb_strimwidth($it['deskripsi'], 0, 120, '...');
        }
        unset($it);

        $this->json([
            'data' => $items,
            'meta' => [
                'page' => $page,
                'per_page' => $per_page,
                'total' => (int)$total
            ]
        ]);
    }

    public function show($id) {
        $model = new KueModel();
        $item = $model->getById((int)$id);
        if (!$item) {
            return $this->error('Kue tidak ditemukan', 404);
        }
        $item['harga_formatted'] = number_format($item['harga'], 0, ',', '.');
        $this->json($item);
    }

    public function create() {
        $user = ApiAuth::requireAuth();
        if (!isset($user['role']) || $user['role'] !== 'admin') {
            return $this->error('Forbidden', 403);
        }

        $input = json_decode(file_get_contents('php://input'), true);
        if (!is_array($input)) {
            return $this->error('Payload JSON tidak valid', 400);
        }

        $nama = isset($input['nama']) ? trim($input['nama']) : '';
        $deskripsi = isset($input['deskripsi']) ? trim($input['deskripsi']) : '';
        $harga = isset($input['harga']) ? (float)$input['harga'] : 0;
        $stok = isset($input['stok']) ? (int)$input['stok'] : 0;
        $gambar = isset($input['gambar']) ? trim($input['gambar']) : null;

        if ($nama === '' || $harga <= 0 || $stok < 0) {
            return $this->error('Field wajib: nama, harga > 0, stok >= 0', 400);
        }

        $model = new KueModel();
        $id = $model->create([
            'nama' => $nama,
            'deskripsi' => $deskripsi,
            'harga' => $harga,
            'stok' => $stok,
            'gambar' => $gambar
        ]);

        $this->json(['id' => $id, 'message' => 'Kue berhasil ditambahkan'], 201);
    }

    public function update($id) {
        $user = ApiAuth::requireAuth();
        if (!isset($user['role']) || $user['role'] !== 'admin') {
            return $this->error('Forbidden', 403);
        }

        $kueId = (int)$id;
        if ($kueId < 1) {
            return $this->error('ID kue tidak valid', 400);
        }

        $input = json_decode(file_get_contents('php://input'), true);
        if (!is_array($input)) {
            return $this->error('Payload JSON tidak valid', 400);
        }

        $nama = isset($input['nama']) ? trim($input['nama']) : '';
        $deskripsi = isset($input['deskripsi']) ? trim($input['deskripsi']) : '';
        $harga = isset($input['harga']) ? (float)$input['harga'] : 0;
        $stok = isset($input['stok']) ? (int)$input['stok'] : 0;
        $gambar = isset($input['gambar']) ? trim($input['gambar']) : null;

        if ($nama === '' || $harga <= 0 || $stok < 0) {
            return $this->error('Field wajib: nama, harga > 0, stok >= 0', 400);
        }

        $model = new KueModel();
        $existing = $model->getById($kueId);
        if (!$existing) {
            return $this->error('Kue tidak ditemukan', 404);
        }

        $affected = $model->update($kueId, [
            'nama' => $nama,
            'deskripsi' => $deskripsi,
            'harga' => $harga,
            'stok' => $stok,
            'gambar' => $gambar
        ]);

        if ($affected > 0) {
            return $this->json(['message' => 'Kue berhasil diperbarui']);
        }
        $this->json(['message' => 'Tidak ada perubahan']);
    }

    public function delete($id) {
        $user = ApiAuth::requireAuth();
        if (!isset($user['role']) || $user['role'] !== 'admin') {
            return $this->error('Forbidden', 403);
        }

        $kueId = (int)$id;
        if ($kueId < 1) {
            return $this->error('ID kue tidak valid', 400);
        }

        $model = new KueModel();
        $affected = $model->delete($kueId);
        if ($affected > 0) {
            return $this->json(['message' => 'Kue berhasil dihapus']);
        }
        $this->error('Kue tidak ditemukan', 404);
    }
}
