<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class NotifController
{
    public function sendNotification($data)
    {
        if (empty($data['nama_peminta']) || empty($data['items'])) {
            return false;
        }

        $emailPeminta = $data['email_peminta'];
        $emailAtasan1 = "ilyaspriyono2710@gmail.com";
        $emailAtasan2 = "ilyaspriyono0810@gmail.com";

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'ilyasanakbaik27@gmail.com';
            $mail->Password = 'zxjbexufsjbjuzbl';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('ilyasanakbaik27@gmail.com', 'PT Dharma Electrindo Manufacturing');
            $mail->addAddress($emailAtasan1, 'Atasan1');
            $mail->addAddress($emailAtasan2, 'Atasan2');
            $mail->addReplyTo($emailPeminta, $data['nama_peminta']);

            $mail->isHTML(true);
            $mail->Subject = 'New Request Submitted';

            $template = file_get_contents(__DIR__ . '/../views/pagenotification.php');

            $template = str_replace('{nama_peminta}', $data['nama_peminta'], $template);
            $template = str_replace('{facility}', $data['facility'], $template);
            $template = str_replace('{request_date}', $data['request_date'], $template);
            $template = str_replace('{request_time}', $data['request_time'], $template);

            $itemsList = "";
            foreach ($data['items'] as $item) {
                $itemsList .= "<li>{$item['id_barang']} - {$item['jumlah']} {$item['satuan']}</li>";
            }

            $body = str_replace(
                ['{nama_peminta}', '{facility}', '{request_date}', '{request_time}', '{items_list}'],
                [$data['nama_peminta'], $data['facility'], $data['request_date'], $data['request_time'], $itemsList],
                $template
            );

            $mail->Body .= $body;

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Email tidak bisa dikirim: " . $mail->ErrorInfo);
            return false;
        }
    }

    public function notif_closed($email_peminta)
    {
        $mail = new PHPMailer(true);
        $email_tujuan = $email_peminta;

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'ilyasanakbaik27@gmail.com';
            $mail->Password = 'zxjbexufsjbjuzbl';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('ilyasanakbaik27@gmail.com', 'PT Dharma Electrindo Manufacturing');
            $mail->addAddress($email_tujuan);

            $mail->Subject = "PT Dharma Electrindo Manufacturing";
            $mail->isHTML(true);
            $mail->Body    = "
                <div style='font-family: Arial, sans-serif; padding: 20px; border: 1px solid #ddd; background-color: #f9f9f9;'>
                    <h2 style='color: #333;'>Status Pesanan</h2>
                    <p>Status pesanan Anda <strong style='color: red;'>CLOSED</strong></p>
                </div>
            ";

            return $mail->send();
        } catch (Exception $e) {
            error_log("Gagal mengirim email: {$mail->ErrorInfo}");
            return false;
        }
    }

    public function notif_approve($email_peminta)
    {
        $mail = new PHPMailer(true);
        $email_tujuan = $email_peminta;

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'ilyasanakbaik27@gmail.com';
            $mail->Password = 'zxjbexufsjbjuzbl';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('ilyasanakbaik27@gmail.com', 'PT Dharma Electrindo Manufacturing');
            $mail->addAddress($email_tujuan);

            $mail->Subject = "PT Dharma Electrindo Manufacturing";
            $mail->isHTML(true);
            $mail->Body    = "
                <div style='font-family: Arial, sans-serif; padding: 20px; border: 1px solid #ddd; background-color: #f9f9f9;'>
                    <h2 style='color: #333;'>Status Pesanan</h2>
                    <p>Pesanan Anda telah di <strong style='color: green;'>SETUJUI</strong></p>
            ";

            return $mail->send();
        } catch (Exception $e) {
            error_log("Gagal mengirim email: {$mail->ErrorInfo}");
            return false;
        }
    }
}
