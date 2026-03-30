<?php
session_start();

// Cek keamanan
if (!isset($_SESSION['status']) || $_SESSION['status'] != "telah_login" || $_SESSION['role'] != 'petugas') {
    header("Location: ../auth/login.php?pesan=belum_login"); // Path diperbaiki menyesuaikan folder auth
    exit();
}

require_once __DIR__ . '/../config/database.php';

// Pastikan ada ID yang dikirim
if (!isset($_GET['id'])) {
    header("Location: dashboard_petugas.php"); // Path diperbaiki
    exit();
}

$id_temuan = $_GET['id'];

// Ambil data barang yang mau diedit
$query = "SELECT * FROM barang_temuan WHERE id_temuan = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_temuan);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // Jika ID tidak ditemukan di database
    header("Location: dashboard_petugas.php");
    exit();
}

$data = $result->fetch_assoc();

// Format waktu dari MySQL (YYYY-MM-DD HH:MM:SS) menjadi format input HTML datetime-local (YYYY-MM-DDTHH:MM)
$waktu_format = date('Y-m-d\TH:i', strtotime($data['waktu_ditemukan']));

$page_title = "Edit Data Barang - KRL Lost & Found";
// Panggil header 
require_once __DIR__ . '/header.php'; 
?>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 pb-24">
    <div class="mb-8">
      <h1 class="text-2xl font-bold text-gray-900">Edit Data Barang ke-<?php echo $data['id_temuan']; ?></h1>
      <p class="text-gray-500 mt-1 text-sm">Perbarui informasi barang temuan atau ubah status pengembalian di sini.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      
      <div class="lg:col-span-2">
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
          
          <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800"><i class="fas fa-edit text-[#0054A6] mr-2"></i> Formulir Perubahan</h3>
          </div>
          
          <form action="proses_edit.php" method="POST" class="p-6 space-y-6">
            <input type="hidden" name="id_temuan" value="<?php echo $data['id_temuan']; ?>">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label for="nama_barang" class="block text-sm font-semibold text-gray-700 mb-1">Nama Barang <span class="text-[#E32227]">*</span></label>
                <input type="text" id="nama_barang" name="nama_barang" value="<?php echo htmlspecialchars($data['nama_barang']); ?>" required class="w-full px-4 py-2 bg-white border border-gray-300 rounded-md text-sm text-gray-800 focus:outline-none focus:ring-1 focus:ring-[#0054A6] transition">
              </div>

              <div>
                <label for="kategori" class="block text-sm font-semibold text-gray-700 mb-1">Kategori <span class="text-[#E32227]">*</span></label>
                <div class="relative">
                  <select id="kategori" name="kategori" required class="w-full px-4 py-2 bg-white border border-gray-300 rounded-md text-sm text-gray-800 focus:outline-none focus:ring-1 focus:ring-[#0054A6] appearance-none cursor-pointer">
                    <option value="Tas" <?php if($data['kategori'] == 'Tas') echo 'selected'; ?>>Tas</option>
                    <option value="Elektronik" <?php if($data['kategori'] == 'Elektronik') echo 'selected'; ?>>Elektronik</option>
                    <option value="Dokumen" <?php if($data['kategori'] == 'Dokumen') echo 'selected'; ?>>Dokumen</option>
                    <option value="Pakaian" <?php if($data['kategori'] == 'Pakaian') echo 'selected'; ?>>Pakaian</option>
                    <option value="Lainnya" <?php if($data['kategori'] == 'Lainnya') echo 'selected'; ?>>Lainnya</option>
                  </select>
                  <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500"><i class="fas fa-chevron-down text-xs"></i></div>
                </div>
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label for="lokasi_ditemukan" class="block text-sm font-semibold text-gray-700 mb-1">Lokasi Ditemukan <span class="text-[#E32227]">*</span></label>
                <input type="text" id="lokasi_ditemukan" name="lokasi_ditemukan" value="<?php echo htmlspecialchars($data['lokasi_ditemukan']); ?>" required class="w-full px-4 py-2 bg-white border border-gray-300 rounded-md text-sm text-gray-800 focus:outline-none focus:ring-1 focus:ring-[#0054A6] transition">
              </div>

              <div>
                <label for="waktu_ditemukan" class="block text-sm font-semibold text-gray-700 mb-1">Waktu Ditemukan <span class="text-[#E32227]">*</span></label>
                <input type="datetime-local" id="waktu_ditemukan" name="waktu_ditemukan" value="<?php echo $waktu_format; ?>" required class="w-full px-4 py-2 bg-white border border-gray-300 rounded-md text-sm text-gray-800 focus:outline-none focus:ring-1 focus:ring-[#0054A6] transition">
              </div>
            </div>

            <div>
              <label for="deskripsi" class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi Detail <span class="text-[#E32227]">*</span></label>
              <textarea id="deskripsi" name="deskripsi" rows="4" required class="w-full px-4 py-2 bg-white border border-gray-300 rounded-md text-sm text-gray-800 focus:outline-none focus:ring-1 focus:ring-[#0054A6] transition"><?php echo htmlspecialchars($data['deskripsi']); ?></textarea>
            </div>

            <div class="p-4 bg-blue-50 border border-blue-100 rounded-md">
               <label for="status_barang" class="block text-sm font-bold text-[#0054A6] mb-2"><i class="fas fa-info-circle mr-1"></i> Update Status Barang</label>
               <div class="relative">
                  <select id="status_barang" name="status_barang" required class="w-full px-4 py-2 bg-white border border-gray-300 rounded-md text-sm text-gray-800 focus:outline-none focus:ring-1 focus:ring-[#0054A6] appearance-none cursor-pointer font-medium">
                    
                    <option value="Tersedia" <?php if($data['status_barang'] == 'Tersedia') echo 'selected'; ?>>Tersedia (Belum ada yang klaim)</option>
                    
                    <?php if($data['status_barang'] == 'Proses Klaim'): ?>
                        <option value="Proses Klaim" selected>Proses Klaim (Sedang diverifikasi oleh sistem)</option>
                    <?php endif; ?>

                    <option value="Dikembalikan" <?php if($data['status_barang'] == 'Dikembalikan') echo 'selected'; ?>>Dikembalikan (Sudah diserahkan ke pemilik)</option>
                    
                  </select>
                  <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500"><i class="fas fa-chevron-down text-xs"></i></div>
               </div>
            </div>

            <div class="pt-6 border-t border-gray-200 flex items-center justify-end space-x-3">
              <a href="/krl/core/dashboard_petugas.php" class="px-6 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-semibold rounded-md hover:bg-gray-50 transition">
                Batal
              </a>
              <button type="submit" class="px-6 py-2 bg-[#0054A6] text-white text-sm font-semibold rounded-md hover:bg-blue-800 transition flex items-center shadow-sm">
                <i class="fas fa-save mr-2"></i> Simpan Perubahan
              </button>
            </div>

          </form>
        </div>
      </div>

      <div class="lg:col-span-1">
        <div class="bg-blue-50 border border-blue-100 rounded-lg p-6 sticky top-24">
          <h4 class="font-bold text-[#0054A6] mb-4 flex items-center text-lg">
            <i class="fas fa-exclamation-triangle mr-2"></i> Catatan Edit
          </h4>
          <ul class="text-sm text-blue-900 space-y-4">
            <li class="flex items-start">
              <i class="fas fa-check-circle text-green-600 mt-1 mr-3"></i>
              <span>Gunakan fitur edit ini hanya jika terdapat kesalahan ketik pada pendataan awal.</span>
            </li>
            <li class="flex items-start">
              <i class="fas fa-check-circle text-green-600 mt-1 mr-3"></i>
              <span><strong>Ubah Status:</strong> Jika barang sudah diserahkan kepada pemilik yang sah, pastikan status diubah menjadi <strong>Dikembalikan</strong>.</span>
            </li>
            <li class="flex items-start">
              <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
              <span><strong>Catatan:</strong> Anda tidak dapat mengubah status menjadi "Proses Klaim" secara manual. Status tersebut hanya terpicu otomatis dari aplikasi penumpang.</span>
            </li>
          </ul>
        </div>
      </div>

    </div>
  </div>

  <?php include '../layout/footer.php'; ?>