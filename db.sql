CREATE TABLE siswa (
                       id_siswa INT AUTO_INCREMENT PRIMARY KEY,
                       nis VARCHAR(20) NOT NULL UNIQUE,
                       nama VARCHAR(100) NOT NULL,
                       alamat TEXT,
                       tempat_lahir VARCHAR(100),
                       tanggal_lahir DATE,
                       jenis_kelamin ENUM('L','P'),
                       no_hp VARCHAR(20),
                       email VARCHAR(100),
                       foto VARCHAR(255)
);

CREATE TABLE mapel (
                       id_mapel INT AUTO_INCREMENT PRIMARY KEY,
                       nama_mapel VARCHAR(100) NOT NULL
);

CREATE TABLE nilai (
                       id_nilai INT AUTO_INCREMENT PRIMARY KEY,
                       id_siswa INT NOT NULL,
                       id_mapel INT NOT NULL,
                       semester INT NOT NULL CHECK (semester BETWEEN 1 AND 6),
                       nilai_angka DECIMAL(5,2) NOT NULL,
                       FOREIGN KEY (id_siswa) REFERENCES siswa(id_siswa) ON DELETE CASCADE,
                       FOREIGN KEY (id_mapel) REFERENCES mapel(id_mapel) ON DELETE CASCADE
);

CREATE TABLE users (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       username VARCHAR(50) NOT NULL UNIQUE,
                       password VARCHAR(255) NOT NULL,
                       role ENUM('admin', 'siswa') NOT NULL,
                       id_siswa INT DEFAULT NULL,
                       FOREIGN KEY (id_siswa) REFERENCES siswa(id_siswa)
);