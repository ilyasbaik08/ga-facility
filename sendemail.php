<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require '/PHPMailer/src/SMTP.php';

if (
    isset($_POST['date']) &&
    isset($_POST['name']) &&
    isset($_POST['div']) &&
    isset($_POST['phone']) &&
    isset($_POST['request date']) &&
    isset($_POST['request time']) &&
    isset($_POST['facility']) &&
    isset($_POST['name[]']) &&
    isset($_POST['jumlah[]']) &&
    isset($_POST['satuan[]']) &&
    isset($_POST['keterangan[]'])
) {

    $date = $_POST['date'];
    $name = $_POST['name'];
    $dept = $_POST['div'];
    $phone = $_POST['phone'];
    $requestDate = $_POST['date'];
    $requestTime = $_POST['time'];
    $facility = $_POST['facility'];
    $NameItem = $_POST['name[]'];
    $jumlah = $_POST['jumlah[]'];
    $satuan = $_POST['satuan[]'];
    $keterangan = $_POST['keterangan[]'];


    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        //SMTP username
        $mail->Username   = 'ilyasanakbaik27@gmail.com';
        $mail->Password   = '$2y$10$7N8bWF1WKYTKGghIQFyUlOZ0l2BanaGEtvswsLzZfbsff4.LdghIS';                               //SMTP password
        $mail->SMTPSecure = 'ssl';            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom($email, $name);
        $mail->addAddress('ilyasanakbaik27@gmail.com');               //Name is optional

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'FORM GA-FACILITY';
        $mail->Body    = "
                <h3> FORM GA-FACILITY</h3>
                <p><strong>Date</strong>: $date</p>
                <p><strong>Name</strong>: $name</p>
                <p><strong>Dept/Div</strong>: $dept</p>
                <p><strong>Ext/Phone</strong>: $phone</p>
                <p><strong>Request Date</strong>: $requestDate</p>
                <p><strong>Request Time</strong>: $requestTime</p>
                <p><strong>Facility</strong>: $facility</p>
                <p><strong>Name Item</strong>: $NameItem</p>
                <p><strong>Jumlah</strong>: $jumlah</p>
                <p><strong>Satuan</strong>: $satuan</p>
                <p><strong>Keterangan</strong>: $keterangan</p>
        ";


        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
