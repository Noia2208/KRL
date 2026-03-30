<footer class="bg-[#0054A6] text-white py-10 border-t-4 border-[#E32227]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center">
      <div class="mb-4 md:mb-0 text-center md:text-left">
        <h3 class="text-xl font-bold mb-1 flex items-center justify-center md:justify-start">
          <i></i> KRL Lost & Found
        </h3>
        <p class="text-blue-200 text-sm">Sistem Informasi Barang Hilang Commuter Line</p>
      </div>
      <div class="text-center md:text-right text-blue-200 text-sm">
        <p>© 2026 PT Kereta Commuter Indonesia.</p>
        <p>All rights reserved.</p>
      </div>
    </div>
  </footer>

  <button id="backToTop" class="fixed bottom-8 right-8 bg-[#E32227] text-white rounded-full w-12 h-12 flex items-center justify-center shadow-lg hover:bg-red-700 transition opacity-0 invisible z-50">
    <i class="fas fa-arrow-up"></i>
  </button>

  <script src="script.js"></script>
</body>
</html>

<script>
    // Fungsi untuk membuka/menutup riwayat klaim dengan mulus
    function toggleKlaim(element) {
      // Mengambil elemen-elemen yang dibutuhkan
      const content = element.nextElementSibling;
      const chevron = element.querySelector('.chevron-icon');
      const title = element.querySelector('.title-text');
      
      // Jika sedang tertutup (0fr), maka buka (1fr)
      if (content.classList.contains('grid-rows-[0fr]')) {
        content.classList.replace('grid-rows-[0fr]', 'grid-rows-[1fr]');
        chevron.classList.add('rotate-180');
        title.classList.add('text-[#0054A6]'); // Judul jadi biru saat dibuka
      } 
      // Jika sedang terbuka, maka tutup kembali
      else {
        content.classList.replace('grid-rows-[1fr]', 'grid-rows-[0fr]');
        chevron.classList.remove('rotate-180');
        title.classList.remove('text-[#0054A6]');
      }
    }
  </script>