<?php
// session_start();

require_once __DIR__ . '/../config/connection.php';

class RequestModel
{
    private $conn;

    public function __construct()
    {
        $db = new Connection();
        $this->conn = $db->openConnection();
    }

    public function createRequest($data)
    {
        $sql_peminta = "INSERT INTO peminta (user_id, date, nama_peminta, ext_phone, request_date, request_time, facility) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
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

            $sql_items = "INSERT INTO items (peminta_id, id_barang, jumlah, keterangan, status_barang, status) 
                          VALUES (?, ?, ?, ?, ?, ?)";
            $stmt_items = $this->conn->prepare($sql_items);

            foreach ($data['items'] as $item) {
                // Cek apakah id_barang ada di master_barang
                $query_check = "SELECT id FROM master_barang WHERE id = ?";
                $stmt_check = $this->conn->prepare($query_check);
                $stmt_check->bind_param("i", $item['id_barang']);
                $stmt_check->execute();
                $stmt_check->store_result();

                if ($stmt_check->num_rows > 0) { // Kalau id_barang valid
                    // Masukkan data ke items tanpa description_item & satuan
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
                    // Kalau id_barang gak ada, kasih log biar gampang debugging
                    error_log("Error: id_barang " . $item['id_barang'] . " tidak ditemukan di master_barang");
                }

                $stmt_check->close();
            }

            return true;
        }

        return false;
    }

    // Fungsi untuk mengambil data berdasarkan user_id
    public function getRequestsByUser($user_id)
    {
        $sql = "SELECT * FROM peminta WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $peminta = [];
        while ($row = $result->fetch_assoc()) {
            $peminta[] = $row;
        }

        return $peminta;
    }
}
