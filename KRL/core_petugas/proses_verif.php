<?php
session_start();

// Cek keamanan petugas
if (!isset($_SESSION['status']) || $_SESSION['status'] != "telah_login" || $_SESSION['role'] != 'petugas') {
    header("Location: ../auth/login.php");
    exit();
}

require_once __DIR__ . '/../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $id_klaim = $_POST['id_klaim'];
    $id_temuan = $_POST['id_temuan'];
    $keputusan = $_POST['keputusan']; // Isinya: 'Disetujui' atau 'Ditolak'
    $catatan_petugas = trim($_POST['catatan_petugas']);
    $id_petugas = $_SESSION['id_user']; // ID Petugas yang sedang login

    // 1. UPDATE TABEL klaim_barang
    $query_update = "UPDATE klaim_barang SET status_klaim = ? WHERE id_klaim = ?";
    $stmt_update = $conn->prepare($query_update);
    $stmt_update->bind_param("si", $keputusan, $id_klaim);
    
    if ($stmt_update->execute()) {
        
        // --- TRIGGER MYSQL BEKERJA DI SINI SECARA OTOMATIS ---
        // Jika Disetujui -> barang_temuan jadi 'Dikembalikan'
        // Jika Ditolak -> barang_temuan jadi 'Tersedia' kembali
        
        // 2. JIKA DISETUJUI, MASUKKAN KE riwayat_penyerahan
        if ($keputusan == 'Disetujui') {
            $query_riwayat = "INSERT INTO riwayat_penyerahan (id_klaim, id_petugas, catatan_petugas) VALUES (?, ?, ?)";
            $stmt_riwayat = $conn->prepare($query_riwayat);
            $stmt_riwayat->bind_param("iis", $id_klaim, $id_petugas, $catatan_petugas);
            $stmt_riwayat->execute();
            $stmt_riwayat->close();
        header("Location: dashboard_petugas.php?pesan=edit_sukses");
        }

        header("Location: /krl/core/dashboard_petugas.php?pesan=edit_sukses");
    } else {
        header("Location: /krl/core/dashboard_petugas.php?pesan=edit_gagal");
    }

    $stmt_update->close();
} else {
    header("Location: /krl/core/dashboard_petugas.php");
}

$conn->close();
?>