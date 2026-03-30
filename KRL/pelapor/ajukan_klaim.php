<?php
session_start();

// Keamanan: Pastikan yang masuk adalah pelapor
if (!isset($_SESSION['status']) || $_SESSION['status'] != "telah_login" || $_SESSION['role'] != 'pelapor') {
    header("Location: ../auth/login.php?pesan=belum_login");
    exit();
}

require_once __DIR__ . '/../config/database.php';

// Pastikan ada ID barang yang dilempar dari URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php"); // Kembali ke dashboard jika tidak ada ID
    exit();
}

$id_temuan = $_GET['id'];

// Ambil data barang (Hanya izinkan barang yang statusnya masih 'Tersedia')
$query = "SELECT * FROM barang_temuan WHERE id_temuan = ? AND status_barang = 'Tersedia'";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_temuan);
$stmt->execute();
$result = $stmt->get_result();

// Jika barang tidak ada atau sudah diklaim orang lain
if ($result->num_rows == 0) {
    echo "<script>alert('Maaf, barang ini tidak ditemukan atau sudah dalam proses klaim oleh pihak lain.'); window.location.href='index.php';</script>";
    exit();
}

$barang = $result->fetch_assoc();
$waktu_ditemukan = date('d F Y, H:i', strtotime($barang['waktu_ditemukan']));

$page_title = "Ajukan Klaim - KRL Lost & Found";
// Sesuaikan path header kamu (contoh ini menggunakan ../pelapor/header.php)
require_once __DIR__ . '/../pelapor/header.php'; 
?>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 pb-24">
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900 mb-2">Formulir Pengajuan Klaim</h1>
      <p class="text-gray-600">Mohon isi bukti kepemilikan dengan jujur dan sedetail mungkin agar petugas dapat memverifikasi klaim Anda.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      
      <div class="lg:col-span-1">
        <div class="bg-blue-50 border border-blue-100 rounded-xl p-6 shadow-sm sticky top-24">
          <h3 class="text-sm font-bold text-[#0054A6] uppercase tracking-wider mb-4 border-b border-blue-200 pb-2">Detail Barang Temuan</h3>
          
          <div class="space-y-4">
            <div>
              <p class="text-xs text-gray-500 mb-1">Nama Barang</p>
              <p class="font-bold text-gray-900"><?php echo htmlspecialchars($barang['nama_barang']); ?></p>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
              <div>
                <p class="text-xs text-gray-500 mb-1">Kategori</p>
                <p class="font-medium text-gray-800 flex items-center">
                  <i class="fas fa-tag text-[#0054A6] mr-2 text-xs"></i> <?php echo htmlspecialchars($barang['kategori']); ?>
                </p>
              </div>
              <div>
                <p class="text-xs text-gray-500 mb-1">ID Register</p>
                <p class="font-medium text-gray-800">#BRG-<?php echo $barang['id_temuan']; ?></p>
              </div>
            </div>

            <div>
              <p class="text-xs text-gray-500 mb-1">Lokasi Ditemukan</p>
              <p class="font-medium text-gray-800 flex items-center">
                <i class="fas fa-map-marker-alt text-[#E32227] mr-2 text-xs"></i> <?php echo htmlspecialchars($barang['lokasi_ditemukan']); ?>
              </p>
            </div>

            <div>
              <p class="text-xs text-gray-500 mb-1">Waktu Ditemukan</p>
              <p class="font-medium text-gray-800 flex items-center">
                <i class="far fa-clock text-gray-400 mr-2 text-xs"></i> <?php echo $waktu_ditemukan; ?> WIB
              </p>
            </div>

            <div>
              <p class="text-xs text-gray-500 mb-1">Catatan Petugas</p>
              <div class="bg-white p-3 rounded-md border border-gray-200 text-sm text-gray-600 italic">
                "<?php echo htmlspecialchars($barang['deskripsi']); ?>"
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="lg:col-span-2">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
          <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex items-center">
            <i class="fas fa-shield-alt text-[#E32227] text-xl mr-3"></i>
            <div>
              <h3 class="font-bold text-gray-800">Bukti Kepemilikan</h3>
              <p class="text-xs text-gray-500 mt-0.5">Hanya pemilik sah yang dapat mengambil barang ini.</p>
            </div>
          </div>

          <form action="proses_klaim.php" method="POST" class="p-6">
            <input type="hidden" name="id_temuan" value="<?php echo $barang['id_temuan']; ?>">

            <div class="mb-6">
              <label for="bukti_kepemilikan" class="block text-sm font-bold text-gray-700 mb-2">
                Deskripsikan Ciri-ciri Khusus Barang Anda <span class="text-[#E32227]">*</span>
              </label>
              <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 mb-3 text-sm text-yellow-800 rounded-r-md">
                <strong>Tips:</strong> Sebutkan ciri fisik yang tidak disebutkan petugas di samping (misal: isi di dalam tas, merk spesifik, warna lapisan dalam, nomor seri, ada goresan di sudut kiri, dll).
              </div>
              <textarea id="bukti_kepemilikan" name="bukti_kepemilikan" rows="6" required placeholder="Contoh: Di dalam dompet terdapat KTP atas nama Budi Santoso dan kartu ATM Bank Mandiri. Terdapat lecet kecil di ujung kanan bawah dompet..." class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#0054A6] focus:border-transparent transition-all shadow-sm"></textarea>
            </div>

            <div class="mb-8 flex items-start">
              <div class="flex items-center h-5">
                <input id="persetujuan" type="checkbox" required class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-[#0054A6]">
              </div>
              <label for="persetujuan" class="ml-2 text-sm text-gray-600 leading-relaxed">
                Saya menyatakan bahwa barang tersebut benar milik saya. Jika di kemudian hari terbukti saya memberikan keterangan palsu, saya bersedia diproses sesuai ketentuan hukum yang berlaku oleh pihak KAI Commuter.
              </label>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
              <a href="index.php" class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 text-sm font-bold rounded-lg hover:bg-gray-50 transition shadow-sm">
                Batal
              </a>
              <button type="submit" class="px-6 py-2.5 bg-[#0054A6] text-white text-sm font-bold rounded-lg hover:bg-blue-800 transition shadow-sm flex items-center">
                Kirim Pengajuan Klaim <i class="fas fa-paper-plane ml-2"></i>
              </button>
            </div>
          </form>

        </div>
      </div>

    </div>
  </div>

<?php 
$stmt->close();
include '../layout/footer.php'; 
?>