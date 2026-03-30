<?php 
// Memanggil CSS dan JS khusus untuk halaman Index (sesuai trik modular kita)
$page_css = "css/index_utama.css"; 
$page_js = "js/index_utama.js"; 

include 'layout/header.php'; 
?>

  <div class="pt-24 pb-16 md:pt-36 md:pb-28 bg-cover bg-center relative" style="background-image: url('/krl/assets/kereta.jpeg');">
    <div class="absolute inset-0 bg-[#0054A6] bg-opacity-75"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      <div class="max-w-3xl">
        <span class="inline-block py-1.5 px-4 rounded-md bg-[#E32227] text-white text-xs font-bold mb-5 tracking-wider uppercase border border-red-400">
          Layanan Pengguna KRL
        </span>
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-6 leading-tight">Pusat Informasi Barang Hilang & Ditemukan</h1>
        <p class="text-lg text-blue-50 mb-8 border-l-4 border-[#E32227] pl-4">
          Kehilangan barang berharga Anda di stasiun atau di dalam rangkaian kereta Commuter Line? Jangan panik, laporkan kepada kami dan pantau status pencariannya di sini.
        </p>
        <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
          <a href="auth/login.php" class="inline-flex items-center justify-center px-6 py-3 bg-[#E32227] text-white rounded-md font-semibold hover:bg-red-700 transition duration-300 shadow-sm border border-transparent">
            <i class="fas fa-bullhorn mr-2"></i> Lapor Barang Hilang
          </a>
        </div>
      </div>
    </div>
  </div>

  <section id="tentang" class="py-16 md:py-24 bg-slate-50 border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      
      <div class="mb-12 text-center md:text-left">
         <h2 class="text-3xl font-bold text-[#0054A6] mb-2">Mengapa Menggunakan Layanan Ini?</h2>
         <div class="w-16 h-1 bg-[#E32227] mx-auto md:mx-0 mb-8 rounded-full"></div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div>
          <div class="bg-white p-2 rounded-lg border border-gray-200 shadow-sm">
            <img src="/krl/assets/kota.jpeg" alt="Stasiun Kereta Api" class="rounded-md w-full h-auto">
          </div>
        </div>
        
        <div>
          <h3 class="text-2xl font-bold text-gray-900 mb-4">Terintegrasi Seluruh Stasiun Commuter Line</h3>
          <p class="text-gray-600 mb-4 leading-relaxed text-sm">
            Aplikasi <strong>Lost & Found KRL</strong> mengumpulkan berbagai informasi barang tertinggal yang diamankan oleh petugas kami (Passenger Service) di seluruh stasiun dan di dalam rangkaian kereta Commuter Line.
          </p>
          <p class="text-gray-600 mb-8 leading-relaxed text-sm">
            Kami berkomitmen untuk membantu mempertemukan kembali barang berharga Anda secara transparan, cepat, dan aman tanpa dipungut biaya apapun.
          </p>
          
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            
            <div class="flex items-start p-4 bg-white border border-gray-200 rounded-md shadow-sm hover:border-[#0054A6] transition">
              <div class="bg-blue-50 p-2.5 rounded-md border border-blue-100 mr-3">
                <i class="fas fa-train-subway text-[#0054A6]"></i>
              </div>
              <div>
                <h4 class="font-bold text-gray-900 text-sm">Cakupan Luas</h4>
                <p class="text-xs text-gray-500 mt-0.5">Terhubung di 80+ stasiun</p>
              </div>
            </div>

            <div class="flex items-start p-4 bg-white border border-gray-200 rounded-md shadow-sm hover:border-red-400 transition">
              <div class="bg-red-50 p-2.5 rounded-md border border-red-100 mr-3">
                <i class="fas fa-shield-halved text-[#E32227]"></i>
              </div>
              <div>
                <h4 class="font-bold text-gray-900 text-sm">Terverifikasi</h4>
                <p class="text-xs text-gray-500 mt-0.5">Proses klaim yang ketat</p>
              </div>
            </div>

            <div class="flex items-start p-4 bg-white border border-gray-200 rounded-md shadow-sm hover:border-red-400 transition">
              <div class="bg-red-50 p-2.5 rounded-md border border-red-100 mr-3">
                <i class="fas fa-clock-rotate-left text-[#E32227]"></i>
              </div>
              <div>
                <h4 class="font-bold text-gray-900 text-sm">Real-time</h4>
                <p class="text-xs text-gray-500 mt-0.5">Pantau status laporan</p>
              </div>
            </div>

            <div class="flex items-start p-4 bg-white border border-gray-200 rounded-md shadow-sm hover:border-[#0054A6] transition">
              <div class="bg-blue-50 p-2.5 rounded-md border border-blue-100 mr-3">
                <i class="fas fa-hand-holding-hand text-[#0054A6]"></i>
              </div>
              <div>
                <h4 class="font-bold text-gray-900 text-sm">Bebas Biaya</h4>
                <p class="text-xs text-gray-500 mt-0.5">100% layanan gratis</p>
              </div>
            </div>

          </div>
        </div>
      </div>

    </div>
  </section>

<?php include 'layout/footer.php'; ?>