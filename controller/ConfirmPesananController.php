<?php
session_start();
require_once __DIR__ . '/../models/PesananModel.php';
require_once __DIR__ . '/../controller/NotifController.php';

class ConfirmPesananController
{
    private $pesananModel;
    private $pesananController;

    public function __construct()
    {
        $this->pesananModel = new PesananModel();
    }

    public function confirmPesanan()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_POST['user_id'];
            $id = $_POST['id']; // Ambil id dari form
            $updateSuccess = false;

            if (isset($_POST['status_barang'])) {
                $status_barang = $_POST['status_barang'];

                // Logika perubahan status_barang
                if ($status_barang === 'waiting confirmation') {
                    $status_barang = 'confirmed';
                } elseif ($status_barang === 'confirmed') {
                    $status_barang = 'on process';
                } elseif ($status_barang === 'on process') {
                    $status_barang = 'completed';
                }

                $updateSuccess = $this->pesananModel->updateStatusBarang($id, $user_id, $status_barang);
                $statusMessage = "Status barang diperbarui menjadi: $status_barang";
            } elseif (isset($_POST['status'])) {
                $status = $_POST['status'];

                // Logika perubahan status
                if ($status === 'Not Approve') {
                    $status = 'Approve';
                }

                $updateSuccess = $this->pesananModel->updateStatusPesanan($id, $user_id, $status);
                $statusMessage = "Status pesanan diperbarui menjadi: $status";
            }

            if ($updateSuccess) {
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Status sudah diperbarui'];

                // 🔥 Kirim email notifikasi ke user
                $userEmail = $this->pesananModel->getUserEmailById($user_id);
                if ($userEmail) {
                    $subject = "Notifikasi Pembaruan Status Pesanan";
                    $message = "Halo, status pesanan Anda telah diperbarui. \n$statusMessage";
                    sendNotification($userEmail, $subject, $message);
                }
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
