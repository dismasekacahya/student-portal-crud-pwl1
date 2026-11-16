-- Pengembangan skema relasional pertemuan 6, dengan data fiktif praktikum.
-- Import ke database baru. Tidak menghapus atau menimpa tabel yang ada.
CREATE DATABASE IF NOT EXISTS student_portal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE student_portal;
CREATE TABLE program_studi (
    id_prodi INT AUTO_INCREMENT PRIMARY KEY,
    nama_prodi VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;
CREATE TABLE mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nim VARCHAR(20) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    id_prodi INT NOT NULL,
    nilai_tugas DECIMAL(5,2) NOT NULL DEFAULT 0 CHECK (nilai_tugas BETWEEN 0 AND 100),
    nilai_uts DECIMAL(5,2) NOT NULL DEFAULT 0 CHECK (nilai_uts BETWEEN 0 AND 100),
    nilai_uas DECIMAL(5,2) NOT NULL DEFAULT 0 CHECK (nilai_uas BETWEEN 0 AND 100),
    kehadiran INT NOT NULL DEFAULT 0 CHECK (kehadiran BETWEEN 0 AND 100),
    FOREIGN KEY (id_prodi) REFERENCES program_studi(id_prodi) ON DELETE RESTRICT
) ENGINE=InnoDB;
INSERT INTO program_studi (nama_prodi) VALUES ('Sistem Informasi'), ('Informatika'), ('Teknik Elektro');
-- Seluruh password demo: 123456 (hash bcrypt, bukan password akun asli).
INSERT INTO mahasiswa (nim,nama,email,password,id_prodi,nilai_tugas,nilai_uts,nilai_uas,kehadiran) VALUES
('412026001','Andi Wijaya','andi@ukrida.ac.id','$2y$12$GEpCy3m1oukv945Ghn0m6.zsQtp..IQT/PWSPE.t1pcA5Tp4liGe2',1,80,78,90,85),
('412026002','Budi Santoso','budi@ukrida.ac.id','$2y$12$GEpCy3m1oukv945Ghn0m6.zsQtp..IQT/PWSPE.t1pcA5Tp4liGe2',1,75,70,82,80),
('412026003','Citra Lestari','citra@ukrida.ac.id','$2y$12$GEpCy3m1oukv945Ghn0m6.zsQtp..IQT/PWSPE.t1pcA5Tp4liGe2',2,90,88,92,95),
('412026004','Dimas Saputra','dimas@ukrida.ac.id','$2y$12$GEpCy3m1oukv945Ghn0m6.zsQtp..IQT/PWSPE.t1pcA5Tp4liGe2',2,65,68,70,75);
