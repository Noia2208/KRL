<?php
session_start();

// Memanggil koneksi database
// Pastikan path ini sesuai dengan letak folder config milikmu
require_once __DIR__ . '/../config/database.php';

// Cek apakah data benar-benar dikirim dari form (menggunakan method POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Tangkap semua data dari input form HTML
    $nama_lengkap = $_POST['nama_lengkap'];
    $no_telp      = $_POST['no_telp'];
    $username     = $_POST['username'];
    $password     = $_POST['password']; 
    
    // 2. KUNCI UTAMA: Otomatis berikan role 'pelapor' untuk akun baru
    $role         = 'pelapor'; 

    // 3. CEK USERNAME: Apakah username ini sudah dipakai oleh orang lain?
    $cek_query = "SELECT id_user FROM users WHERE username = ?";
    $stmt_cek = $conn->prepare($cek_query);
    
    if (!$stmt_cek) {
        die("Query Error: " . $conn->error);
    }
    
    $stmt_cek->bind_param("s", $username);
    $stmt_cek->execute();
    $result_cek = $stmt_cek->get_result();

    if ($result_cek->num_rows > 0) {
        // Jika username sudah ada di database, batalkan pendaftaran dan kembalikan ke form
        header("Location: register.php?pesan=username_terpakai");
        $stmt_cek->close();
        $conn->close();
        exit();
    }
    $stmt_cek->close();

    // 4. JIKA USERNAME AMAN: Mulai proses simpan ke tabel users
    $insert_query = "INSERT INTO users (username, password, nama_lengkap, no_telp, role) VALUES (?, ?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($insert_query);
    
    // Cek jika ada error penulisan query
    if (!$stmt_insert) {
        die("Query Error: " . $conn->error);
    }

    // "sssss" berarti kita mengirimkan 5 data bertipe String ke database
    $stmt_insert->bind_param("sssss", $username, $password, $nama_lengkap, $no_telp, $role);

    // 5. Eksekusi query dan pindahkan halaman berdasarkan hasil
    if ($stmt_insert->execute()) {
        // Sukses! Arahkan penumpang ke halaman login untuk masuk
        header("Location: login.php?pesan=berhasil_daftar");
    } else {
        // Gagal! (Misalnya database tiba-tiba down)
        header("Location: register.php?pesan=gagal_daftar");
    }

    $stmt_insert->close();
}

$conn->close();
?>