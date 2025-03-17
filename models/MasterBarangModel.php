<?php
require_once __DIR__ . '/../config/connection.php';

class MasterBarang
{
    private $conn;

    public function __construct()
    {
        $db = new Connection();
        $this->conn = $db->openConnection();
    }

    public function getAllBarang()
    {
        $sql = "SELECT kode_item, description_item, satuan FROM master_barang";
        $result = $this->conn->query($sql);

        $barang = [];
        while ($row = $result->fetch_assoc()) {
            $barang[] = $row;
        }

        return $barang;
    }
}
