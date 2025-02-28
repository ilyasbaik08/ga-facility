<?php

require_once __DIR__ . '/../models/PesananModel.php';

class PesananController
{
    private $pesananModel;

    public function __construct()
    {
        $this->pesananModel = new PesananModel();
    }

    public function pesanan()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_POST['user_id'];
            $date = $_POST['date'];
            $nama_peminta = $_POST['name'];
            $ext_phone = $_POST['ext_phone'];
            $request_date = $_POST['request_date'];
            $request_time = $_POST['request_time'];
            $facility = $_POST['facility'];
            $nama_item = $_POST['nama_item'];
            $jumlah = $_POST['jumlah'];
            $satuan = $_POST['satuan'];
            $keterangan = $_POST['keterangan'];
            $status_barang = "waiting confirmation";
            $status = "Not Approve";

            if ($this->pesananModel->pesanan($user_id, $date, $nama_peminta, $ext_phone, $request_date, $request_time, $facility, $nama_item, $jumlah, $satuan, $keterangan, $status_barang, $status)) {
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Pesanan akan segera diproses'];
            }

            header("Location:  ../views/user/pesanan.php");
            exit();
        }
    }

    public function getPesanan($searchTerm = '')
    {
        return $this->pesananModel->getPesanan($searchTerm);
    }

    public function getPesananByUser()
    {
        if (isset($_SESSION['user'])) {
            $user_id = $_SESSION['user']['id'];
            $items = $this->pesananModel->getPesananByUser($user_id);
            return $items;
        } else {
            header("Location: ../index.php");
            exit();
        }
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
            } elseif (isset($_POST['status'])) {
                $status = $_POST['status'];
    
                // Logika perubahan status
                if ($status === 'Not Approve') {
                    $status = 'Approve';
                }
    
                $updateSuccess = $this->pesananModel->updateStatusPesanan($id, $user_id, $status);
            }
    
            if ($updateSuccess) {
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
    $controller = new PesananController();
    $controller->pesanan();
}
