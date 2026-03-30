<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrasi - KRL Lost & Found</title>
  
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  
  <link rel="stylesheet" href="../css/auth.css" />
</head>
<body class="bg-slate-50 font-sans text-gray-800">

  <div class="flex min-h-screen">
    
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-gray-900">
      
      <div id="slideTrack" class="flex w-[300%] h-full transition-transform duration-1000 ease-in-out">
        <div class="w-1/3 h-full bg-cover bg-center" style="background-image: url('../assets/masinis.jpeg');"></div>
        <div class="w-1/3 h-full bg-cover bg-center" style="background-image: url('../assets/stasiun.jpeg');"></div>
        <div class="w-1/3 h-full bg-cover bg-center" style="background-image: url('../assets/krl.jpeg');"></div>
      </div>
      
      <div class="absolute inset-0 bg-gradient-to-br from-[#0054A6] to-[#002f5e] bg-opacity-80 mix-blend-multiply pointer-events-none"></div>
      
      <div class="absolute inset-0 z-10 flex flex-col justify-center items-start p-16 w-full text-white pointer-events-none">
        <div class="flex items-center space-x-3 mb-8">
          <span class="inline-block py-1 px-3 border border-white/30 rounded-md text-xs font-bold tracking-wider uppercase bg-white/10 backdrop-blur-sm">
            Portal Penumpang Baru
          </span>
        </div>
        <h2 class="text-4xl font-bold mb-6 leading-tight drop-shadow-md">Bergabung Bersama<br>Komunitas Kami.</h2>
        <p class="text-blue-100 text-lg max-w-md leading-relaxed drop-shadow">
          Buat akun Anda sekarang untuk mempermudah proses pelaporan dan pencarian barang berharga yang tertinggal di Commuter Line.
        </p>
      </div>
    </div>

    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12 md:p-16 relative z-20 bg-white border-l border-gray-200 shadow-2xl max-h-screen overflow-y-auto custom-scrollbar">
      <div class="w-full max-w-md py-4">
        
        <div class="mb-8 lg:hidden flex items-center justify-center space-x-2">
          <i class="fas fa-train text-[#E32227] text-2xl"></i>
          <span class="text-xl font-bold text-[#0054A6]">KRL Lost & Found</span>
        </div>

        <h2 class="text-3xl font-bold text-gray-900 mb-2">Daftar Akun Baru</h2>
        <p class="text-gray-500 mb-6 text-sm">Lengkapi data diri Anda di bawah ini dengan benar.</p>

        <?php 
        if(isset($_GET['pesan'])){
          if($_GET['pesan'] == "username_terpakai"){
            echo "<div class='bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md mb-6 flex items-center text-sm font-medium'><i class='fas fa-exclamation-triangle mr-3 text-lg'></i> Username sudah dipakai, coba yang lain!</div>";
          } else if($_GET['pesan'] == "gagal_daftar"){
            echo "<div class='bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md mb-6 flex items-center text-sm font-medium'><i class='fas fa-times-circle mr-3 text-lg'></i> Sistem sibuk, gagal mendaftar!</div>";
          }
        }
        ?>

        <form id="registerForm" action="proses_registrasi.php" method="POST" class="space-y-4">
          
          <div>
            <label for="nama_lengkap" class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
            <div class="relative">
              <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><i class="fas fa-id-card"></i></span>
              <input type="text" id="nama_lengkap" name="nama_lengkap" class="w-full pl-10 pr-4 py-2 bg-white border border-gray-300 rounded-md text-sm text-gray-800 focus:outline-none focus:ring-1 focus:ring-[#0054A6] focus:border-[#0054A6] transition" placeholder="Contoh: Budi Santoso" required>
            </div>
          </div>

          <div>
            <label for="no_telp" class="block text-sm font-semibold text-gray-700 mb-1">Nomor WhatsApp</label>
            <div class="relative">
              <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><i class="fab fa-whatsapp"></i></span>
              <input type="tel" id="no_telp" name="no_telp" class="w-full pl-10 pr-4 py-2 bg-white border border-gray-300 rounded-md text-sm text-gray-800 focus:outline-none focus:ring-1 focus:ring-[#0054A6] focus:border-[#0054A6] transition" placeholder="Contoh: 081234567890" required>
            </div>
          </div>

          <div>
            <label for="reg_username" class="block text-sm font-semibold text-gray-700 mb-1">Username Baru</label>
            <div class="relative">
              <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><i class="fas fa-user-tag"></i></span>
              <input type="text" id="reg_username" name="username" class="w-full pl-10 pr-4 py-2 bg-white border border-gray-300 rounded-md text-sm text-gray-800 focus:outline-none focus:ring-1 focus:ring-[#0054A6] focus:border-[#0054A6] transition" placeholder="Buat username tanpa spasi" required>
            </div>
          </div>

          <div>
            <label for="reg_password" class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
            <div class="relative">
              <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><i class="fas fa-lock"></i></span>
              <input type="password" id="reg_password" name="password" class="w-full pl-10 pr-10 py-2 bg-white border border-gray-300 rounded-md text-sm text-gray-800 focus:outline-none focus:ring-1 focus:ring-[#0054A6] focus:border-[#0054A6] transition" placeholder="Minimal 6 karakter" required minlength="6">
            </div>
            
            <div id="strength-container" class="mt-2.5 hidden">
              <div class="w-full bg-gray-200 rounded-full h-1.5 overflow-hidden">
                <div id="strength-bar" class="h-full w-0 transition-all duration-500 ease-out rounded-full bg-red-500"></div>
              </div>
              <div class="flex justify-between items-center mt-1.5">
                <p id="strength-text" class="text-[11px] font-bold text-gray-500 transition-colors duration-300">Ketik password...</p>
                <p id="strength-percent" class="text-[11px] font-bold text-gray-400">0%</p>
              </div>
            </div>
          </div>

          <div>
            <label for="konfirmasi_password" class="block text-sm font-semibold text-gray-700 mb-1">Konfirmasi Password</label>
            <div class="relative">
              <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><i class="fas fa-lock"></i></span>
              <input type="password" id="konfirmasi_password" class="w-full pl-10 pr-10 py-2 bg-white border border-gray-300 rounded-md text-sm text-gray-800 focus:outline-none focus:ring-1 focus:ring-[#0054A6] focus:border-[#0054A6] transition" placeholder="Ketik ulang password" required>
            </div>
            <p id="passwordError" class="text-red-500 text-xs mt-1.5 hidden font-semibold"><i class="fas fa-times-circle mr-1"></i> Password tidak cocok!</p>
          </div>

          <button type="submit" class="w-full bg-[#0054A6] text-white font-semibold py-2.5 px-4 rounded-md hover:bg-blue-800 transition duration-300 shadow-sm flex justify-center items-center mt-6">
            Daftar Sekarang <i class="fas fa-user-plus ml-2 text-sm"></i>
          </button>
        </form>

        <div class="mt-6 pt-6 border-t border-gray-200 flex flex-col space-y-4">
          <p class="text-center text-sm text-gray-600">
            Sudah punya akun? 
            <a href="login.php" class="text-[#E32227] font-semibold hover:underline transition">Login di sini</a>
          </p>
          <a href="../index.php" class="text-center text-sm text-gray-500 hover:text-[#0054A6] font-medium transition flex items-center justify-center">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Beranda
          </a>
        </div>

      </div>
    </div>
  </div>

  <script src="../js/auth.js"></script>
  
  <script>
    document.addEventListener("DOMContentLoaded", function() {
        const passwordInput = document.getElementById('reg_password');
        const meterContainer = document.getElementById('strength-container');
        const strengthBar = document.getElementById('strength-bar');
        const strengthText = document.getElementById('strength-text');
        const strengthPercent = document.getElementById('strength-percent');

        passwordInput.addEventListener('input', function() {
            const val = passwordInput.value;
            
            // Tampilkan bar jika user mulai mengetik
            if (val.length > 0) {
                meterContainer.classList.remove('hidden');
            } else {
                meterContainer.classList.add('hidden');
                strengthBar.style.width = '0%';
                return;
            }

            // PERHITUNGAN SKOR (Maksimal 100%)
            let score = 0;

            // 1. Poin dari panjang karakter (Setiap 1 huruf = 4%, maksimal 40%)
            score += Math.min(val.length * 4, 40);

            // 2. Poin dari variasi karakter
            if (val.match(/[a-z]/)) score += 15; // Ada huruf kecil (+15%)
            if (val.match(/[A-Z]/)) score += 15; // Ada huruf besar (+15%)
            if (val.match(/[0-9]/)) score += 15; // Ada angka (+15%)
            if (val.match(/[^a-zA-Z0-9]/)) score += 15; // Ada simbol spesial (+15%)

            // Batasi skor maksimal 100
            score = Math.min(score, 100);

            // Terapkan perubahan lebar (width) bar secara animasi (persentase)
            strengthBar.style.width = score + '%';
            
            // Update teks persentase di sebelah kanan
            let displayedScore = score;
            if(score > 98) displayedScore = 100; // Pembulatan visual agar mantap di 100%
            strengthPercent.textContent = displayedScore + '%';

            // Reset class untuk warnanya (tapi biarkan animasinya)
            strengthBar.className = "h-full transition-all duration-500 ease-out rounded-full";
            
            // Berikan warna & teks berdasarkan tingkatan persentase
            if (score < 40) {
                // Sangat Lemah
                strengthBar.classList.add('bg-red-500');
                strengthText.textContent = "Sangat Lemah";
                strengthText.className = "text-[11px] font-bold text-red-500 transition-colors duration-300";
            } else if (score < 70) {
                // Lumayan
                strengthBar.classList.add('bg-yellow-400');
                strengthText.textContent = "Lumayan";
                strengthText.className = "text-[11px] font-bold text-yellow-500 transition-colors duration-300";
            } else if (score < 90) {
                // Kuat
                strengthBar.classList.add('bg-blue-400');
                strengthText.textContent = "Kuat";
                strengthText.className = "text-[11px] font-bold text-blue-500 transition-colors duration-300";
            } else {
                // Sangat Aman (Skor 90-100)
                strengthBar.classList.add('bg-green-500');
                strengthText.textContent = "Sangat Aman";
                strengthText.className = "text-[11px] font-bold text-green-500 transition-colors duration-300";
            }
        });
    });
  </script>
  
</body>
</html>