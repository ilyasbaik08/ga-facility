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

            $sql_items = "INSERT INTO items (peminta_id, nama_item, jumlah, satuan, keterangan, status_barang, status) 
                          VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt_items = $this->conn->prepare($sql_items);

            foreach ($data['items'] as $item) {
                $stmt_items->bind_param(
                    "isissss",
                    $peminta_id,
                    $item['nama_item'],
                    $item['jumlah'],
                    $item['satuan'],
                    $item['keterangan'],
                    $item['status_barang'],
                    $item['status']
                );
                $stmt_items->execute();
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

    // // Ambil request berdasarkan ID
    // public function getRequestById($request_id)
    // {
    //     $query = "SELECT * FROM requests WHERE id = :id";
    //     $stmt = $this->conn->prepare($query);
    //     $stmt->bind_param(':id', $request_id);
    //     $stmt->execute();
    //     return $stmt->fetch(PDO::FETCH_ASSOC); // Balikin satu data sebagai array
    // }

    // // Update status request
    // public function updateStatus($request_id, $status)
    // {
    //     $query = "UPDATE requests SET status = :status WHERE id = :id";
    //     $stmt = $this->conn->prepare($query);
    //     $stmt->bind_param(':status', $status);
    //     $stmt->bind_param(':id', $request_id);
    //     return $stmt->execute();
    // }

    // // Ambil semua items berdasarkan request_id
    // public function getRequestItems($request_id)
    // {
    //     $query = "SELECT * FROM request_items WHERE request_id = :request_id";
    //     $stmt = $this->conn->prepare($query);
    //     $stmt->bind_param(':request_id', $request_id);
    //     $stmt->execute();
    //     return $stmt->fetch(PDO::FETCH_ASSOC); // Balikin semua item dalam bentuk array
    // }
}
