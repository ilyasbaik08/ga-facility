<?php
session_start();
require_once __DIR__ . '/../models/PesananModel.php';
require_once __DIR__ . '/NotifController.php';

class ConfirmPesananController
{
    private $pesananModel;
    private $notifController;

    public function __construct()
    {
        $this->pesananModel = new PesananModel();
        $this->notifController = new NotifController();
    }

    public function confirmPesanan()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['id']) || !isset($_POST['peminta_id']) || (!isset($_POST['status_barang']) && !isset($_POST['status']))) {
                $_SESSION['message'] = ['type' => 'error', 'text' => 'DATA GAK ADA, FORM ERROR!'];
                header("Location: ../views/admin/pesanan.php");
                exit();
            }

            $id = $_POST['id'];
            $peminta_id = $_POST['peminta_id'];
            $email_peminta = $_POST['email_peminta'];

            if (isset($_POST['status_barang'])) {
                $status_barang = $_POST['status_barang'];
                $updateSukses = $this->pesananModel->updateStatusBarang($id, $peminta_id, $status_barang);

                if ($updateSukses && $status_barang === 'closed') {
                    $this->notifController->notif_closed($email_peminta);
                }
            } elseif (isset($_POST['status'])) {
                $status = $_POST['status'];
                $updateSukses = $this->pesananModel->updateStatusPesanan($id, $peminta_id, $status);

                if ($updateSukses) {
                    $this->notifController->notif_approve($email_peminta);
                }
            } else {
                $_SESSION['message'] = ['type' => 'error', 'text' => 'Data tidak valid!'];
                header("Location: ../views/admin/pesanan.php");
                exit();
            }

            if ($updateSukses) {
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
