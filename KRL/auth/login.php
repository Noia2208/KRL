<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - KRL Lost & Found</title>
  
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  
  <link rel="stylesheet" href="../css/auth.css" />
</head>
<body class="bg-slate-50 font-sans text-gray-800">

  <div class="flex min-h-screen">
    
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-gray-900">
      
      <div id="slideTrack" class="flex w-[300%] h-full transition-transform duration-1000 ease-in-out">
        <div class="w-1/3 h-full bg-cover bg-center" style="background-image: url('/krl/assets/masinis.jpeg');"></div>
        <div class="w-1/3 h-full bg-cover bg-center" style="background-image: url('/krl/assets/stasiun.jpeg');"></div>
        <div class="w-1/3 h-full bg-cover bg-center" style="background-image: url('/krl/assets/krl.jpeg');"></div>
      </div>
      
      <div class="absolute inset-0 bg-gradient-to-br from-[#0054A6] to-[#002f5e] bg-opacity-80 mix-blend-multiply pointer-events-none"></div>
      
      <div class="absolute inset-0 z-10 flex flex-col justify-center items-start p-16 w-full text-white pointer-events-none">
        <div class="flex items-center space-x-3 mb-8">
          <span class="inline-block py-1 px-3 border border-white/30 rounded-md text-xs font-bold tracking-wider uppercase bg-white/10 backdrop-blur-sm">
            Portal Petugas & Penumpang
          </span>
        </div>
        <h2 class="text-4xl font-bold mb-6 leading-tight drop-shadow-md">Menghubungkan Kembali<br>Barang Berharga Anda.</h2>
        <p class="text-blue-100 text-lg max-w-md leading-relaxed drop-shadow">
          Sistem informasi terpadu pelaporan dan pencarian barang tertinggal di seluruh jaringan Commuter Line.
        </p>
      </div>
    </div>

    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12 md:p-16 relative z-20 bg-white border-l border-gray-200 shadow-2xl">
      <div class="w-full max-w-md">
        
        <div class="mb-10 lg:hidden flex items-center justify-center space-x-2">
          <i class="fas fa-train text-[#E32227] text-2xl"></i>
          <span class="text-xl font-bold text-[#0054A6]">KRL Lost & Found</span>
        </div>

        <h2 class="text-3xl font-bold text-gray-900 mb-2">Halooo...</h2>
        <p class="text-gray-500 mb-8 text-sm">Login dulu sebelum mencari barang ya...!!!</p>

        <?php 
        if(isset($_GET['pesan'])){
          if($_GET['pesan'] == "password_salah"){
            echo "<div class='bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md mb-6 flex items-center text-sm font-medium'><i class='fas fa-exclamation-circle mr-3 text-lg'></i> Password yang Anda masukkan salah!</div>";
          } else if($_GET['pesan'] == "username_tidak_ada"){
            echo "<div class='bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md mb-6 flex items-center text-sm font-medium'><i class='fas fa-user-times mr-3 text-lg'></i> Username tidak ditemukan!</div>";
          } else if($_GET['pesan'] == "berhasil_daftar"){
            echo "<div class='bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md mb-6 flex items-center text-sm font-medium'><i class='fas fa-check-circle mr-3 text-lg'></i> Registrasi berhasil! Silakan login.</div>";
          } else if($_GET['pesan'] == "belum_login"){
            echo "<div class='bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-md mb-6 flex items-center text-sm font-medium'><i class='fas fa-lock mr-3 text-lg'></i> Anda harus login terlebih dahulu!</div>";
          } else if($_GET['pesan'] == "logout"){
            echo "<div class='bg-blue-50 border border-blue-200 text-[#0054A6] px-4 py-3 rounded-md mb-6 flex items-center text-sm font-medium'><i class='fas fa-info-circle mr-3 text-lg'></i> Anda telah berhasil keluar dari sistem.</div>";
          }
        }
        ?>

        <form action="proses_login.php" method="POST" class="space-y-5">
          <div>
            <label for="username" class="block text-sm font-semibold text-gray-700 mb-1">Username</label>
            <div class="relative">
              <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <i class="fas fa-user"></i>
              </span>
              <input type="text" id="username" name="username" class="w-full pl-10 pr-4 py-2 bg-white border border-gray-300 rounded-md text-sm text-gray-800 focus:outline-none focus:ring-1 focus:ring-[#0054A6] focus:border-[#0054A6] transition" placeholder="Ketik username Anda" required>
            </div>
          </div>

          <div>
            <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
            <div class="relative">
              <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <i class="fas fa-lock"></i>
              </span>
              <input type="password" id="password" name="password" class="w-full pl-10 pr-10 py-2 bg-white border border-gray-300 rounded-md text-sm text-gray-800 focus:outline-none focus:ring-1 focus:ring-[#0054A6] focus:border-[#0054A6] transition" placeholder="Ketik password Anda" required>
            </div>
          </div>

          <button type="submit" class="w-full bg-[#E32227] text-white font-semibold py-2.5 px-4 rounded-md hover:bg-red-700 transition duration-300 shadow-sm flex justify-center items-center mt-4">
            Masuk ke Sistem <i class="fas fa-arrow-right ml-2 text-sm"></i>
          </button>
        </form>

        <div class="mt-8 pt-6 border-t border-gray-200 flex flex-col space-y-4">
          <p class="text-center text-sm text-gray-600">
            Penumpang baru? 
            <a href="registrasi.php" class="text-[#0054A6] font-semibold hover:underline">Buat akun laporan di sini</a>
          </p>
          <a href="../index.php" class="text-center text-sm text-gray-500 hover:text-[#E32227] font-medium transition flex items-center justify-center">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Beranda
          </a>
        </div>

      </div>
    </div>
  </div>

  <script src="../js/auth.js"></script>
</body>
</html>