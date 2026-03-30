<?php
session_start();

// Cek keamanan
if (!isset($_SESSION['status']) || $_SESSION['status'] != "telah_login" || $_SESSION['role'] != 'pelapor') {
    header("Location: ../auth/login.php");
    exit();
}

require_once __DIR__ . '/../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $id_pelapor = $_SESSION['id_user'];
    $jenis_update = $_POST['jenis_update'];

    // JIKA YANG DIUBAH ADALAH INFO PRIBADI
    if ($jenis_update == 'info_pribadi') {
        $nama_lengkap = trim($_POST['nama_lengkap']);
        // Bersihkan input nomor WA (hanya angka)
        $no_telp = preg_replace('/[^0-9]/', '', $_POST['no_telp']); 

        $query = "UPDATE users SET nama_lengkap = ?, no_telp = ? WHERE id_user = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssi", $nama_lengkap, $no_telp, $id_pelapor);
        
        if ($stmt->execute()) {
            // Update session agar nama di navbar langsung berubah tanpa perlu relogin
            $_SESSION['nama_lengkap'] = $nama_lengkap;
            header("Location: my_profile.php?pesan=update_sukses");
        } else {
            header("Location: my_profile.php?pesan=update_gagal");
        }
        $stmt->close();
    }
} else {
    header("Location: my_profile.php");
}

$conn->close();
?>