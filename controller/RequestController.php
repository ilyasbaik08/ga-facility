<?php
// session_start();

require_once __DIR__ . '/../models/RequestModel.php';
require_once __DIR__ . '/NotifController.php';

class RequestController
{
    private $requestModel;

    public function __construct()
    {
        $this->requestModel = new RequestModel();
    }


    public function createRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'user_id' => $_POST['user_id'],
                'date' => $_POST['date'],
                'nama_peminta' => $_POST['name'],
                'ext_phone' => $_POST['ext_phone'],
                'request_date' => $_POST['request_date'],
                'request_time' => $_POST['request_time'],
                'facility' => $_POST['facility'],
                'items' => []
            ];

            foreach ($_POST['id_barang'] as $key => $id_barang) {
                if (empty($_POST['jumlah'][$key]) || empty($_POST['keterangan'][$key])) {
                    continue;
                }

                $data['items'][] = [
                    'id_barang' => $id_barang,
                    'jumlah' => $_POST['jumlah'][$key],
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

            if ($this->requestModel->createRequest($data)) {
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Request berhasil disimpan'];

                // Panggil NotifController untuk mengirim email
                $notifData = [
                    'nama_peminta' => $_POST['name'],
                    'email_peminta' => $_POST['email_peminta'],
                    'division' => $_POST['division'],
                    'facility' => $_POST['facility'],
                    'request_date' => $_POST['request_date'],
                    'request_time' => $_POST['request_time'],
                    'items' => $data['items']
                ];

                $notifController = new NotifController();
                $notifController->sendNotification($notifData);
            } else {
                $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal menyimpan request'];
            }

            header("Location: ../views/user/pesanan.php");
            exit();
        }
    }

    // Fungsi untuk mengambil semua data berdasarkan user_id
    public function getRequestsByUser()
    {
        if (isset($_SESSION['user'])) {
            $user_id = $_SESSION['user']['id'];
            return $this->requestModel->getRequestsByUser($user_id);
        } else {
            header("Location: ../index.php");
            exit();
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new RequestController();
    $controller->createRequest();
}
