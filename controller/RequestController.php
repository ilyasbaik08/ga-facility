<?php

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

    // Fungsi untuk menyimpan data
    public function createRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_POST['user_id'];
            $date = $_POST['date'];
            $nama_peminta = $_POST['name'];
            $ext_phone = $_POST['ext_phone'];
            $request_date = $_POST['request_date'];
            $request_time = $_POST['request_time'];
            $facility = $_POST['facility'];
            $nama_items = $_POST['nama_items']; // Array
            $jumlahs = $_POST['jumlah']; // Array
            $satuans = $_POST['satuan']; // Array
            $keterangans = $_POST['keterangan']; // Array
            $status_barang = "on progress";
            $status = "Not Approve";

            if ($this->requestModel->createRequest($user_id, $date, $nama_peminta, $ext_phone, $request_date, $request_time, $facility, $nama_items, $jumlahs, $satuans, $keterangans, $status_barang, $status)) {
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Request berhasil disimpan'];

                // Kirim email setelah berhasil menyimpan data
                $this->sendEmailNotification($user_id, $date, $nama_peminta, $ext_phone, $request_date, $request_time, $facility, $nama_items, $jumlahs, $satuans, $keterangans, $status_barang, $status);
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
    private function sendEmailNotification($user_id, $date, $nama_peminta, $ext_phone, $request_date, $request_time, $facility, $nama_item, $jumlah, $satuan, $keterangan, $status_barang, $status)
    {
        // Pesan email
        $to = 'ilyasanaknaik27@gmail.com';  // Ganti dengan email tujuan
        $subject = 'Notifikasi Request GA-Facility';
        $message = "
        <html>
        <head>
            <title>Notifikasi Request GA-Facility</title>
        </head>
        <body>
            <p>Berikut adalah detail request GA-Facility yang baru:</p>
            <table>
                <tr><td>Tanggal Request:</td><td>$date</td></tr>
                <tr><td>Nama Peminta:</td><td>$nama_peminta</td></tr>
                <tr><td>Ext Phone:</td><td>$ext_phone</td></tr>
                <tr><td>Tanggal Request:</td><td>$request_date</td></tr>
                <tr><td>Waktu Request:</td><td>$request_time</td></tr>
                <tr><td>Facility:</td><td>$facility</td></tr>
                <tr><td>Nama Item:</td><td>$nama_item</td></tr>
                <tr><td>Jumlah:</td><td>$jumlah</td></tr>
                <tr><td>Satuan:</td><td>$satuan</td></tr>
                <tr><td>Keterangan:</td><td>$keterangan</td></tr>
                <tr><td>Status Barang:</td><td>$status_barang</td></tr>
                <tr><td>Status:</td><td>$status</td></tr>
            </table>
        </body>
        </html>
        ";

        // Menggunakan PHPMailer untuk mengirim email
        try {
            // Membuat objek PHPMailer
            $mail = new PHPMailer(true);

            // Set konfigurasi server SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.example.com'; // Ganti dengan SMTP server yang digunakan
            $mail->SMTPAuth = true;
            $mail->Username = 'your_email@example.com'; // Ganti dengan email pengirim
            $mail->Password = 'your_password'; // Ganti dengan password email pengirim
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Alamat pengirim dan penerima
            $mail->setFrom('your_email@example.com', 'Request GA-Facility');
            $mail->addAddress($to);  // Ganti dengan email penerima

            // Isi email
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $message;

            // Kirim email
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
