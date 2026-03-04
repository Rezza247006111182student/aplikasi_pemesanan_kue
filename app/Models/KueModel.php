<?php
require_once __DIR__ . '/Model.php';

class KueModel extends Model {
    public function getAll($limit = 50, $offset = 0) {
        $stmt = $this->db->prepare("SELECT * FROM kue ORDER BY id DESC LIMIT ? OFFSET ?");
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        $res = $stmt->get_result();
        $rows = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    public function search($q, $limit = 50, $offset = 0) {
        $like = '%' . $q . '%';
        $stmt = $this->db->prepare("SELECT * FROM kue WHERE nama LIKE ? OR deskripsi LIKE ? ORDER BY id DESC LIMIT ? OFFSET ?");
        $stmt->bind_param("ssii", $like, $like, $limit, $offset);
        $stmt->execute();
        $res = $stmt->get_result();
        $rows = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    public function countSearch($q) {
        $like = '%' . $q . '%';
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM kue WHERE nama LIKE ? OR deskripsi LIKE ?");
        $stmt->bind_param("ss", $like, $like);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $stmt->close();
        return (int)$row['total'];
    }

    public function countAll() {
        $res = $this->db->query("SELECT COUNT(*) as total FROM kue");
        $row = $res->fetch_assoc();
        return (int)$row['total'];
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM kue WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $stmt->close();
        return $row;
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO kue (nama, deskripsi, harga, stok, gambar) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('ssdis', $data['nama'], $data['deskripsi'], $data['harga'], $data['stok'], $data['gambar']);
        $stmt->execute();
        $id = $stmt->insert_id;
        $stmt->close();
        return $id;
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE kue SET nama = ?, deskripsi = ?, harga = ?, stok = ?, gambar = ? WHERE id = ?");
        $stmt->bind_param('ssdisi', $data['nama'], $data['deskripsi'], $data['harga'], $data['stok'], $data['gambar'], $id);
        $stmt->execute();
        $affected = $stmt->affected_rows;
        $stmt->close();
        return $affected;
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM kue WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $affected = $stmt->affected_rows;
        $stmt->close();
        return $affected;
    }
}
