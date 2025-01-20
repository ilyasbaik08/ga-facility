<?php

require_once __DIR__ . '/../config/connection.php';

class RequestModel
{
    private $conn;

    public function __construct()
    {
        $db = new Connection();
        $this->conn = $db->openConnection();
    }

    // Fungsi untuk menyimpan data ke tabel peminta
    public function createRequest($date, $nama_peminta, $ext_phone, $request_date, $request_time, $facility, $nama_item, $jumlah, $satuan, $keterangan, $status_barang, $status)
    {
        $sql = "INSERT INTO peminta (user_id, date, nama_peminta, ext_phone, request_date, request_time, facility, nama_item, jumlah, satuan, keterangan, status_barang, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("issssssisssss", $user_id, $date, $nama_peminta, $ext_phone, $request_date, $request_time, $facility, $nama_item, $jumlah, $satuan, $keterangan, $status_barang, $status);

        return $stmt->execute();
    }

    // Fungsi untuk mengambil data berdasarkan user_id
    public function getRequestsByUser($user_id)
    {
        $sql = "SELECT * FROM peminta WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $requests = [];
        while ($row = $result->fetch_assoc()) {
            $requests[] = $row;
        }

        return $requests;
    }
}
