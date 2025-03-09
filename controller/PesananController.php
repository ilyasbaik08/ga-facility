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
            $data = [
                'user_id' => $_POST['user_id'] ?? null,
                'date' => $_POST['date'] ?? null,
                'nama_peminta' => $_POST['name'] ?? null,
                'ext_phone' => $_POST['ext_phone'] ?? null,
                'request_date' => $_POST['request_date'] ?? null,
                'request_time' => $_POST['request_time'] ?? null,
                'facility' => $_POST['facility'] ?? null,
                'items' => []
            ];

            // 🔍 Cek apakah user_id kosong
            if (empty($data['user_id'])) {
                $_SESSION['message'] = ['type' => 'error', 'text' => 'User ID tidak boleh kosong'];
                header("Location: ../views/user/pesanan.php");
                exit();
            }

            // 🔍 Proses items
            foreach ($_POST['nama_items'] as $key => $nama_item) {
                if (empty($nama_item) || empty($_POST['jumlah'][$key]) || empty($_POST['satuan'][$key]) || empty($_POST['keterangan'][$key])) {
                    continue;
                }

                $data['items'][] = [
                    'nama_item' => $nama_item,
                    'jumlah' => $_POST['jumlah'][$key],
                    'satuan' => $_POST['satuan'][$key],
                    'keterangan' => $_POST['keterangan'][$key],
                    'status_barang' => "waiting confirmation",
                    'status' => "Not Approve",
                ];
            }

            if (empty($data['items'])) {
                $_SESSION['message'] = ['type' => 'error', 'text' => 'Data items kosong atau tidak valid'];
                header("Location: ../views/user/pesanan.php");
                exit();
            }

            // 🔥 Simpan ke database
            if ($this->pesananModel->pesanan($data)) {
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Pesanan berhasil dibuat'];
            } else {
                $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal menyimpan pesanan'];
            }

            header("Location: ../views/user/pesanan.php");
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
