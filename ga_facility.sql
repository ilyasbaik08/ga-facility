CREATE DATABASE ga_facility;

USE ga_facility;

CREATE TABLE users(
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    division VARCHAR(255) NOT NULL,
    level ENUM('admin', 'user'),
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


CREATE TABLE peminta(
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    date DATE NOT NULL,
    nama_peminta VARCHAR(255) NOT NULL,
    ext_phone VARCHAR(255) NOT NULL,
    request_date DATE  NOT NULL,
    request_time TIME NOT NULL,
    facility ENUM('ATK','Dokumen','Perlengkapan Karyawan','Konsumsi','Akomodasi','Furniture','Building','Alat Kebersihan') NOT NULL,
    nama_item VARCHAR(255),
    jumlah INT,
    satuan VARCHAR(255)NOT NULL,
    keterangan VARCHAR(255),
    status_barang ENUM('on progress','closed'),
    status ENUM('Approve','Not Approve'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
