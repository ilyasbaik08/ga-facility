<?php
session_start();
require_once __DIR__ . '/../models/PesananModel.php';
require_once __DIR__ . '/../controller/PesananController.php';

class ConfirmPesananController
{
    private $pesananModel;
    private $pesananController;

    public function __construct()
    {
        $this->pesananModel = new PesananModel();
        $this->pesananController = new PesananController();
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

            if (isset($_POST['status_barang'])) {
                $status_barang = $_POST['status_barang'];
                $updateSukses = $this->pesananModel->updateStatusBarang($id, $peminta_id, $status_barang);
            } elseif (isset($_POST['status'])) {
                $status = $_POST['status'];
                $updateSukses = $this->pesananModel->updateStatusPesanan($id, $peminta_id, $status);
            } else {
                $_SESSION['message'] = ['type' => 'error', 'text' => 'Data tidak valid!'];
                header("Location: ../views/admin/pesanan.php");
                exit();
            }

            if ($updateSukses) {
                if (isset($status_barang) && $status_barang = 'closed') {
                    $user = $this->pesananController->getPesananByUser($peminta_id);
                    if ($user) {
                        $to = $user['email'];
                        $subject = "Pesanan anda siap di ambil";
                        $message = "
                            <html>
                            <head>
                                <title>Pesanan Selesai</title>
                            </head>
                            <body>
                                <p>Halo, {$user['name']}!</p>
                                <p>Pesanan anda telah ditututp.</p>
                            </body>
                            </html>
                        ";
                        $headers = "MIME-Version: 1.0" . "\r\n";
                        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                        $headers .= "From: no-reply@yourdomain.com" . "\r\n";

                        mail($to, $subject, $message, $headers);
                    }
                }
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
