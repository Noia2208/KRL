<?php
session_start();

// Cek keamanan
if (!isset($_SESSION['status']) || $_SESSION['status'] != "telah_login" || $_SESSION['role'] != 'petugas') {
    header("Location: ../login.php?pesan=belum_login");
    exit();
}

require_once __DIR__ . '/../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Tangkap data dari form
    $id_temuan        = $_POST['id_temuan'];
    $nama_barang      = $_POST['nama_barang'];
    $kategori         = $_POST['kategori'];
    $lokasi_ditemukan = $_POST['lokasi_ditemukan'];
    $waktu_ditemukan  = $_POST['waktu_ditemukan']; 
    $deskripsi        = $_POST['deskripsi'];
    $status_barang    = $_POST['status_barang'];

    // Query UPDATE database (singkat dan aman dari SQL Injection)
    $query = "UPDATE barang_temuan SET 
                nama_barang = ?, 
                kategori = ?, 
                lokasi_ditemukan = ?, 
                waktu_ditemukan = ?, 
                deskripsi = ?, 
                status_barang = ? 
              WHERE id_temuan = ?";
              
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        die("Query Error: " . $conn->error);
    }

    // "ssssssi" = 6 String, 1 Integer (ID di bagian paling akhir query)
    $stmt->bind_param("ssssssi", $nama_barang, $kategori, $lokasi_ditemukan, $waktu_ditemukan, $deskripsi, $status_barang, $id_temuan);

    // Eksekusi dan pindahkan kembali ke dashboard utama
    if ($stmt->execute()) {
        header("Location: ../core/dashboard_petugas.php?pesan=edit_sukses");
    } else {
        header("Location: ../core/dashboard_petugas.php?pesan=edit_gagal");
    }

    $stmt->close();
} else {
    // Jika tidak ada data POST (orang iseng buka URL)
    header("Location: ../core/dashboard_petugas.php");
}

$conn->close();
?>