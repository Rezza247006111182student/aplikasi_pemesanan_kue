<?php
require_once __DIR__ . '/Model.php';

class PesananModel extends Model {
    public function create($userId, $items) {
        // $items = [ ['kue_id'=>..,'quantity'=>..], ... ]
        $this->db->begin_transaction();
        try {
            $stmt = $this->db->prepare("INSERT INTO pesanan (user_id, status, created_at) VALUES (?, 'pending', NOW())");
            $stmt->bind_param('i', $userId);
            $stmt->execute();
            $pesanan_id = $stmt->insert_id;
            $stmt->close();

            $detailStmt = $this->db->prepare("INSERT INTO detail_pesanan (pesanan_id, kue_id, quantity) VALUES (?, ?, ?)");
            $stockStmt = $this->db->prepare("UPDATE kue SET stok = stok - ? WHERE id = ? AND stok >= ?");
            foreach ($items as $it) {
                $kid = (int)$it['kue_id'];
                $qty = (int)$it['quantity'];
                if ($qty < 1) throw new Exception('Quantity harus >= 1');
                // decrement stock
                $stockStmt->bind_param('iii', $qty, $kid, $qty);
                $stockStmt->execute();
                if ($stockStmt->affected_rows === 0) {
                    throw new Exception('Stok tidak cukup untuk kue ' . $kid);
                }
                // insert detail
                $detailStmt->bind_param('iii', $pesanan_id, $kid, $qty);
                $detailStmt->execute();
            }
            $detailStmt->close();
            $stockStmt->close();
            $this->db->commit();
            return $pesanan_id;
        } catch (Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }

    public function getByUser($userId, $limit=50, $offset=0) {
        $stmt = $this->db->prepare("SELECT * FROM pesanan WHERE user_id = ? ORDER BY id DESC LIMIT ? OFFSET ?");
        $stmt->bind_param('iii', $userId, $limit, $offset);
        $stmt->execute();
        $res = $stmt->get_result();
        $rows = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    public function countByUser($userId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM pesanan WHERE user_id = ?");
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $stmt->close();
        return (int)$row['total'];
    }

    public function getAll($limit=50, $offset=0) {
        $stmt = $this->db->prepare("SELECT * FROM pesanan ORDER BY id DESC LIMIT ? OFFSET ?");
        $stmt->bind_param('ii', $limit, $offset);
        $stmt->execute();
        $res = $stmt->get_result();
        $rows = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    public function countAll() {
        $res = $this->db->query("SELECT COUNT(*) as total FROM pesanan");
        $row = $res->fetch_assoc();
        return (int)$row['total'];
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM pesanan WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $stmt->close();
        return $row;
    }

    public function updateStatus($id, $status) {
        $stmt = $this->db->prepare("UPDATE pesanan SET status = ? WHERE id = ?");
        $stmt->bind_param('si', $status, $id);
        $stmt->execute();
        $affected = $stmt->affected_rows;
        $stmt->close();
        return $affected;
    }

    public function delete($id) {
        // cascade: delete details first then order
        $stmt = $this->db->prepare("DELETE FROM detail_pesanan WHERE pesanan_id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();
        $stmt = $this->db->prepare("DELETE FROM pesanan WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $affected = $stmt->affected_rows;
        $stmt->close();
        return $affected;
    }

    public function getDetails($pesananId) {
        $stmt = $this->db->prepare("SELECT dp.*, k.nama, k.gambar, k.harga FROM detail_pesanan dp JOIN kue k ON k.id = dp.kue_id WHERE dp.pesanan_id = ?");
        $stmt->bind_param('i', $pesananId);
        $stmt->execute();
        $res = $stmt->get_result();
        $rows = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }
}
