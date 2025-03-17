<?php
session_start();
require_once '../models/UserModel.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['name'];
    $email = $_POST['email'];
    $division = $_POST['division'];
    $level = 'user';
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password != $confirm_password) {
        echo "Konfirmasi passwor tidak sama";
        exit;
    }

    $userModel = new UserModel();

    $user = $userModel->register($user, $email, $division, $level, $password);

    if ($user) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'division' => $user['division']
        ];

        header("Location: ../index.php");
        exit;
    }
}
