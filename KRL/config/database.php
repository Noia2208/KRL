<?php
// Konfigurasi Database
$host     = "localhost";      // Server database (biasanya localhost)
$username = "root";           // Username default XAMPP/Laragon
$password = "";               // Password default kosong
$database = "krldb"; // Nama database yang akan kita gunakan

// Membuat koneksi menggunakan MySQLi
$conn = new mysqli($host, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}
?>