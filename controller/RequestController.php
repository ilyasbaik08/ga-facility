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

    // // untuk mengirim balik notif ke user
    // public function updateRequestStatus($request_id, $status)
    // {
    //     $request = $this->requestModel->getRequestById($request_id);

    //     if (!$request) {
    //         $_SESSION['message'] = ['type' => 'error', 'text' => 'Request tidak ditemukan'];
    //         header("Location: ../views/admin/request.php");
    //         exit();
    //     }

    //     // Update status request di database
    //     if ($this->requestModel->updateStatus($request_id, $status)) {
    //         $_SESSION['message'] = ['type' => 'success', 'text' => "Request telah diubah menjadi $status"];

    //         // Kirim notifikasi ke peminta jika status Approved atau Closed
    //         if (in_array($status, ['Approved', 'Closed'])) {
    //             $notifController = new NotifController();
    //             $notifData = [
    //                 'nama_peminta' => $request['nama_peminta'],
    //                 'email_peminta' => $request['email_peminta'],
    //                 'items' => $this->requestModel->getRequestItems($request_id) // Ambil item dari request
    //             ];

    //             if ($status === 'Approved') {
    //                 $notifController->sendApprovalNotification($notifData);
    //             } elseif ($status === 'Closed') {
    //                 $notifController->sendCloseNotification($notifData);
    //             }
    //         }
    //     } else {
    //         $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal mengupdate request'];
    //     }

    //     header("Location: ../views/admin/requests.php"); // Redirect ke halaman admin
    //     exit();
    // }

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
