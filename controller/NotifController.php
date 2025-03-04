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
            $mail->Username = 'ilyasanakbaik27@gmail.com'; // Ganti dengan email kamu
            $mail->Password = 'zxjbexufsjbjuzbl'; // Ganti dengan password aplikasi email kamu
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('ilyasanakbaik27@gmail.com', 'PT Dharma Electrindo Manufacturing');
            $mail->addAddress($emailAtasan1, 'Atasan1'); // Email tujuan dan peminta sama
            $mail->addAddress($emailAtasan2, 'Atasan2'); // Email tujuan dan peminta sama
            $mail->addReplyTo($emailPeminta, $data['nama_peminta']);

            $mail->isHTML(true);
            $mail->Subject = 'New Request Submitted';

            $template = file_get_contents(__DIR__ . '/../views/pagenotification.php');

            // Replace data di dalam template
            $template = str_replace('{nama_peminta}', $data['nama_peminta'], $template);
            $template = str_replace('{facility}', $data['facility'], $template);
            $template = str_replace('{request_date}', $data['request_date'], $template);
            $template = str_replace('{request_time}', $data['request_time'], $template);

            $itemsList = "";
            foreach ($data['items'] as $item) {
                $itemsList .= "<li>{$item['nama_item']} - {$item['jumlah']} {$item['satuan']}</li>";
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
}
