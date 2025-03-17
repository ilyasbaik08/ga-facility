<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../../index.php");
    exit();
}

$user = $_SESSION['user'];

require_once __DIR__ . '/../../controller/PesananController.php';

$pesananController = new PesananController();
$pesanan = $pesananController->getPesananByUser($user['id']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;300;400;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <title>Pesanan</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100">
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white py-4 px-6 flex justify-between items-center border-b border-gray-700">
        <a href="../../controller/LogoutController.php" class="flex items-center gap-2">
            <img class="size-16" src="../../assets/img/logo-dharma.png" alt="logo">
            <span class="text">PT. Dharma Electrindo Manufacturing</span>
        </a>
        <div class="flex space-x-4 items-center">
            <span class="text-black hidden md:block"><?= htmlspecialchars($user['name']); ?></span>
            <a href="#" id="modal-button" class="text-black hover:text-green-500"><i data-feather="user"></i></a>
            <a href="#" id="menu-button" class="md:hidden hover:text-green-500"><i data-feather="menu"></i></a>
            <a href="#" id="close-button" class="hidden md:hidden hover:text-green-500"><i data-feather="x"></i></a>
        </div>
    </nav>

    <div id="menu-modal" class="bg-black bg-opacity-50 absolute top-14 right-4 z-50 hidden">
        <div class="bg-white/60 backdrop-blur-lg py-4 rounded-lg shadow-lg w-48">
            <ul>
                <a href="#" class="text-gray-700 hover:bg-gray-100 py-2 px-4 block w-full">Profile</a>
                <a href="#" class="text-gray-700 hover:bg-gray-100 py-2 px-4 block w-full">Settings</a>
                <a href="../../controller/LogoutController.php" class="text-gray-700 hover:bg-gray-100 py-2 px-4 block w-full">Logout</a>
            </ul>
        </div>
    </div>

    <?php include __DIR__ . '/../partials/aside.php'; ?>

    <aside class="fixed top-0 h-full w-64 bg-white text-black shadow-lg z-50 md:hidden sidebar" id="sidenav">
        <nav class="flex flex-col h-full py-8">
            <a href="request.php" class="hover:bg-green-100 py-2 px-8">Request</a>
            <a href="pesanan.php" class="hover:bg-green-100 py-2 px-8">Pesanan</a>
        </nav>
    </aside>

    <div class="container mx-auto mt-32 px-4 md:ml-72">
        <h1 class="text-3xl font-bold text-green-400 mb-6">Daftar Pesanan</h1>

        <div class="overflow-x-auto bg-white border rounded-lg shadow-md">
            <table class="w-full">
                <thead>
                    <tr class="bg-green-500 text-white text-left">
                        <th class="px-6 py-3 border">Nama Peminta</th>
                        <th class="px-6 py-3 border">Nama Item</th>
                        <th class="px-6 py-3 border">Jumlah</th>
                        <th class="px-6 py-3 border">Satuan</th>
                        <th class="px-6 py-3 border">Keterangan</th>
                        <th class="px-6 py-3 border">Status Barang</th>
                        <th class="px-6 py-3 border">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pesanan)): ?>
                        <?php foreach ($pesanan as $pesanan): ?>
                            <tr class="border-b hover:bg-gray-100">
                                <td class="px-6 py-4"><?= htmlspecialchars($pesanan['nama_peminta']) ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($pesanan['description_item']) ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($pesanan['jumlah']) ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($pesanan['satuan']) ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($pesanan['keterangan']) ?></td>
                                <td class="px-6 py-4 <?= $pesanan['status_barang'] === 'waiting confirmation' ? 'text-red-500' : ($pesanan['status_barang'] === 'confirmed' ? 'text-blue-500' : ($pesanan['status_barang'] === 'on process' ? 'text-purple-500' : 'text-green-500')); ?>">
                                    <?= $pesanan['status_barang']; ?>
                                </td>
                                <td class="px-6 py-4 <?= $pesanan['status'] === 'Not Approve' ? 'text-red-500' : ($pesanan['status'] === 'Approve' ? 'text-green-500' : ''); ?>">
                                    <?= $pesanan['status']; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center px-6 py-4 text-gray-500">Belum ada pesanan</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        feather.replace();

        const toggleButton = document.getElementById('menu-button');
        const sideNav = document.getElementById('sidenav');
        const closeMenu = document.getElementById("close-button");

        toggleButton.addEventListener('click', () => {
            sideNav.classList.toggle('visible');
            toggleButton.classList.toggle('hidden');
            closeMenu.classList.toggle('hidden');
        });

        closeMenu.addEventListener('click', () => {
            sideNav.classList.toggle('visible');
            toggleButton.classList.toggle('hidden');
            closeMenu.classList.toggle('hidden');
        });

        const modalButton = document.getElementById('modal-button');
        const menuModal = document.getElementById('menu-modal');

        modalButton.addEventListener('click', () => {
            menuModal.classList.toggle('hidden');
        });
    </script>
</body>

</html>