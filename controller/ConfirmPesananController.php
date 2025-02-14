<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['user_id']) || (!isset($_POST['status_barang'])  && !isset($_POST['status']))) {
        die("DATA GAK ADA, FORM ERROR!");
    }
}

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

            if (isset($_POST['status_barang'])) {
                $status_barang = $_POST['status_barang'];
                $updateSucsess = $this->pesananModel->updateStatusBarang($user_id, $status_barang);
            } elseif (isset($_POST['status'])) {
                $status = $_POST['status'];
                $updateSucsess = $this->pesananModel->updateStatuspesanan($user_id, $status);
            } else {
                $_SESSION['message'] = ['type' => 'error', 'text' => 'Data tidak valid!'];
                header("Location: ../views/admin/pesanan.php");
                exit();
            }

            if ($updateSucsess) {
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
