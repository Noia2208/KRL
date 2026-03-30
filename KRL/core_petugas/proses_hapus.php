<?php
session_start();

// 1. KEAMANAN
if (!isset($_SESSION['status']) || $_SESSION['status'] != "telah_login" || $_SESSION['role'] != 'petugas') {
    // Arahkan kembali ke luar folder menuju auth/login
    header("Location: ../login.php?pesan=belum_login"); // Sesuaikan jika login.php ada di luar
    exit();
}

// 2. Panggil koneksi database (Naik 1 folder ke krl, lalu masuk ke config)
require_once __DIR__ . '/../config/database.php';

// 3. Tangkap ID
if (isset($_GET['id'])) {
    $id_temuan = $_GET['id'];

    $query = "DELETE FROM barang_temuan WHERE id_temuan = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        die("Query Error: " . $conn->error);
    }

    $stmt->bind_param("i", $id_temuan);

    // 4. Eksekusi dan KEMBALI KE FOLDER CORE
    if ($stmt->execute()) {
        // PERHATIKAN BARIS INI: Kita arahkan naiki 1 folder (..), lalu masuk ke folder core
        header("Location: ../core/dashboard_petugas.php?pesan=hapus_sukses");
    } else {
        header("Location: ../core/dashboard_petugas.php?pesan=hapus_gagal");
    }

    $stmt->close();
} else {
    header("Location: ../core/dashboard_petugas.php");
}

$conn->close();
?>