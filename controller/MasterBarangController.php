<?php
require_once __DIR__ . '/../models/MasterBarangModel.php';

class MasterBarangController
{
    public function getBarang()
    {
        $barangModel = new MasterBarang();
        return $barangModel->getAllBarang(); 
    }
}