<?php
session_start();

// Cek keamanan
if (!isset($_SESSION['status']) || $_SESSION['status'] != "telah_login" || $_SESSION['role'] != 'petugas') {
    header("Location: ../auth/login.php");
    exit();
}

require_once __DIR__ . '/../config/database.php';

if (!isset($_GET['id'])) {
    header("Location: dashboard_petugas.php");
    exit();
}

$id_temuan = $_GET['id'];

// Ambil data Klaim + Barang + Pelapor dengan JOIN
$query = "SELECT k.*, b.nama_barang, b.kategori, b.lokasi_ditemukan, b.deskripsi AS deskripsi_petugas, 
                 u.nama_lengkap AS nama_pelapor, u.no_telp, u.username 
          FROM klaim_barang k
          JOIN barang_temuan b ON k.id_temuan = b.id_temuan
          JOIN users u ON k.id_pelapor = u.id_user
          WHERE k.id_temuan = ? AND k.status_klaim = 'Menunggu Verifikasi'
          ORDER BY k.waktu_klaim ASC LIMIT 1";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_temuan);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<script>alert('Data klaim tidak ditemukan atau sudah diproses.'); window.location.href='dashboard_petugas.php';</script>";
    exit();
}

$klaim = $result->fetch_assoc();
$waktu_klaim = date('d F Y, H:i', strtotime($klaim['waktu_klaim']));

$page_title = "Verifikasi Klaim - KRL Lost & Found";
require_once __DIR__ . '/header.php'; 
?>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 pb-24">
    
    <nav class="flex text-sm text-gray-500 mb-6 font-medium">
      <ol class="flex items-center space-x-2">
        <li><a href="dashboard_petugas.php" class="hover:text-[#0054A6] transition"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><span class="mx-2 text-gray-400">/</span></li>
        <li class="text-gray-800">Verifikasi Klaim Penumpang</li>
      </ol>
    </nav>

    <div class="mb-8">
      <h1 class="text-2xl font-bold text-gray-900">Verifikasi Klaim #KLM-<?php echo $klaim['id_klaim']; ?></h1>
      <p class="text-gray-500 mt-1 text-sm">Bandingkan deskripsi penumpang dengan fisik barang temuan yang sebenarnya.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      
      <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="bg-yellow-50 px-6 py-4 border-b border-yellow-200 flex items-center">
          <i class="fas fa-user-check text-yellow-600 text-xl mr-3"></i>
          <h3 class="font-bold text-gray-800">Keterangan Penumpang (Pelapor)</h3>
        </div>
        
        <div class="p-6">
          <div class="grid grid-cols-2 gap-4 mb-6 pb-6 border-b border-gray-100">
            <div>
              <p class="text-xs text-gray-500 mb-1">Nama Lengkap</p>
              <p class="font-bold text-gray-900"><?php echo htmlspecialchars($klaim['nama_pelapor']); ?></p>
            </div>
            <div>
              <p class="text-xs text-gray-500 mb-1">No. WhatsApp</p>
              <p class="font-semibold text-green-600 flex items-center">
                <i class="fab fa-whatsapp mr-1.5"></i> <?php echo htmlspecialchars($klaim['no_telp']); ?>
              </p>
            </div>
            <div>
              <p class="text-xs text-gray-500 mb-1">Waktu Mengajukan</p>
              <p class="font-medium text-gray-800 text-sm"><?php echo $waktu_klaim; ?></p>
            </div>
          </div>

          <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Bukti/Ciri Khusus dari Penumpang:</label>
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 text-sm text-gray-700 whitespace-pre-line leading-relaxed italic">
              "<?php echo htmlspecialchars($klaim['bukti_kepemilikan']); ?>"
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden flex flex-col">
        <div class="bg-blue-50 px-6 py-4 border-b border-blue-200 flex items-center">
          <i class="fas fa-box text-[#0054A6] text-xl mr-3"></i>
          <h3 class="font-bold text-gray-800">Data Barang Fisik (Sistem)</h3>
        </div>
        
        <div class="p-6 flex-1 flex flex-col">
          <div class="mb-6 pb-6 border-b border-gray-100">
            <h4 class="font-bold text-lg text-[#0054A6] mb-1"><?php echo htmlspecialchars($klaim['nama_barang']); ?></h4>
            <p class="text-sm text-gray-600 mb-3"><i class="fas fa-map-marker-alt text-[#E32227] mr-1"></i> Ditemukan di <?php echo htmlspecialchars($klaim['lokasi_ditemukan']); ?></p>
            
            <p class="text-xs text-gray-500 mb-1">Catatan Awal Petugas:</p>
            <p class="text-sm text-gray-800 bg-blue-50 border-l-4 border-[#0054A6] pl-3 py-1">
              <?php echo htmlspecialchars($klaim['deskripsi_petugas']); ?>
            </p>
          </div>

          <form action="proses_verif.php" method="POST" class="mt-auto">
            <input type="hidden" name="id_klaim" value="<?php echo $klaim['id_klaim']; ?>">
            <input type="hidden" name="id_temuan" value="<?php echo $klaim['id_temuan']; ?>">
            
            <div class="mb-5">
              <label for="keputusan" class="block text-sm font-bold text-gray-700 mb-2">Keputusan Verifikasi <span class="text-[#E32227]">*</span></label>
              <div class="relative">
                <select id="keputusan" name="keputusan" required class="w-full pl-4 pr-10 py-3 bg-white border border-gray-300 rounded-md text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#0054A6] appearance-none cursor-pointer font-bold">
                  <option value="">-- Pilih Keputusan --</option>
                  <option value="Disetujui">DISETUJUI (Barang diserahkan ke penumpang)</option>
                  <option value="Ditolak">DITOLAK (Bukti tidak cocok / mencurigakan)</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500"><i class="fas fa-chevron-down"></i></div>
              </div>
            </div>

            <div class="mb-6">
              <label for="catatan_petugas" class="block text-sm font-bold text-gray-700 mb-2">Catatan Penyerahan / Penolakan</label>
              <textarea id="catatan_petugas" name="catatan_petugas" rows="3" placeholder="Contoh: Barang diserahkan bersama KTP penumpang. / Ciri-ciri tidak cocok dengan fisik..." class="w-full px-4 py-2 bg-white border border-gray-300 rounded-md text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#0054A6]"></textarea>
            </div>

            <div class="flex items-center justify-end space-x-3">
              <a href="/krl/core/dashboard_petugas.php" class="px-5 py-2.5 border border-gray-300 text-gray-700 text-sm font-semibold rounded-md hover:bg-gray-50 transition">Batal</a>
              <button type="submit" class="px-5 py-2.5 bg-[#0054A6] text-white text-sm font-bold rounded-md hover:bg-blue-800 transition flex items-center shadow-md">
                <i class="fas fa-save mr-2"></i> Proses Keputusan
              </button>
            </div>
          </form>

        </div>
      </div>

    </div>
  </div>

<?php 
$stmt->close();
?>