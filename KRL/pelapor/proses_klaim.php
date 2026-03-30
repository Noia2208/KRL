<?php
session_start();

// Cek keamanan
if (!isset($_SESSION['status']) || $_SESSION['status'] != "telah_login" || $_SESSION['role'] != 'pelapor') {
    header("Location: ../auth/login.php");
    exit();
}

require_once __DIR__ . '/../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Tangkap data dari form
    $id_temuan = $_POST['id_temuan'];
    $id_pelapor = $_SESSION['id_user']; // Diambil dari session login
    $bukti_kepemilikan = trim($_POST['bukti_kepemilikan']);

    // Validasi dasar
    if (empty($bukti_kepemilikan)) {
        echo "<script>alert('Bukti kepemilikan tidak boleh kosong!'); window.history.back();</script>";
        exit();
    }

    // 1. Cek dulu apakah barang masih tersedia (mencegah bentrok jika ada 2 orang klik klaim bersamaan)
    $cek_status = $conn->query("SELECT status_barang FROM barang_temuan WHERE id_temuan = $id_temuan")->fetch_assoc();
    
    if($cek_status['status_barang'] != 'Tersedia') {
        echo "<script>alert('Terlambat! Barang ini baru saja diajukan klaimnya oleh orang lain.'); window.location.href='/krl/core/index.php';</script>";
        exit();
    }

    // 2. Insert ke tabel klaim_barang (Status defaultnya 'Menunggu Verifikasi')
    $query = "INSERT INTO klaim_barang (id_temuan, id_pelapor, bukti_kepemilikan) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    
    if (!$stmt) {
        die("Query Error: " . $conn->error);
    }

    $stmt->bind_param("iis", $id_temuan, $id_pelapor, $bukti_kepemilikan);

    if ($stmt->execute()) {
        // TRIGGER MySQL kamu akan otomatis jalan di sini! (Mengubah barang jadi 'Proses Klaim')
        
        // Kembalikan ke dashboard dengan pesan sukses
        // Sesuaikan 'index.php' dengan nama dashboard pelapor kamu
        header("Location: /krl/core/index.php?pesan=klaim_sukses");
    } else {
        echo "<script>alert('Gagal mengajukan klaim. Sistem sedang sibuk.'); window.history.back();</script>";
    }

    $stmt->close();
} else {
    header("Location: /krl/core/index.php");
}

$conn->close();
?>