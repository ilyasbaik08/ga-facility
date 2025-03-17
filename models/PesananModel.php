<?php

require_once __DIR__ . '/../config/connection.php';

class PesananModel
{
    private $conn;

    public function __construct()
    {
        $db = new Connection();
        $this->conn = $db->openConnection();
    }

    public function pesanan($data)
    {
        $sql_peminta = "INSERT INTO peminta (user_id, date, nama_peminta, ext_phone, request_date, request_time, facility) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_peminta = $this->conn->prepare($sql_peminta);
        $stmt_peminta->bind_param(
            "issssss",
            $data['user_id'],
            $data['date'],
            $data['nama_peminta'],
            $data['ext_phone'],
            $data['request_date'],
            $data['request_time'],
            $data['facility']
        );

        if ($stmt_peminta->execute()) {
            $peminta_id = $stmt_peminta->insert_id;

            $sql_items = "INSERT INTO items (peminta_id, id_barang, jumlah, keterangan, status_barang, status) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt_items = $this->conn->prepare($sql_items);

            foreach ($data['items'] as $item) {
                // Cek apakah id_barang ada di master_barang
                $query_check = "SELECT id FROM master_barang WHERE id = ?";
                $stmt_check = $this->conn->prepare($query_check);
                $stmt_check->bind_param("i", $item['id_barang']);
                $stmt_check->execute();
                $stmt_check->store_result();

                if ($stmt_check->num_rows > 0) { // Kalau id_barang valid
                    // Masukkan data ke items tanpa nama_item & satuan
                    $stmt_items->bind_param(
                        "iiisss",
                        $peminta_id,
                        $item['id_barang'],
                        $item['jumlah'],
                        $item['keterangan'],
                        $item['status_barang'],
                        $item['status']
                    );
                    $stmt_items->execute();
                } else {
                    // Kalau id_barang gak ada, kasih log buat debugging
                    error_log("Error: id_barang " . $item['id_barang'] . " tidak ditemukan di master_barang");
                }

                $stmt_check->close();
            }

            return true;
        }

        return false;
    }

    public function getPesanan($searchTerm = '')
    {
        $sql = "
            SELECT
                items.id,
                items.jumlah,
                items.keterangan,
                items.status_barang,
                items.status,
                peminta.id AS peminta_id,  
                peminta.user_id,           
                peminta.nama_peminta,
                users.email AS email_user,
                master_barang.description_item,
                master_barang.satuan
            FROM items
            JOIN peminta ON items.peminta_id = peminta.id
            JOIN users ON peminta.user_id = users.id
            JOIN master_barang ON items.id_barang = master_barang.id
        ";

        if (!empty($searchTerm)) {
            $sql .= " WHERE peminta.nama_peminta LIKE ? OR users.name LIKE ?";
        }

        $sql .= " ORDER BY peminta.created_at DESC";

        $stmt = $this->conn->prepare($sql);

        if (!empty($searchTerm)) {
            $searchParam = '%' . $searchTerm . '%';
            $stmt->bind_param("ss", $searchParam, $searchParam);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $pesanan = [];

        while ($row = $result->fetch_assoc()) {
            $pesanan[] = $row;
        }

        return $pesanan;
    }

    public function getPesananByUser($userId)
    {
        $sql = "
            SELECT
                items.id,
                items.peminta_id,
                items.id_barang,
                items.jumlah,
                items.keterangan,
                items.status_barang,
                items.status,
                peminta.nama_peminta
            FROM items                                                                                                          
            JOIN peminta ON items.peminta_id = peminta.id
            WHERE peminta.user_id = ?  -- Filter berdasarkan user_id
            ORDER BY items.created_at DESC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }

        return $items;
    }

    public function confirmPesanan($user_id, $status_barang, $status)
    {
        $stmt = $this->conn->prepare("UPDATE peminta SET status_barang = ?, status = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("ssi", $status_barang, $status, $user_id);
        return $stmt->execute();
    }

    public function searchPesanan($search)
    {
        $sql = "
        SELECT
            items.id,
            items.jumlah,
            items.keterangan,
            peminta.nama_peminta,
            items.status_barang,
            items.status,
            master_barang.description_item,  -- Ambil description_item
            master_barang.satuan             -- Ambil satuan
        FROM items
        JOIN peminta ON items.peminta_id = peminta.id
        JOIN master_barang ON items.id_barang = master_barang.id
        WHERE master_barang.description_item LIKE ?
        ORDER BY items.created_at DESC
    ";

        $stmt = $this->conn->prepare($sql);
        $searchTerm = "%$search%";
        $stmt->bind_param('s', $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();

        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }

        return $items;
    }

    public function updateStatusBarang($id, $peminta_id, $status_barang)
    {
        $sql = "UPDATE items SET status_barang = ? WHERE id = ? AND peminta_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sii", $status_barang, $id, $peminta_id);
        return $stmt->execute();
    }

    public function updateStatusPesanan($id, $peminta_id, $status)
    {
        $sql = "UPDATE items SET status = ? WHERE id = ? AND peminta_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sii", $status, $id, $peminta_id);
        return $stmt->execute();
    }
}
