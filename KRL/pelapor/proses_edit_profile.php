<?php
session_start();

// Cek keamanan, pastikan yang mengakses adalah pelapor yang sudah login
if (!isset($_SESSION['status']) || $_SESSION['status'] != "telah_login" || $_SESSION['role'] != 'pelapor') {
    header("Location: ../auth/login.php");
    exit();
}

require_once __DIR__ . '/../config/database.php';

// Pastikan data dikirim melalui metode POST dari form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $id_pelapor = $_SESSION['id_user'];
    $jenis_update = $_POST['jenis_update'];

    // ====================================================
    // SKENARIO 1: PENGGUNA MENGUBAH DATA DIRI
    // ====================================================
    if ($jenis_update == 'info_pribadi') {
        $nama_lengkap = trim($_POST['nama_lengkap']);
        
        // Membersihkan input WA agar hanya berisi angka (opsional tapi bagus untuk database)
        $no_telp = preg_replace('/[^0-9]/', '', $_POST['no_telp']); 

        $query = "UPDATE users SET nama_lengkap = ?, no_telp = ? WHERE id_user = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssi", $nama_lengkap, $no_telp, $id_pelapor);
        
        if ($stmt->execute()) {
            // SANGAT PENTING: Update session agar nama di pojok kanan atas (Navbar) langsung berubah!
            $_SESSION['nama_lengkap'] = $nama_lengkap;
            
            header("Location: my_profile.php?pesan=update_sukses");
        } else {
            header("Location: my_profile.php?pesan=update_gagal");
        }
        $stmt->close();
    }
    
    // ====================================================
    // SKENARIO 2: PENGGUNA MENGUBAH PASSWORD
    // ====================================================
    else if ($jenis_update == 'password') {
        $password_baru = $_POST['password_baru'];
        $konfirmasi_password = $_POST['konfirmasi_password'];

        // Cek ganda apakah password baru dan konfirmasi sama
        if ($password_baru !== $konfirmasi_password) {
            header("Location: my_profile.php?pesan=pass_tidak_cocok");
            exit();
        }

        // Catatan: Karena sistem registrasimu menyimpan password secara langsung (plain text),
        // maka di sini kita juga mengupdate-nya secara langsung tanpa password_hash.
        $query = "UPDATE users SET password = ? WHERE id_user = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $password_baru, $id_pelapor);
        
        if ($stmt->execute()) {
            header("Location: my_profile.php?pesan=update_sukses");
        } else {
            header("Location: my_profile.php?pesan=update_gagal");
        }
        $stmt->close();
    }

} else {
    // Jika ada yang mencoba iseng mengakses file ini lewat URL secara langsung
    header("Location: my_profile.php");
}

$conn->close();
?>