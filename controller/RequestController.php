<?php
// session_start();

require_once __DIR__ . '/../models/RequestModel.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';


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
                'status_barang' => "waiting confirmation",
                'status' => "Not Approve",
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
                    'keterangan' => $_POST['keterangan'][$key]
                ];
            }

            if (empty($data['items'])) {
                $_SESSION['message'] = ['type' => 'error', 'text' => 'Data items kosong atau tidak valid'];
                header("Location: ../views/user/pesanan.php");
                exit();
            }

            if ($this->requestModel->createRequest($data)) {
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Request berhasil disimpan'];

                $this->sendEmailNotification($data);
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

    // Fungsi untuk mengirim email
    private function sendEmailNotification($data)
    {
        $to = 'ilyasanaknaik27@gmail.com';  // Ganti dengan email tujuan
        $subject = 'Notifikasi Request GA-Facility';

        $message = "<html><head><title>Notifikasi Request GA-Facility</title></head><body>";
        $message .= "<p>Berikut adalah detail request GA-Facility yang baru:</p><table>";
        foreach ($data as $key => $value) {
            if ($key !== 'items') {
                $message .= "<tr><td>" . ucfirst(str_replace('_', ' ', $key)) . ":</td><td>$value</td></tr>";
            }
        }
        $message .= "</table><br><p>Detail Items:</p><ul>";
        foreach ($data['items'] as $item) {
            $message .= "<li>" . implode(' | ', $item) . "</li>";
        }
        $message .= "</ul></body></html>";

        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.example.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'your_email@example.com';
            $mail->Password = 'your_password';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('your_email@example.com', 'Request GA-Facility');
            $mail->addAddress($to);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $message;

            $mail->send();
        } catch (Exception $e) {
            echo "Terjadi kesalahan saat mengirim email: {$mail->ErrorInfo}";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new RequestController();
    $controller->createRequest();
}
