<?php
session_start();

// Cek apakah yang masuk benar-benar petugas
if (!isset($_SESSION['status']) || $_SESSION['status'] != "telah_login" || $_SESSION['role'] != 'petugas') {
    header("Location: ../login.php?pesan=belum_login");
    exit();
}

// Set judul halaman dan panggil navbar atas
$page_title = "Tambah Data Barang - KRL Lost & Found";
require_once __DIR__ . '/../core_petugas/header.php';
?>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 pb-24">
    <div class="mb-8">
      <h1 class="text-2xl font-bold text-gray-900">Catat Barang Temuan Baru</h1>
      <p class="text-gray-500 mt-1 text-sm">Pastikan data diisi dengan detail dan akurat sesuai dengan kondisi fisik barang.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      
      <div class="lg:col-span-2">
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
          
          <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800"><i class="fas fa-edit text-[#0054A6] mr-2"></i> Formulir Pendataan</h3>
            <span class="text-xs font-medium bg-blue-100 text-blue-700 px-2 py-1 rounded">Wajib Diisi (*)</span>
          </div>
          
          <form action="/krl/core_petugas/proses_tambah.php" method="POST" class="p-6 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label for="nama_barang" class="block text-sm font-semibold text-gray-700 mb-1">Nama Barang <span class="text-[#E32227]">*</span></label>
                <input type="text" id="nama_barang" name="nama_barang" required placeholder="Contoh: Dompet Kulit Coklat" class="w-full px-4 py-2 bg-white border border-gray-300 rounded-md text-sm text-gray-800 focus:outline-none focus:ring-1 focus:ring-[#0054A6] focus:border-[#0054A6] transition">
              </div>

              <div>
                <label for="kategori" class="block text-sm font-semibold text-gray-700 mb-1">Kategori <span class="text-[#E32227]">*</span></label>
                <div class="relative">
                  <select id="kategori" name="kategori" required class="w-full px-4 py-2 bg-white border border-gray-300 rounded-md text-sm text-gray-800 focus:outline-none focus:ring-1 focus:ring-[#0054A6] focus:border-[#0054A6] transition appearance-none cursor-pointer">
                    <option value="" disabled selected>-- Pilih Kategori --</option>
                    <option value="Tas">Tas</option>
                    <option value="Elektronik">Elektronik</option>
                    <option value="Dokumen">Dokumen</option>
                    <option value="Pakaian">Pakaian</option>
                    <option value="Lainnya">Lainnya</option>
                  </select>
                  <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                    <i class="fas fa-chevron-down text-xs"></i>
                  </div>
                </div>
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label for="lokasi_ditemukan" class="block text-sm font-semibold text-gray-700 mb-1">Lokasi Ditemukan <span class="text-[#E32227]">*</span></label>
                <input type="text" id="lokasi_ditemukan" name="lokasi_ditemukan" required placeholder="Contoh: Gerbong 3, Stasiun Bogor" class="w-full px-4 py-2 bg-white border border-gray-300 rounded-md text-sm text-gray-800 focus:outline-none focus:ring-1 focus:ring-[#0054A6] focus:border-[#0054A6] transition">
              </div>

              <div>
                <label for="waktu_ditemukan" class="block text-sm font-semibold text-gray-700 mb-1">Waktu Ditemukan <span class="text-[#E32227]">*</span></label>
                <input type="datetime-local" id="waktu_ditemukan" name="waktu_ditemukan" required class="w-full px-4 py-2 bg-white border border-gray-300 rounded-md text-sm text-gray-800 focus:outline-none focus:ring-1 focus:ring-[#0054A6] focus:border-[#0054A6] transition">
              </div>
            </div>

            <div>
              <label for="deskripsi" class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi Detail <span class="text-[#E32227]">*</span></label>
              <textarea id="deskripsi" name="deskripsi" rows="5" required placeholder="Sebutkan ciri-ciri khusus barang (warna, merk, isi di dalamnya jika ada, dll)" class="w-full px-4 py-2 bg-white border border-gray-300 rounded-md text-sm text-gray-800 focus:outline-none focus:ring-1 focus:ring-[#0054A6] focus:border-[#0054A6] transition custom-scrollbar"></textarea>
            </div>

            <div class="pt-6 border-t border-gray-200 flex items-center justify-end space-x-3">
              <a href="/krl/core/dashboard_petugas.php" class="px-6 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-semibold rounded-md hover:bg-gray-50 transition">
                Batal
              </a>
              <button type="submit" class="px-6 py-2 bg-[#0054A6] text-white text-sm font-semibold rounded-md hover:bg-blue-800 transition flex items-center shadow-sm">
                <i class="fas fa-save mr-2"></i> Simpan Data
              </button>
            </div>

          </form>
        </div>
      </div>

      <div class="lg:col-span-1">
        <div class="bg-blue-50 border border-blue-100 rounded-lg p-6 sticky top-24">
          <h4 class="font-bold text-[#0054A6] mb-4 flex items-center text-lg">
            <i class="fas fa-clipboard-list mr-2"></i> SOP Pendataan
          </h4>
          
          <ul class="text-sm text-blue-900 space-y-4">
            <li class="flex items-start">
              <i class="fas fa-check-circle text-green-600 mt-1 mr-3"></i>
              <span><strong>Akurasi Lokasi:</strong> Pastikan menulis nomor gerbong dan stasiun tempat barang diamankan.</span>
            </li>
            <li class="flex items-start">
              <i class="fas fa-check-circle text-green-600 mt-1 mr-3"></i>
              <span><strong>Barang Berharga:</strong> Untuk dompet/tas, dokumentasikan isi barang secara garis besar tanpa membongkar hal privasi.</span>
            </li>
            <li class="flex items-start">
              <i class="fas fa-check-circle text-green-600 mt-1 mr-3"></i>
              <span><strong>Status Otomatis:</strong> Barang yang diinput di sini akan otomatis berstatus <span class="font-bold border-b border-blue-300">"Tersedia"</span> di sistem pelapor.</span>
            </li>
          </ul>

          <div class="mt-8 pt-6 border-t border-blue-200">
            <div class="flex items-center space-x-3">
              <div class="bg-white p-2 rounded-full border border-blue-200 text-[#0054A6]">
                <i class="fas fa-headset"></i>
              </div>
              <p class="text-xs text-blue-800 leading-tight">
                Butuh bantuan sistem? Hubungi IT Support Internal di <strong>Ext. 1024</strong>.
              </p>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  <script src="../script.js"></script>
</body>
</html>