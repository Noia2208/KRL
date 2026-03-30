<?php
session_start();

// Cek apakah yang masuk benar-benar pelapor (penumpang)
if (!isset($_SESSION['status']) || $_SESSION['status'] != "telah_login" || $_SESSION['role'] != 'pelapor') {
    header("Location: ../auth/login.php?pesan=belum_login");
    exit();
}

require_once __DIR__ . '/../config/database.php';
$id_pelapor = $_SESSION['id_user'];

// 1. Ambil data pencarian & kategori
$where_sql = "WHERE status_barang = 'Tersedia'"; 
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

$query_barang = "SELECT * FROM barang_temuan $where_sql ORDER BY waktu_ditemukan DESC LIMIT 20";
$result_barang = $conn->query($query_barang);

// 2. Ambil data "Klaim Saya" + CATATAN PETUGAS
$query_klaim = "SELECT k.*, b.nama_barang, b.lokasi_ditemukan, r.catatan_petugas 
                FROM klaim_barang k 
                JOIN barang_temuan b ON k.id_temuan = b.id_temuan 
                LEFT JOIN riwayat_penyerahan r ON k.id_klaim = r.id_klaim
                WHERE k.id_pelapor = ? 
                ORDER BY k.waktu_klaim DESC LIMIT 5";
$stmt_klaim = $conn->prepare($query_klaim);
$stmt_klaim->bind_param("i", $id_pelapor);
$stmt_klaim->execute();
$result_klaim = $stmt_klaim->get_result();

$page_title = "Dashboard Penumpang - KRL Lost & Found";
require_once __DIR__ . '/../pelapor/header.php';
?>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 pb-24">
    
    <div class="bg-[#0054A6] rounded-xl p-8 mb-8 text-white shadow-sm flex flex-col md:flex-row items-center justify-between relative overflow-hidden">
      <div class="absolute right-0 top-0 opacity-10">
        <i class="fas fa-train-subway text-9xl -mt-4 -mr-4"></i>
      </div>
      <div class="relative z-10 w-full md:w-auto mb-6 md:mb-0">
        <h1 class="text-3xl font-bold mb-2">Halo, <?php echo htmlspecialchars(explode(" ", $_SESSION['nama_lengkap'])[0]); ?>!</h1>
        <p class="text-blue-100 max-w-lg text-sm md:text-base leading-relaxed">
          Kehilangan sesuatu di perjalanan hari ini? Cari barang Anda di daftar temuan stasiun berdasarkan kategori atau kata kunci.
        </p>
      </div>
      
      <div class="relative z-10 w-full md:w-auto">
        <form action="" method="GET" class="flex flex-col sm:flex-row gap-3">
          
          <div class="relative w-full sm:w-auto">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
              <i class="fas fa-tags text-sm"></i>
            </span>
            <select name="kategori" onchange="this.form.submit()" class="w-full sm:w-40 pl-9 pr-8 py-3 bg-white text-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 shadow-lg appearance-none cursor-pointer text-sm font-medium border-0">
              <option value="">Kategori</option>
              <option value="Tas" <?php if($kat_val == 'Tas') echo 'selected'; ?>>Tas</option>
              <option value="Elektronik" <?php if($kat_val == 'Elektronik') echo 'selected'; ?>>Elektronik</option>
              <option value="Dokumen" <?php if($kat_val == 'Dokumen') echo 'selected'; ?>>Dokumen</option>
              <option value="Pakaian" <?php if($kat_val == 'Pakaian') echo 'selected'; ?>>Pakaian</option>
              <option value="Lainnya" <?php if($kat_val == 'Lainnya') echo 'selected'; ?>>Lainnya</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
              <i class="fas fa-chevron-down text-xs"></i>
            </div>
          </div>

          <div class="relative w-full sm:w-auto flex-1">
            <input type="text" name="cari" value="<?php echo htmlspecialchars($cari_val); ?>" placeholder="Ketik ciri barang/stasiun..." class="w-full sm:w-64 pl-10 pr-4 py-3 bg-white text-gray-800 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 shadow-lg text-sm border-0">
            <i class="fas fa-search absolute left-3.5 top-3.5 text-gray-400"></i>
          </div>

          <button type="submit" class="hidden sm:block bg-[#E32227] text-white px-5 py-3 rounded-md shadow-lg hover:bg-red-700 transition font-bold text-sm">
            Cari
          </button>
          
        </form>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      
      <div class="lg:col-span-2">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-xl font-bold text-gray-800 border-l-4 border-[#E32227] pl-3">Daftar Barang Ditemukan</h2>
          
          <?php if($cari_val != '' || $kat_val != ''): ?>
            <a href="/krl/core/index.php" class="text-sm font-semibold text-[#0054A6] hover:text-red-600 transition flex items-center bg-blue-50 px-3 py-1.5 rounded">
              <i class="fas fa-times mr-1.5"></i> Hapus Filter
            </a>
          <?php endif; ?>
        </div>

        <?php 
        if(isset($_GET['pesan'])){
          if($_GET['pesan'] == "klaim_sukses"){
            echo "<div class='bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md mb-6 flex items-center text-sm font-medium'><i class='fas fa-check-circle mr-3 text-lg'></i> Klaim berhasil diajukan! Menunggu verifikasi petugas.</div>";
          }
        }
        ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <?php
          if ($result_barang->num_rows > 0) {
              while($row = $result_barang->fetch_assoc()) {
                $waktu = date('d M Y, H:i', strtotime($row['waktu_ditemukan']));
                
                $icon = "fa-box";
                if($row['kategori'] == 'Dompet') $icon = "fa-wallet";
                if($row['kategori'] == 'Elektronik') $icon = "fa-laptop";
                if($row['kategori'] == 'Tas') $icon = "fa-briefcase";
                if($row['kategori'] == 'Dokumen') $icon = "fa-file-alt";
                if($row['kategori'] == 'Pakaian') $icon = "fa-tshirt";
          ?>
          
          <div class="bg-white border border-gray-200 rounded-lg p-5 hover:shadow-md transition duration-200 flex flex-col justify-between h-full">
            <div>
              <div class="flex justify-between items-start mb-3">
                <div class="bg-blue-50 text-[#0054A6] p-2 rounded-md">
                  <i class="fas <?php echo $icon; ?> text-lg"></i>
                </div>
              </div>
              
              <h3 class="font-bold text-gray-900 mb-1 line-clamp-1"><?php echo htmlspecialchars($row['nama_barang']); ?></h3>
              <p class="text-xs text-[#E32227] font-semibold mb-3"><i class="fas fa-map-marker-alt mr-1"></i> <?php echo htmlspecialchars($row['lokasi_ditemukan']); ?></p>
              
              <p class="text-sm text-gray-600 line-clamp-2 mb-4">
                <?php echo htmlspecialchars($row['deskripsi']); ?>
              </p>
            </div>
            
            <div class="pt-4 border-t border-gray-100 flex items-center justify-between mt-auto">
              <span class="text-xs text-gray-500"><i class="far fa-clock mr-1"></i> <?php echo $waktu; ?></span>
              <a href="/krl/pelapor/ajukan_klaim.php?id=<?php echo $row['id_temuan']; ?>" class="text-sm font-semibold text-[#0054A6] hover:text-blue-800 transition">
                Klaim Barang <i class="fas fa-arrow-right ml-1"></i>
              </a>
            </div>
          </div>

          <?php 
              }
          } else {
              echo "<div class='col-span-1 md:col-span-2 bg-white border border-gray-200 rounded-lg p-8 text-center'>
                      <div class='bg-gray-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4'>
                        <i class='fas fa-search text-2xl text-gray-400'></i>
                      </div>
                      <h3 class='font-bold text-gray-800 mb-1'>Belum Ada Barang yang Cocok</h3>
                      <p class='text-sm text-gray-500'>Petugas kami selalu memperbarui data. Silakan cek kembali nanti atau ubah kata pencarian Anda.</p>
                    </div>";
          }
          ?>
        </div>
      </div>

      <div class="lg:col-span-1">
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm sticky top-24">
          <div class="bg-gray-50 px-5 py-4 border-b border-gray-200 rounded-t-lg">
            <h3 class="font-bold text-gray-800"><i class="fas fa-clipboard-list text-[#0054A6] mr-2"></i> Klaim Aktif Saya</h3>
          </div>
          
          <div class="p-0">
            <?php
            if ($result_klaim->num_rows > 0) {
                echo '<ul class="divide-y divide-gray-100">';
                while($klaim = $result_klaim->fetch_assoc()) {
                  
                  $badge_color = "bg-yellow-50 text-yellow-700 border-yellow-200";
                  $icon_status = "fa-hourglass-half";
                  
                  if($klaim['status_klaim'] == 'Disetujui') {
                      $badge_color = "bg-green-50 text-green-700 border-green-200";
                      $icon_status = "fa-check-circle";
                  } else if($klaim['status_klaim'] == 'Ditolak') {
                      $badge_color = "bg-red-50 text-red-700 border-red-200";
                      $icon_status = "fa-times-circle";
                  }
            ?>
              <li class="hover:bg-slate-50 transition border-b border-gray-100 last:border-b-0">
                
                <div class="p-5 cursor-pointer flex flex-col" onclick="toggleKlaim(this)">
                  <div class="flex justify-between items-start mb-2">
                    <h4 class="font-bold text-sm text-gray-900 line-clamp-1 flex-1 pr-2 transition-colors duration-300 title-text"><?php echo htmlspecialchars($klaim['nama_barang']); ?></h4>
                    <span class="text-[10px] px-2 py-0.5 rounded border font-semibold whitespace-nowrap <?php echo $badge_color; ?>">
                      <i class="fas <?php echo $icon_status; ?> mr-1"></i> <?php echo $klaim['status_klaim']; ?>
                    </span>
                  </div>
                  <div class="flex justify-between items-center">
                    <p class="text-xs text-gray-500"><i class="fas fa-map-marker-alt text-gray-400 mr-1"></i> <?php echo htmlspecialchars($klaim['lokasi_ditemukan']); ?></p>
                    <i class="fas fa-chevron-down text-gray-400 text-xs transform transition-transform duration-300 chevron-icon"></i>
                  </div>
                </div>
                
                <div class="grid grid-rows-[0fr] transition-[grid-template-rows] duration-300 ease-in-out klaim-content">
                  <div class="overflow-hidden px-5">
                    <div class="pb-5 pt-1 border-t border-gray-100 mt-1">
                      <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2">Pesan dari Petugas:</p>
                      
                      <?php if (!empty($klaim['catatan_petugas'])): ?>
                        <div class="bg-gray-50 rounded-md p-3 text-sm text-gray-700 border border-gray-200 italic shadow-inner">
                          "<?php echo htmlspecialchars($klaim['catatan_petugas']); ?>"
                        </div>
                      <?php else: ?>
                        <?php if ($klaim['status_klaim'] == 'Ditolak'): ?>
                          <div class="bg-red-50 rounded-md p-3 text-sm text-red-700 border border-red-200">
                            <i class="fas fa-times-circle mr-1"></i> Klaim Anda ditolak karena bukti/ciri-ciri tidak sesuai dengan fisik barang. Silahkan datang ke Layanan Lost & Found terdekat.
                          </div>
                        <?php else: ?>
                          <div class="bg-gray-50 rounded-md p-3 text-sm text-gray-500 border border-gray-200">
                            Belum ada catatan. Harap tunggu proses verifikasi maksimal 2x24 jam.
                          </div>
                        <?php endif; ?>
                      <?php endif; ?>

                      <p class="text-[10px] text-gray-400 mt-2 text-right">Diajukan: <?php echo date('d M Y, H:i', strtotime($klaim['waktu_klaim'])); ?></p>
                    </div>
                  </div>
                </div>

              </li>

            <?php 
                }
                echo '</ul>';
            } else {
            ?>
              <div class="p-8 text-center">
                <i class="fas fa-inbox text-3xl text-gray-300 mb-3"></i>
                <p class="text-sm text-gray-500">Anda belum memiliki riwayat pengajuan klaim barang.</p>
              </div>
            <?php } ?>
          </div>
          
          <div class="bg-blue-50 p-4 border-t border-blue-100 rounded-b-lg">
             <div class="flex items-start">
               <i class="fas fa-headset text-[#0054A6] mt-1 mr-3"></i>
               <p class="text-xs text-blue-900 leading-relaxed">
                 Klik pada riwayat klaim untuk melihat balasan petugas. Hubungi <strong>Call Center 121</strong> jika butuh bantuan lebih lanjut.
               </p>
             </div>
          </div>

        </div>
      </div>

    </div>
  </div>

  <script>
    // FUNGSI ANIMASI YANG HILANG DI KODE SEBELUMNYA
    function toggleKlaim(element) {
      const content = element.nextElementSibling;
      const chevron = element.querySelector('.chevron-icon');
      const title = element.querySelector('.title-text');
      
      if (content.classList.contains('grid-rows-[0fr]')) {
        content.classList.replace('grid-rows-[0fr]', 'grid-rows-[1fr]');
        chevron.classList.add('rotate-180');
        title.classList.add('text-[#0054A6]');
      } else {
        content.classList.replace('grid-rows-[1fr]', 'grid-rows-[0fr]');
        chevron.classList.remove('rotate-180');
        title.classList.remove('text-[#0054A6]');
      }
    }
  </script>

  <?php include '../layout/footer.php'; ?>