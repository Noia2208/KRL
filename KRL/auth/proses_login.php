<?php
session_start();

// Memanggil koneksi database
// Pastikan path ini benar (menyesuaikan letak folder config milikmu)
require_once __DIR__ . '/../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Siapkan query untuk mencari user berdasarkan username
    $query = "SELECT id_user, username, password, nama_lengkap, role FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);

    // Mengecek apakah ada error pada penulisan query atau koneksi tabel
    if (!$stmt) {
        die("Query Error: " . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Cek apakah username ditemukan di database
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        
        // Pengecekan password (karena di database kamu pakai teks biasa)
        if ($password === $data['password']) {
            
            // Jika password benar, simpan data ke session
            $_SESSION['id_user']      = $data['id_user'];
            $_SESSION['username']     = $data['username'];
            $_SESSION['nama_lengkap'] = $data['nama_lengkap'];
            $_SESSION['role']         = $data['role'];
            $_SESSION['status']       = "telah_login";
            
            // Arahkan berdasarkan role pengguna
            if ($data['role'] == 'petugas') {
                // Jika Admin/Petugas
                header("Location: /krl/core/dashboard_petugas.php");
            } else {
                // Jika Penumpang/Pelapor (diarahkan kembali ke halaman beranda utama)
                header("Location: /krl/core/index.php"); 
            }
            exit();
            
        } else {
            // Jika password salah
            header("Location: login.php?pesan=password_salah");
            exit();
        }
    } else {
        // Jika username tidak ada di tabel users
        header("Location: login.php?pesan=username_tidak_ada");
        exit();
    }

    $stmt->close();
}
$conn->close();
?>