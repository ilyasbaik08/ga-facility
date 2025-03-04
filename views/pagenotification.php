<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Request Submitted</title>
</head>

<body style="background-color: #f3f4f6; padding: 20px; font-family: Arial, sans-serif;">
    <div style="max-width: 600px; margin: auto; background: #ffffff; padding: 25px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">

        <!-- Judul -->
        <h2 style="color: #1f2937; text-align: center; font-size: 22px; margin-bottom: 10px;">APPROVAL</h2>
        <p style="color: #4b5563; font-size: 14px; margin-top: 10px">Dear Bapak / Ibu Pimpinan,</p>
        <p style="color: #4b5563; font-size: 14px; margin-top: 16px">Mohon untuk dapat me-review dan memberikan approval terhadap Request GA-FACILITY berikut :</p>


        <!-- Detail Request -->
        <p style="color: #4b5563; font-size: 14px; margin-top: 20px;"><strong>Request Dari:</strong> {nama_peminta}</p>
        <p style="color: #4b5563; font-size: 14px;"><strong>Facility:</strong> {facility}</p>
        <p style="color: #4b5563; font-size: 14px;"><strong>Tanggal Request:</strong> {request_date} {request_time}</p>

        <h4 style="color: #1f2937; font-size: 16px; margin-top: 20px;">Detail Barang:</h4>
        <ul style="color: #4b5563; padding-left: 20px; font-size: 14px;">
            {items_list}
        </ul>

        <p><strong>Silakan klik <a href='http://localhost:5000/views/admin/pesanan.php'>link sini</a> untuk melakukan approval.</strong></p>


    </div>
</body>

</html>