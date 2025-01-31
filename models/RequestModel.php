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
        $sql_peminta = "INSERT INTO peminta (user_id, date, nama_peminta, ext_phone, request_date, request_time, facility, status_barang, status) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_peminta = $this->conn->prepare($sql_peminta);
        $stmt_peminta->bind_param(
            "issssssss",
            $data['user_id'],
            $data['date'],
            $data['nama_peminta'],
            $data['ext_phone'],
            $data['request_date'],
            $data['request_time'],
            $data['facility'],
            $data['status_barang'],
            $data['status']
        );
    
        if ($stmt_peminta->execute()) {
            $peminta_id = $stmt_peminta->insert_id; 
    
            $sql_items = "INSERT INTO items (peminta_id, nama_item, jumlah, satuan, keterangan) 
                          VALUES (?, ?, ?, ?, ?)";
            $stmt_items = $this->conn->prepare($sql_items);
    
            foreach ($data['items'] as $item) {
                $stmt_items->bind_param(
                    "isiss",
                    $peminta_id,
                    $item['nama_item'],
                    $item['jumlah'],
                    $item['satuan'],
                    $item['keterangan']
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
}
