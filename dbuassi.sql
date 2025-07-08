-- Create Database
CREATE DATABASE IF NOT EXISTS integrasi_sistem_uas;
USE integrasi_sistem_uas;

-- Table: pegawai (Employee)
CREATE TABLE IF NOT EXISTS pegawai (
    id_pegawai VARCHAR(20) PRIMARY KEY,
    nama VARCHAR(100),
    jabatan VARCHAR(50),
    status_kepegawaian VARCHAR(30),
    gaji_pokok DECIMAL(12,2)
);

-- Insert sample employees
INSERT INTO pegawai (id_pegawai, nama, jabatan, status_kepegawaian, gaji_pokok) VALUES
('EMP001', 'Budi Santoso', 'Staff IT', 'tetap', 8000000),
('EMP002', 'Siti Nurfadilah', 'HRD', 'tetap', 9000000),
('EMP003', 'Andika Pratama', 'Marketing', 'kontrak', 6000000);

-- Table: presensi (Attendance)
CREATE TABLE IF NOT EXISTS presensi (
    id_presensi INT AUTO_INCREMENT PRIMARY KEY,
    id_pegawai VARCHAR(20),
    waktu_masuk DATETIME,
    waktu_keluar DATETIME,
    lokasi VARCHAR(50),
    status_kehadiran ENUM('hadir', 'izin', 'cuti', 'sakit'),
    FOREIGN KEY (id_pegawai) REFERENCES pegawai(id_pegawai)
);

-- Insert sample attendance records
INSERT INTO presensi (id_pegawai, waktu_masuk, waktu_keluar, lokasi, status_kehadiran) VALUES
('EMP001', '2025-04-05 07:55:00', '2025-04-05 17:00:00', 'kantor', 'hadir'),
('EMP002', '2025-04-05 08:05:00', '2025-04-05 17:05:00', 'kantor', 'hadir'),
('EMP003', '2025-04-05 08:00:00', '2025-04-05 16:30:00', 'GPS: -6.200000,106.816667', 'hadir'),
('EMP001', '2025-04-04 08:00:00', '2025-04-04 17:00:00', 'kantor', 'hadir'),
('EMP002', '2025-04-04 08:10:00', NULL, 'GPS: -6.208000,106.816000', 'izin');

-- Table: tunjangan (Allowance)
CREATE TABLE IF NOT EXISTS tunjangan (
    id_tunjangan INT AUTO_INCREMENT PRIMARY KEY,
    jenis VARCHAR(50),
    jumlah DECIMAL(10,2),
    ketentuan TEXT
);

-- Insert sample allowances
INSERT INTO tunjangan (jenis, jumlah, ketentuan) VALUES
('Transport', 500000, 'Rp50.000 per bulan jika hadir >= 20 hari'),
('Makan', 300000, 'Rp10.000 per hari hadir'),
('Kinerja', 1000000, 'Berdasarkan evaluasi kinerja bulanan');

-- Table: pegawai_tunjangan (Assigned Allowances)
CREATE TABLE IF NOT EXISTS pegawai_tunjangan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_pegawai VARCHAR(20),
    id_tunjangan INT,
    tanggal DATE,
    jumlah_diterima DECIMAL(10,2),
    FOREIGN KEY (id_pegawai) REFERENCES pegawai(id_pegawai),
    FOREIGN KEY (id_tunjangan) REFERENCES tunjangan(id_tunjangan)
);

-- Insert sample allowance assignments
INSERT INTO pegawai_tunjangan (id_pegawai, id_tunjangan, tanggal, jumlah_diterima) VALUES
('EMP001', 1, '2025-04-01', 500000),
('EMP001', 2, '2025-04-01', 220000), -- 22 days x Rp10.000
('EMP001', 3, '2025-04-01', 1000000),

('EMP002', 1, '2025-04-01', 500000),
('EMP002', 2, '2025-04-01', 230000),
('EMP002', 3, '2025-04-01', 1200000);