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
    public function createRequest($user_id, $date, $nama_peminta, $ext_phone, $request_date, $request_time, $facility, $nama_items, $jumlahs, $satuans, $keterangans, $status_barang, $status)
    {
        $sql = "INSERT INTO peminta (user_id, date, nama_peminta, ext_phone, request_date, request_time, facility, nama_item, jumlah, satuan, keterangan, status_barang, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        // Debug isi nama_items sebelum loop
        echo "<pre>";
        print_r($nama_items); // Cek isi array
        echo "</pre>";

        // Looping melalui array dan memasukkan setiap item
        for ($i = 0; $i < count($nama_items); $i++) {
            $nama_item = $nama_items[$i];
            $jumlah = $jumlahs[$i];
            $satuan = $satuans[$i];
            $keterangan = $keterangans[$i];

            // Gunakan nilai $user_id sesuai kebutuhan
            $user_id = $_SESSION['user']['id'];

            $stmt->bind_param("issssssisssss", $user_id, $date, $nama_peminta, $ext_phone, $request_date, $request_time, $facility, $nama_item, $jumlah, $satuan, $keterangan, $status_barang, $status);

            // Eksekusi query di setiap iterasi
            if (!$stmt->execute()) {
                echo "Error: " . $stmt->error; // Debug jika ada error
            }
        }
        return true;
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
