<?php
// 1. Mulai sesi untuk mendeteksi siapa yang sedang login
session_start();

// 2. Bersihkan semua data memori (seperti nama dan role)
session_unset(); 

// 3. Hancurkan sesinya secara total! 💥
session_destroy(); 

// 4. Arahkan kembali ke halaman utama (keluar 1 folder ke index.html)
header("Location: ../index.php"); 
exit();
?>