<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../../index.php");
    exit();
}

$user = $_SESSION['user'];

require_once __DIR__ . '/../../controller/RequestController.php';

$requestController = new RequestController();
$request = $requestController->getRequestsByUser($user['id']);

require_once __DIR__ . '/../../controller/MasterBarangController.php';

$barangController = new MasterBarangController();
$getBarang = $barangController->getBarang();
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
    <title>PT Dharma Electrindo Manufacturing</title>
</head>

<body class="bg-gray-100 text-white font-poppins">
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white py-4 px-6 flex justify-between items-center border-b border-gray-700">
        <a href="../../controller/LogoutController.php" class="flex items-center gap-2">
            <img class="size-16" src="../../assets/img/logo-dharma.png" alt="logo">
            <span class="text">PT. Dharma Electrindo Manufacturing</span>
        </a>
        <div class="flex space-x-4 items-center">
            <span class="text-black hidden md:block"><?= htmlspecialchars($user['name']); ?></span>
            <a href="#" id="modal-button" class="text-black hover:text-green-500"><i data-feather="user"></i></a>
            <a href="#" id="menu-button" class="text-black md:hidden hover:text-green-500"><i data-feather="menu"></i></a>
            <a href="#" id="close-button" class="text-black hidden md:hidden hover:text-green-500"><i data-feather="x"></i></a>
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

    <section class="relative flex items-center justify-center top-32 pt-10 z-10">
        <div class="flex items-center justify-center min-h-screen">
            <div class="w-full max-w-3xl p-6 bg-white rounded-lg shadow-md">
                <h1 class="text-3xl font-bold text-center text-green-400 mb-6">REQUEST GA-FACILITY</h1>

                <form action="../../controller/RequestController.php" method="POST" class="mt-6 space-y-4">
                    <input type="text" name="_honey" style="display: none;">
                    <input type="hidden" name="_captcha" value="false">
                    <input type="hidden" name="user_id" value="<?= $_SESSION['user']['id'] ?>">
                    <input type="hidden" name="email_peminta" value="<?= $user['email'] ?>">
                    <!-- <input type="hidden" name="id_barang[]" value="<?= $getBarang['id_barang']; ?>"> -->
                    <div>
                        <label for="date" class="block text-sm font-medium text-black">Date:</label>
                        <input type="date" name="date" id="date" required class="w-full px-4 py-2 mt-1 text-sm text-black bg-green-50 border border-green-300 rounded-md focus:ring-green-500 focus:border-green-500">
                    </div>
                    <div>
                        <label for="name" class="block text-sm font-medium text-black">Name:</label>
                        <input type="text" name="name" id="name" value="<?= htmlspecialchars($user['name']) ?>" required class="w-full px-4 py-2 mt-1 text-sm text-black bg-green-50 border border-green-300 rounded-md focus:ring-green-500 focus:border-green-500">
                    </div>
                    <div>
                        <label for="division" class="block text-sm font-medium text-black">Dept:</label>
                        <input disabled type="text" name="division" id="division" value="<?= htmlspecialchars($user['division']) ?>" required class="w-full px-4 py-2 mt-1 text-sm text-black bg-green-50 border border-green-300 rounded-md focus:ring-green-500 focus:border-green-500">
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-black">Ext/Phone:</label>
                        <input type="text" name="ext_phone" id="ext_phone" required class="w-full px-4 py-2 mt-1 text-sm text-black bg-green-50 border border-green-300 rounded-md focus:ring-green-500 focus:border-green-500">
                    </div>
                    <div>
                        <label for="date" class="block text-sm font-medium text-black">Request Date:</label>
                        <input type="date" name="request_date" id="request_date" required class="w-full px-4 py-2 mt-1 text-sm text-black bg-green-50 border border-green-300 rounded-md focus:ring-green-500 focus:border-green-500">
                    </div>
                    <div>
                        <label for="time" class="block text-sm font-medium text-black">Request Time:</label>
                        <input type="time" name="request_time" id="request_time" required class="w-full px-4 py-2 mt-1 text-sm text-black bg-green-50 border border-green-300 rounded-md focus:ring-green-500 focus:border-green-500">
                    </div>
                    <div>
                        <label for="facility" class="block text-sm font-medium text-black">Facility:</label>
                        <select name="facility" class="w-full px-4 py-2 mt-1 text-sm text-black bg-green-50 border border-green-300 rounded-md focus:ring-green-500 focus:border-green-500">
                            <option value="atk">ATK</option>
                            <option value="Dokumen">Dokumen</option>
                            <option value="perlengkapan karyawan">Perlengkapan Karyawan</option>
                            <option value="konsumsi">Konsumsi</option>
                            <option value="akomodasi">Akomodasi</option>
                            <option value="furniture">Furniture</option>
                            <option value="building">Building</option>
                            <option value="alat kebersihan">Alat Kebersihan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-black mb-2">Detail Items:</label>
                        <table id="item-table" class="w-full text-sm text-black border border-green-300">
                            <thead>
                                <tr class="bg-green-50">
                                    <th class="border border-green-300 px-2 py-1">Nama Item</th>
                                    <th class="border border-green-300 px-2 py-1">Jumlah</th>
                                    <th class="border border-green-300 px-2 py-1">Satuan</th>
                                    <th class="border border-green-300 px-2 py-1">Keterangan</th>
                                    <th class="border border-green-300 px-2 py-1">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="border border-green-300 px-2 py-1">
                                        <select name="id_barang[]" required class="w-full px-2 py-1 bg-green-50 border border-green-300 rounded-md">
                                            <option value="">Pilih Barang</option>
                                            <?php foreach ($getBarang as $barang) : ?>
                                                <option value="<?= htmlspecialchars($barang['description_item']) ?>">
                                                    <?= htmlspecialchars($barang['description_item']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td class="border border-green-300 px-2 py-1">
                                        <input type="number" name="jumlah[]" required class="w-full px-2 py-1 bg-green-50 border border-green-300 rounded-md">
                                    </td>
                                    <td class="border border-green-300 px-2 py-1">
                                        <select name="satuan[]" required class="w-full px-2 py-1 bg-green-50 border border-green-300 rounded-md">
                                            <option value="">Satuan</option>
                                            <?php foreach ($getBarang as $barang) : ?>
                                                <option value="<?= htmlspecialchars($barang['satuan']) ?>">
                                                    <?= htmlspecialchars($barang['satuan']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td class="border border-green-300 px-2 py-1">
                                        <input type="text" name="keterangan[]" required class="w-full px-2 py-1 bg-green-50 border border-green-300 rounded-md">
                                    </td>
                                    <td class="border border-green-300 px-2 py-1 text-center">
                                        <button type="button" class="remove-row text-red-500">Hapus</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="button" id="add-row" class="mt-2 px-4 py-2 bg-green-500 text-white rounded-md">Tambah Baris</button>
                    </div>

                    <button type="submit" class="w-full px-6 py-3 text-lg font-semibold bg-green-500 text-white rounded-md mt-6">Submit</button>

                </form>
            </div>
        </div>
    </section>

    <script>
        feather.replace();

        const toggleButton = document.getElementById('menu-button');
        const sideNav = document.getElementById('sidenav');
        const closeMenu = document.getElementById("close-button");
        const table = document.getElementById('item-table').getElementsByTagName('tbody')[0];
        const addRowButton = document.getElementById('add-row');

        const dropdownBarang = `
        <select name="nama_items[]" required class="w-full px-2 py-1 bg-green-50 border border-green-300 rounded-md">
            <option value="">Pilih Barang</option>
            <?php foreach ($getBarang as $barang) : ?>
                <option value="<?= htmlspecialchars($barang['description_item']) ?>">
                    <?= htmlspecialchars($barang['description_item']) ?>
                </option>
            <?php endforeach; ?>
        </select>`;

        addRowButton.addEventListener('click', () => {
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
            <td class="border border-green-300 px-2 py-1">
                ${dropdownBarang} 
            </td>
            <td class="border border-green-300 px-2 py-1">
                <input type="number" name="jumlah[]" required class="w-full px-2 py-1 bg-green-50 border border-green-300 rounded-md">
            </td>
            <td class="border border-green-300 px-2 py-1">
                <input type="text" name="satuan[]" required class="w-full px-2 py-1 bg-green-50 border border-green-300 rounded-md">
            </td>
            <td class="border border-green-300 px-2 py-1">
                <input type="text" name="keterangan[]" required class="w-full px-2 py-1 bg-green-50 border border-green-300 rounded-md">
            </td>
            <td class="border border-green-300 px-2 py-1 text-center">
                <button type="button" class="remove-row text-red-500">Hapus</button>
            </td>
        `;
            table.appendChild(newRow);
        });

        table.addEventListener('click', (e) => {
            if (e.target && e.target.classList.contains('remove-row')) {
                const row = e.target.closest('tr');
                table.removeChild(row);
            }
        });

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

        const orderButton = document.getElementById('order-button');
        const modalOrder = document.getElementById('order-modal');
        const orderClose = document.getElementById('order-close');

        orderButton.addEventListener('click', function() {
            modalOrder.classList.remove('opacity-0', 'pointer-events-none');
            modalOrder.classList.add('opacity-100', 'pointer-events-auto');
        });

        orderClose.addEventListener('click', function() {
            modalOrder.classList.remove('opacity-100', 'pointer-events-auto');
            modalOrder.classList.add('opacity-0', 'pointer-events-none');
        });
    </script>
</body>

</html>