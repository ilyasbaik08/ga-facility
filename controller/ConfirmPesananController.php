<?php

require_once __DIR__ . '/../models/PesananModel.php';

class ConfirmPesananController
{
    private $pesananModel;

    public function __construct()
    {
        $this->pesananModel = new PesananModel();
    }

    public function confirmPesanan()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_POST['user_id'];
            $status_barang = $_POST['status_barang'];
            $status = $_POST['status'];

            if ($this->pesananModel->confirmPesanan($user_id, $status_barang, $status)) {
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Status sudah diperbarui'];
            } else {
                $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal memperbarui status pesanan'];
            }

            header("Location: ../views/admin/pesanan.php");
            exit();
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new ConfirmPesananController();
    $controller->confirmPesanan();
}
