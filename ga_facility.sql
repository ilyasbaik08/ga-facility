CREATE OR REPLACE DATABASE ga_facility;

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

CREATE OR REPLACE TABLE peminta (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    date DATE NOT NULL,
    nama_peminta VARCHAR(255) NOT NULL,
    ext_phone VARCHAR(255) NOT NULL,
    request_date DATE NOT NULL,
    request_time TIME NOT NULL,
    facility ENUM('ATK', 'Dokumen', 'Perlengkapan Karyawan', 'Konsumsi', 'Akomodasi', 'Furniture', 'Building', 'Alat Kebersihan') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE items (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    peminta_id INT(11) NOT NULL,
    id_barang INT(11) NOT NULL,
    jumlah INT NOT NULL,
    keterangan VARCHAR(255),
    status_barang ENUM('waiting confirmation', 'confirmed', 'on process', 'closed') DEFAULT 'waiting confirmation',
    status ENUM('Not Approve', 'Approve') DEFAULT 'Not Approve',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (peminta_id) REFERENCES peminta(id) ON DELETE CASCADE
);

CREATE TABLE master_barang (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    kode_item VARCHAR(255) NOT NULL,
    description_item VARCHAR(255) NOT NULL,
    satuan VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


INSERT INTO master_barang(kode_item, description_item, satuan) VALUES
('001', 'printer lenovo', 'PCS'),
('002', 'printer hp', 'PCS');


