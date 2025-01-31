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

    public function pesanan($user_id, $date, $nama_peminta, $ext_phone, $request_date, $request_time, $facility, $nama_items, $jumlahs, $satuans, $keterangans, $status_barang, $status)
    {
        $sql = "INSERT INTO peminta (user_id, date, name, ext_phone, request_date, request_time, facility, nama_item, jumlah, satuan, keterangan, status_barang, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isssssssssssss", $user_id, $date, $nama_peminta, $ext_phone, $request_date, $request_time, $facility, $nama_items, $jumlahs, $satuans, $keterangans, $status_barang, $status);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getPesanan($searchTerm = '')
    {
        $sql = "
            SELECT
                items.id,
                items.nama_item,
                items.jumlah,
                items.satuan,
                items.keterangan,
                peminta.nama_peminta,
                peminta.status_barang,
                peminta.status
            FROM items
            JOIN peminta ON items.peminta_id = peminta.id
        ";

        if (!empty($searchTerm)) {
            $sql .= " WHERE peminta.nama_peminta LIKE ?";
        }

        $sql .= " ORDER BY peminta.created_at DESC";

        $stmt = $this->conn->prepare($sql);

        if (!empty($searchTerm)) {
            $searchParam = '%' . $searchTerm . '%';
            $stmt->bind_param("s", $searchParam);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $peminta = [];

        while ($row = $result->fetch_assoc()) {
            $peminta[] = $row;
        }

        return $peminta;
    }

    public function getPesananByUser($userId)
    {
        $sql = "
            SELECT
                items.id,
                items.peminta_id,
                items.nama_item,
                items.jumlah,
                items.satuan,
                items.keterangan,
                peminta.nama_peminta,
                peminta.status_barang,
                peminta.status
            FROM items                                                                                                          
            JOIN peminta ON items.peminta_id = peminta.id
            WHERE items.peminta_id = ?
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

    public function confirmPesanan($id, $status_barang, $status)
    {
        $stmt = $this->conn->prepare("UPDATE peminta SET status_barang = ?, status = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("ssi", $status_barang, $status, $id);
        return $stmt->execute();
    }

    public function searchPesanan($search)
    {
        $sql = "
            SELECT
                items.id,
                items.nama_item,
                items.jumlah,
                items.satuan,
                items.keterangan,
                peminta.nama_peminta,
                peminta.status_barang,
                peminta.status
            FROM items
            JOIN peminta ON items.peminta_id = peminta.id
            WHERE items.nama_item LIKE ?
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
}
