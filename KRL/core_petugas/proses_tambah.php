<?php
session_start();

// 1. KEAMANAN
if (!isset($_SESSION['status']) || $_SESSION['status'] != "telah_login" || $_SESSION['role'] != 'petugas') {
    header("Location: ../login.php?pesan=belum_login");
    exit();
}

require_once __DIR__ . '/../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 2. Tangkap data dari form
    $nama_barang      = $_POST['nama_barang'];
    $kategori         = $_POST['kategori'];
    $lokasi_ditemukan = $_POST['lokasi_ditemukan'];
    $waktu_ditemukan  = $_POST['waktu_ditemukan']; // Format dari HTML: YYYY-MM-DDTHH:MM
    $deskripsi        = $_POST['deskripsi'];
    
    // 3. Ambil ID Petugas dari Session yang sedang login!
    // Di database kamu relasinya adalah id_petugas mengambil dari id_user di tabel users
    $id_petugas = $_SESSION['id_user']; 

    // 4. Siapkan query (Status Barang akan otomatis terisi 'Tersedia' berkat DEFAULT di SQL kamu)
    $query = "INSERT INTO barang_temuan (id_petugas, nama_barang, kategori, deskripsi, lokasi_ditemukan, waktu_ditemukan) 
              VALUES (?, ?, ?, ?, ?, ?)";
              
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        die("Query Error: " . $conn->error);
    }

    // "isssss" = Integer (id_petugas), sisanya String
    $stmt->bind_param("isssss", $id_petugas, $nama_barang, $kategori, $deskripsi, $lokasi_ditemukan, $waktu_ditemukan);

    // 5. Eksekusi dan pindahkan kembali ke dashboard utama (folder core)
    if ($stmt->execute()) {
        header("Location: ../core/dashboard_petugas.php?pesan=tambah_sukses");
    } else {
        header("Location: ../core/dashboard_petugas.php?pesan=tambah_gagal");
    }

    $stmt->close();
}

$conn->close();
?>