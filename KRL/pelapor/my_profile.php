<?php
session_start();

// Cek apakah yang masuk benar-benar pelapor (penumpang)
if (!isset($_SESSION['status']) || $_SESSION['status'] != "telah_login" || $_SESSION['role'] != 'pelapor') {
    header("Location: ../auth/login.php?pesan=belum_login");
    exit();
}

require_once __DIR__ . '/../config/database.php';
$id_pelapor = $_SESSION['id_user'];

// Ambil data user terbaru dari database
$query_user = "SELECT * FROM users WHERE id_user = ?";
$stmt = $conn->prepare($query_user);
$stmt->bind_param("i", $id_pelapor);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$inisial = strtoupper(substr(trim($user['nama_lengkap']), 0, 1));
$tgl_gabung = date('d F Y', strtotime($user['created_at']));

$page_title = "Profil Saya - KRL Lost & Found";
require_once __DIR__ . '/../pelapor/header.php'; 
?>

  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 pb-24">
    <?php 
    if(isset($_GET['pesan'])){
      if($_GET['pesan'] == "update_sukses"){
        echo "<div class='bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded shadow-sm mb-6 flex items-center text-sm font-bold'><i class='fas fa-check-circle mr-3 text-lg'></i> Profil berhasil diperbarui!</div>";
      } else if($_GET['pesan'] == "pass_tidak_cocok"){
        echo "<div class='bg-red-50 border-l-4 border-[#E32227] text-red-700 px-4 py-3 rounded shadow-sm mb-6 flex items-center text-sm font-bold'><i class='fas fa-exclamation-triangle mr-3 text-lg'></i> Konfirmasi password baru tidak cocok!</div>";
      } else if($_GET['pesan'] == "update_gagal"){
        echo "<div class='bg-red-50 border-l-4 border-[#E32227] text-red-700 px-4 py-3 rounded shadow-sm mb-6 flex items-center text-sm font-bold'><i class='fas fa-times-circle mr-3 text-lg'></i> Terjadi kesalahan sistem saat memperbarui profil.</div>";
      }
    }
    ?>

    <div class="bg-gradient-to-r from-[#0054A6] to-[#002f5e] rounded-2xl p-8 sm:p-10 mb-8 relative overflow-hidden shadow-lg flex flex-col sm:flex-row items-center sm:items-start text-center sm:text-left">
      <i class="fas fa-id-card absolute -right-10 -bottom-10 text-9xl text-white opacity-10 transform rotate-12 pointer-events-none"></i>
      <div class="relative z-10 flex-1">
        <h2 class="text-3xl font-bold text-white mb-1"><?php echo htmlspecialchars($user['nama_lengkap']); ?></h2>
        <p class="text-blue-200 font-medium mb-4">@<?php echo htmlspecialchars($user['username']); ?></p>
        
        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3 text-sm">
          <span class="bg-white/10 text-white backdrop-blur-md border border-white/20 px-3 py-1.5 rounded-full font-semibold flex items-center shadow-sm">
            <i class="fas fa-shield-check mr-1.5 text-green-400"></i> Terverifikasi
          </span>
          <span class="bg-white/10 text-white backdrop-blur-md border border-white/20 px-3 py-1.5 rounded-full font-medium flex items-center shadow-sm">
            <i class="far fa-calendar-alt mr-1.5 text-blue-300"></i> Bergabung: <?php echo $tgl_gabung; ?>
          </span>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      
      <div class="bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md transition duration-300 p-6 sm:p-8">
        <div class="flex items-center mb-6">
          <div class="bg-blue-50 p-3 rounded-lg mr-4">
            <i class="fas fa-user-edit text-[#0054A6] text-xl"></i>
          </div>
          <div>
            <h3 class="text-lg font-bold text-gray-900">Data Diri</h3>
            <p class="text-xs text-gray-500">Sesuaikan nama dan kontak Anda.</p>
          </div>
        </div>
        
        <form action="proses_edit_profile.php" method="POST" class="space-y-5">
          <input type="hidden" name="jenis_update" value="info_pribadi">
          
          <div>
            <label for="nama_lengkap" class="block text-sm font-bold text-gray-700 mb-1.5">Nama Lengkap</label>
            <div class="relative">
              <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400"><i class="fas fa-font"></i></span>
              <input type="text" id="nama_lengkap" name="nama_lengkap" value="<?php echo htmlspecialchars($user['nama_lengkap']); ?>" required class="w-full pl-11 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0054A6] focus:border-transparent transition-all">
            </div>
          </div>

          <div>
            <label for="no_telp" class="block text-sm font-bold text-gray-700 mb-1.5">Nomor WhatsApp</label>
            <div class="relative">
              <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400"><i class="fab fa-whatsapp text-lg"></i></span>
              <input type="text" id="no_telp" name="no_telp" value="<?php echo htmlspecialchars($user['no_telp']); ?>" required class="w-full pl-11 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0054A6] focus:border-transparent transition-all">
            </div>
          </div>

          <div>
            <label class="block text-sm font-bold text-gray-700 mb-1.5">Username <span class="text-xs font-normal text-[#E32227] italic ml-1">*Permanen</span></label>
            <div class="relative">
              <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400"><i class="fas fa-at"></i></span>
              <input type="text" value="<?php echo htmlspecialchars($user['username']); ?>" disabled class="w-full pl-11 pr-4 py-2.5 bg-gray-100 border border-gray-200 rounded-xl text-sm text-gray-500 cursor-not-allowed">
            </div>
          </div>

          <div class="pt-2">
            <button type="submit" class="w-full bg-[#0054A6] hover:bg-blue-800 text-white text-sm font-bold py-3 rounded-xl transition duration-300 shadow-lg shadow-blue-200 flex justify-center items-center">
              Simpan Data Diri
            </button>
          </div>
        </form>
      </div>

      <div class="bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md transition duration-300 p-6 sm:p-8">
        <div class="flex items-center mb-6">
          <div class="bg-red-50 p-3 rounded-lg mr-4">
            <i class="fas fa-shield-alt text-[#E32227] text-xl"></i>
          </div>
          <div>
            <h3 class="text-lg font-bold text-gray-900">Keamanan Akun</h3>
            <p class="text-xs text-gray-500">Perbarui kata sandi Anda.</p>
          </div>
        </div>
        
        <form action="proses_edit_profile.php" method="POST" class="space-y-5">
          <input type="hidden" name="jenis_update" value="password">
          
          <div>
            <label for="password_baru" class="block text-sm font-bold text-gray-700 mb-1.5">Password Baru</label>
            <div class="relative">
              <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400"><i class="fas fa-lock"></i></span>
              <input type="password" id="password_baru" name="password_baru" placeholder="Masukkan minimal 6 karakter" minlength="6" required class="w-full pl-11 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#E32227] focus:border-transparent transition-all">
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
            <label for="konfirmasi_password" class="block text-sm font-bold text-gray-700 mb-1.5">Ulangi Password Baru</label>
            <div class="relative">
              <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400"><i class="fas fa-check-double"></i></span>
              <input type="password" id="konfirmasi_password" name="konfirmasi_password" placeholder="Ketik ulang password baru" required class="w-full pl-11 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#E32227] focus:border-transparent transition-all">
            </div>
          </div>
          
          <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex items-start mt-2">
            <i class="fas fa-info-circle text-[#0054A6] mt-0.5 mr-2"></i>
            <p class="text-xs text-blue-900 leading-relaxed">Pastikan Anda mengingat password baru Anda. Jangan berikan password kepada siapapun termasuk petugas KRL.</p>
          </div>

          <div class="pt-2">
            <button type="submit" class="w-full bg-white border-2 border-[#E32227] text-[#E32227] hover:bg-red-50 text-sm font-bold py-2.5 rounded-xl transition duration-300 flex justify-center items-center">
              Update Password Akun
            </button>
          </div>
        </form>
      </div>

    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
        const passwordInput = document.getElementById('password_baru'); // ID Disesuaikan untuk form edit
        const meterContainer = document.getElementById('strength-container');
        const strengthBar = document.getElementById('strength-bar');
        const strengthText = document.getElementById('strength-text');
        const strengthPercent = document.getElementById('strength-percent');

        passwordInput.addEventListener('input', function() {
            const val = passwordInput.value;
            
            if (val.length > 0) {
                meterContainer.classList.remove('hidden');
            } else {
                meterContainer.classList.add('hidden');
                strengthBar.style.width = '0%';
                return;
            }

            // PERHITUNGAN SKOR (Maksimal 100%)
            let score = 0;
            score += Math.min(val.length * 4, 40);
            if (val.match(/[a-z]/)) score += 15; 
            if (val.match(/[A-Z]/)) score += 15; 
            if (val.match(/[0-9]/)) score += 15; 
            if (val.match(/[^a-zA-Z0-9]/)) score += 15; 

            score = Math.min(score, 100);

            // Update UI
            strengthBar.style.width = score + '%';
            
            let displayedScore = score;
            if(score > 98) displayedScore = 100; 
            strengthPercent.textContent = displayedScore + '%';

            strengthBar.className = "h-full transition-all duration-500 ease-out rounded-full";
            
            if (score < 40) {
                strengthBar.classList.add('bg-red-500');
                strengthText.textContent = "Sangat Lemah";
                strengthText.className = "text-[11px] font-bold text-red-500 transition-colors duration-300";
            } else if (score < 70) {
                strengthBar.classList.add('bg-yellow-400');
                strengthText.textContent = "Lumayan";
                strengthText.className = "text-[11px] font-bold text-yellow-500 transition-colors duration-300";
            } else if (score < 90) {
                strengthBar.classList.add('bg-blue-400');
                strengthText.textContent = "Kuat";
                strengthText.className = "text-[11px] font-bold text-blue-500 transition-colors duration-300";
            } else {
                strengthBar.classList.add('bg-green-500');
                strengthText.textContent = "Sangat Aman";
                strengthText.className = "text-[11px] font-bold text-green-500 transition-colors duration-300";
            }
        });
    });
  </script>

<?php 
$stmt->close();
include '../layout/footer.php'; 
?>