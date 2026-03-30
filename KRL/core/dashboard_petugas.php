<?php
session_start();

// Cek apakah yang masuk benar-benar petugas
if (!isset($_SESSION['status']) || $_SESSION['status'] != "telah_login" || $_SESSION['role'] != 'petugas') {
    header("Location: ../auth/login.php?pesan=belum_login"); 
    exit();
}

// Panggil koneksi database
require_once __DIR__ . '/../config/database.php';

// ==========================================
// LOGIKA PENCARIAN & FILTER KATEGORI
// ==========================================
$where_sql = "WHERE 1=1"; 
$cari_val = "";
$kat_val = "";

if (isset($_GET['cari']) && $_GET['cari'] != '') {
    $cari_val = $conn->real_escape_string($_GET['cari']);
    $where_sql .= " AND (nama_barang LIKE '%$cari_val%' OR lokasi_ditemukan LIKE '%$cari_val%')";
}

if (isset($_GET['kategori']) && $_GET['kategori'] != '') {
    $kat_val = $conn->real_escape_string($_GET['kategori']);
    $where_sql .= " AND kategori = '$kat_val'";
}

// ==========================================
// LOGIKA SORTING (PENGURUTAN KOLOM)
// ==========================================
$sort_val = isset($_GET['sort']) ? $_GET['sort'] : 'waktu_ditemukan';
$dir_val = isset($_GET['dir']) ? strtoupper($_GET['dir']) : 'DESC';

// Validasi Keamanan (Tambahan 'nama_barang' dan 'status_barang')
$allowed_sorts = ['id_temuan', 'nama_barang', 'waktu_ditemukan', 'status_barang'];
if (!in_array($sort_val, $allowed_sorts)) {
    $sort_val = 'waktu_ditemukan';
}
if ($dir_val != 'ASC' && $dir_val != 'DESC') {
    $dir_val = 'DESC'; // Default
}

// Eksekusi Query dengan CUSTOM SORT untuk kolom status
if ($sort_val == 'status_barang') {
    // Menggunakan fungsi FIELD() agar urutannya: Proses Klaim -> Tersedia -> Dikembalikan
    $query = "SELECT * FROM barang_temuan $where_sql ORDER BY FIELD(status_barang, 'Proses Klaim', 'Tersedia', 'Dikembalikan') $dir_val, waktu_ditemukan DESC";
} else {
    // Untuk ID, Nama Barang, dan Waktu menggunakan urutan bawaan SQL
    $query = "SELECT * FROM barang_temuan $where_sql ORDER BY $sort_val $dir_val";
}
$result = $conn->query($query);

// ==========================================
// FUNGSI BANTUAN UNTUK MEMBUAT LINK SORTING
// ==========================================
function buatLinkSort($kolom_target, $sort_sekarang, $dir_sekarang) {
    $params = $_GET; 
    
    if ($sort_sekarang == $kolom_target && $dir_sekarang == 'ASC') {
        $params['dir'] = 'DESC';
    } else {
        $params['dir'] = 'ASC';
    }
    $params['sort'] = $kolom_target;
    
    return "?" . http_build_query($params); 
}

function tampilIconSort($kolom_target, $sort_sekarang, $dir_sekarang) {
    if ($sort_sekarang == $kolom_target) {
        if ($dir_sekarang == 'ASC') {
            return '<i class="fas fa-sort-up ml-1.5 text-[#0054A6]"></i>';
        } else {
            return '<i class="fas fa-sort-down ml-1.5 text-[#0054A6]"></i>';
        }
    }
    return '<i class="fas fa-sort ml-1.5 text-gray-300 group-hover:text-gray-500 transition"></i>';
}

// ==========================================
// MEMANGGIL FILE HEADER.PHP
// ==========================================
$page_title = "Dashboard Petugas - KRL Lost & Found"; 
$page_css = "../css/dashboard.css"; 
require_once __DIR__ . '/../core_petugas/header.php'; 
?>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 pb-24">
    
    <div class="mb-6 flex flex-col lg:flex-row lg:items-end justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Data Barang Temuan</h1>
        <p class="text-gray-500 text-sm mt-1">Daftar semua barang penumpang yang ditemukan di area KRL.</p>
      </div>
      
      <form action="" method="GET" class="flex flex-col sm:flex-row items-center gap-3">
        
        <div class="relative w-full sm:w-auto">
          <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
            <i class="fas fa-tags text-sm"></i>
          </span>
          <select name="kategori" onchange="this.form.submit()" class="w-full pl-9 pr-8 py-2 bg-white border border-gray-300 rounded-md text-sm text-gray-700 focus:outline-none focus:ring-1 focus:ring-[#0054A6] focus:border-[#0054A6] appearance-none cursor-pointer transition shadow-sm">
            <option value="">Semua Kategori</option>
            <option value="Tas" <?php if($kat_val == 'Tas') echo 'selected'; ?>>Tas</option>
            <option value="Elektronik" <?php if($kat_val == 'Elektronik') echo 'selected'; ?>>Elektronik</option>
            <option value="Dokumen" <?php if($kat_val == 'Dokumen') echo 'selected'; ?>>Dokumen</option>
            <option value="Pakaian" <?php if($kat_val == 'Pakaian') echo 'selected'; ?>>Pakaian</option>
            <option value="Lainnya" <?php if($kat_val == 'Lainnya') echo 'selected'; ?>>Lainnya</option>
          </select>
          <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-400">
            <i class="fas fa-chevron-down text-xs"></i>
          </div>
        </div>

        <div class="relative w-full sm:w-auto">
          <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
            <i class="fas fa-search text-sm"></i>
          </span>
          <input type="text" id="searchInput" name="cari" value="<?php echo htmlspecialchars($cari_val); ?>" placeholder="Cari barang atau lokasi..." class="w-full pl-9 pr-4 py-2 bg-white border border-gray-300 rounded-md text-sm text-gray-800 focus:outline-none focus:ring-1 focus:ring-[#0054A6] focus:border-[#0054A6] transition shadow-sm">
        </div>

        <div class="flex items-center gap-2 w-full sm:w-auto">
          <button type="submit" class="w-full sm:w-auto px-5 py-2 bg-[#0054A6] text-white text-sm font-semibold rounded-md hover:bg-blue-800 transition shadow-sm flex items-center justify-center">
            Cari
          </button>
          
          <?php if($cari_val != '' || $kat_val != ''): ?>
            <a href="dashboard_petugas.php" class="w-full sm:w-auto px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-semibold rounded-md hover:bg-gray-50 transition shadow-sm flex items-center justify-center tooltip" data-tip="Reset Filter">
              <i class="fas fa-times"></i>
            </a>
          <?php endif; ?>
        </div>
      </form>
    </div>
    
    <?php 
    if(isset($_GET['pesan'])){
      if($_GET['pesan'] == "hapus_sukses"){
        echo "<div class='bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md mb-6 flex items-center text-sm font-medium'><i class='fas fa-check-circle mr-3 text-lg'></i> Data barang berhasil dihapus dari sistem!</div>";
      } else if($_GET['pesan'] == "hapus_gagal"){
        echo "<div class='bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md mb-6 flex items-center text-sm font-medium'><i class='fas fa-times-circle mr-3 text-lg'></i> Gagal menghapus data. Silakan coba lagi.</div>";
      } else if($_GET['pesan'] == "tambah_sukses"){
        echo "<div class='bg-blue-50 border border-blue-200 text-[#0054A6] px-4 py-3 rounded-md mb-6 flex items-center text-sm font-medium'><i class='fas fa-save mr-3 text-lg'></i> Data barang temuan baru berhasil disimpan!</div>";
      } else if($_GET['pesan'] == "edit_sukses"){
        echo "<div class='bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md mb-6 flex items-center text-sm font-medium'><i class='fas fa-edit mr-3 text-lg'></i> Perubahan data barang berhasil disimpan!</div>";
      } else if($_GET['pesan'] == "edit_gagal"){
        echo "<div class='bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md mb-6 flex items-center text-sm font-medium'><i class='fas fa-exclamation-triangle mr-3 text-lg'></i> Terjadi kesalahan saat mengupdate data.</div>";
      }
    }
    ?>

    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
      <div class="overflow-x-auto custom-scrollbar">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-gray-50 border-b border-gray-200 text-gray-600 text-xs uppercase tracking-wider">
              
              <th class="font-semibold group cursor-pointer hover:bg-gray-200 transition select-none" onclick="window.location.href='<?php echo buatLinkSort('id_temuan', $sort_val, $dir_val); ?>'">
                <div class="px-6 py-4 flex items-center">
                  ID <?php echo tampilIconSort('id_temuan', $sort_val, $dir_val); ?>
                </div>
              </th>

              <th class="font-semibold group cursor-pointer hover:bg-gray-200 transition select-none" onclick="window.location.href='<?php echo buatLinkSort('nama_barang', $sort_val, $dir_val); ?>'">
                <div class="px-6 py-4 flex items-center">
                  Nama Barang <?php echo tampilIconSort('nama_barang', $sort_val, $dir_val); ?>
                </div>
              </th>

              <th class="px-6 py-4 font-semibold">Kategori</th>
              <th class="px-6 py-4 font-semibold">Lokasi</th>
              
              <th class="font-semibold group cursor-pointer hover:bg-gray-200 transition select-none" onclick="window.location.href='<?php echo buatLinkSort('waktu_ditemukan', $sort_val, $dir_val); ?>'">
                <div class="px-6 py-4 flex items-center">
                  Waktu Ditemukan <?php echo tampilIconSort('waktu_ditemukan', $sort_val, $dir_val); ?>
                </div>
              </th>

              <th class="font-semibold group cursor-pointer hover:bg-gray-200 transition select-none" onclick="window.location.href='<?php echo buatLinkSort('status_barang', $sort_val, $dir_val); ?>'">
                <div class="px-6 py-4 flex items-center justify-center text-center">
                  Status <?php echo tampilIconSort('status_barang', $sort_val, $dir_val); ?>
                </div>
              </th>

              <th class="px-6 py-4 font-semibold text-center">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 text-sm">
            
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    
                    $status_bg = "bg-gray-100 text-gray-700 border border-gray-200"; 
                    if ($row['status_barang'] == 'Tersedia') {
                        $status_bg = "bg-green-50 text-green-700 border border-green-200";
                    } else if ($row['status_barang'] == 'Proses Klaim') {
                        $status_bg = "bg-yellow-50 text-yellow-700 border border-yellow-200";
                    } else if ($row['status_barang'] == 'Dikembalikan') {
                        $status_bg = "bg-blue-50 text-blue-700 border border-blue-200";
                    }

                    $waktu = date('d M Y, H:i', strtotime($row['waktu_ditemukan']));
            ?>
            <tr class="hover:bg-gray-50 transition-colors">
              <td class="px-6 py-4 font-medium text-gray-900">#BRG-<?php echo $row['id_temuan']; ?></td>
              <td class="px-6 py-4 font-medium text-gray-800"><?php echo htmlspecialchars($row['nama_barang']); ?></td>
              <td class="px-6 py-4 text-gray-600"><?php echo htmlspecialchars($row['kategori']); ?></td>
              <td class="px-6 py-4 text-gray-600"><?php echo htmlspecialchars($row['lokasi_ditemukan']); ?></td>
              <td class="px-6 py-4 text-gray-600"><?php echo $waktu; ?></td>
              <td class="px-6 py-4 text-center">
                <span class="px-2.5 py-1 rounded text-xs font-semibold whitespace-nowrap <?php echo $status_bg; ?>">
                  <?php echo $row['status_barang']; ?>
                </span>
              </td>
              <td class="px-6 py-4 flex justify-center space-x-2">
                
                <?php if ($row['status_barang'] == 'Proses Klaim'): ?>
                <a href="/krl/core_petugas/verifikasi.php?id=<?php echo $row['id_temuan']; ?>" class="p-1.5 px-2.5 text-yellow-600 hover:bg-yellow-50 border border-transparent hover:border-yellow-200 rounded transition tooltip relative" data-tip="Verifikasi Klaim">
                  <i class="fas fa-clipboard-check"></i>
                  <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                </a>
                <?php endif; ?>

                <a href="/krl/core_petugas/edit_barang.php?id=<?php echo $row['id_temuan']; ?>" class="p-1.5 px-2.5 text-blue-600 hover:bg-blue-50 border border-transparent hover:border-blue-200 rounded transition tooltip" data-tip="Edit Data">
                  <i class="fas fa-edit"></i>
                </a>
                <button onclick="hapusData(<?php echo $row['id_temuan']; ?>)" class="p-1.5 px-2.5 text-red-600 hover:bg-red-50 border border-transparent hover:border-red-200 rounded transition tooltip" data-tip="Hapus Data">
                  <i class="fas fa-trash-alt"></i>
                </button>
              </td>
            </tr>
            <?php 
                } 
            } else {
                echo "<tr>
                        <td colspan='7' class='px-6 py-12 text-center'>
                          <i class='fas fa-box-open text-4xl text-gray-300 mb-3 block'></i>
                          <p class='text-gray-500 font-medium'>Barang tidak ditemukan.</p>
                        </td>
                      </tr>";
            }
            ?>

          </tbody>
        </table>
      </div>
    </div>

  </div> 
  
  <a href="/krl/core_petugas/tambah_barang.php" class="fixed bottom-10 right-10 inline-flex items-center justify-center bg-[#0054A6] hover:bg-blue-800 text-white px-5 py-3 rounded-md shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 z-50 border border-blue-900">
    <i class="fas fa-plus mr-2"></i>
    <span class="font-bold text-sm">Tambah Data</span>
  </a>

  <script>
    function hapusData(id) {
        const konfirmasi = confirm("Apakah Anda yakin ingin menghapus data Barang ke-" + id + " ini?");
        if (konfirmasi) {
            window.location.href = "/krl/core_petugas/proses_hapus.php?id=" + id;
        }
    }
  </script>

<?php 
$page_js = "../js/dashboard.js"; 
include '../layout/footer.php'; 
?>