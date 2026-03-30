const mobileMenuButton = document.getElementById('mobile-menu-button');
const closeMobileMenu = document.getElementById('close-mobile-menu');
const mobileMenu = document.getElementById('mobile-menu');
const backToTop = document.getElementById('backToTop');

// Menu Mobile
mobileMenuButton.addEventListener('click', () => {
  mobileMenu.classList.add('active');
  document.body.style.overflow = 'hidden';
});

closeMobileMenu.addEventListener('click', () => {
  mobileMenu.classList.remove('active');
  document.body.style.overflow = 'auto';
});

document.querySelectorAll('#mobile-menu a').forEach(link => {
  link.addEventListener('click', () => {
    mobileMenu.classList.remove('active');
    document.body.style.overflow = 'auto';
  });
});

// Smooth scroll
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    e.preventDefault();
    const targetId = this.getAttribute('href');
    if (targetId === '#home' || targetId === '#') {
      window.scrollTo({ top: 0, behavior: 'smooth' });
      return;
    }
    const target = document.querySelector(targetId);
    if (target) {
      const offsetTop = target.offsetTop - 80;
      window.scrollTo({ top: offsetTop, behavior: 'smooth' });
    }
  });
});

// Navbar shadow dan tombol back to top
window.addEventListener('scroll', () => {
  const nav = document.querySelector('nav');
  if (window.scrollY > 50) {
    nav.classList.add('shadow-lg');
    backToTop.classList.add('show');
  } else {
    nav.classList.remove('shadow-lg');
    backToTop.classList.remove('show');
  }
});

// Tombol back to top klik → scroll halus ke atas
backToTop.addEventListener('click', () => {
  window.scrollTo({ top: 0, behavior: 'smooth' });
});
/* --- EFEK HALAMAN REGISTER & LOGIN --- */

// Toggle Show/Hide Password di Halaman Login
const togglePassword = document.getElementById('togglePassword');
const passwordLogin = document.getElementById('password');

if (togglePassword && passwordLogin) {
  togglePassword.addEventListener('click', function () {
    const type = passwordLogin.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordLogin.setAttribute('type', type);
    this.querySelector('i').classList.toggle('fa-eye');
    this.querySelector('i').classList.toggle('fa-eye-slash');
  });
}

// Toggle Show/Hide Password di Halaman Registrasi (karena ada 2 form password)
const toggleRegPassButtons = document.querySelectorAll('.toggle-reg-pass');
toggleRegPassButtons.forEach(button => {
  button.addEventListener('click', function() {
    const input = this.previousElementSibling; // Mengambil input tag sebelum button
    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
    input.setAttribute('type', type);
    this.querySelector('i').classList.toggle('fa-eye');
    this.querySelector('i').classList.toggle('fa-eye-slash');
  });
});

// Validasi Form Registrasi (Cek Password & Nomor Telepon)
const registerForm = document.getElementById('registerForm');
const noTelpInput = document.getElementById('no_telp');

if (registerForm) {
  // Hanya izinkan angka untuk input nomor telepon
  noTelpInput.addEventListener('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '');
  });

  // Cek kecocokan konfirmasi password saat form di-submit
  registerForm.addEventListener('submit', function(e) {
    const pass = document.getElementById('reg_password');
    const confirmPass = document.getElementById('konfirmasi_password');
    const errorMsg = document.getElementById('passwordError');

    if (pass.value !== confirmPass.value) {
      e.preventDefault(); // Hentikan pengiriman data

      // Tampilkan pesan error dan efek getar
      errorMsg.classList.remove('hidden');
      confirmPass.classList.add('shake-error');
      
      // Hilangkan efek getar setelah animasi selesai agar bisa di-trigger ulang
      setTimeout(() => {
        confirmPass.classList.remove('shake-error');
      }, 500);
    } else {
      errorMsg.classList.add('hidden');
    }
  });
}

/* --- FITUR LIVE SEARCH (Pencarian Real-Time) --- */
document.addEventListener('DOMContentLoaded', () => {
  const searchInput = document.getElementById('searchInput');
  
  if (searchInput) {
    // Event 'keyup' akan merespon setiap kali kamu selesai menekan tombol di keyboard
    searchInput.addEventListener('keyup', function() {
      // Ubah ketikan menjadi huruf kecil semua agar pencarian tidak sensitif huruf besar/kecil
      const kataKunci = this.value.toLowerCase();
      
      // Ambil semua baris (tr) yang ada di dalam badan tabel (tbody)
      const barisTabel = document.querySelectorAll('tbody tr');

      barisTabel.forEach(baris => {
        // Abaikan baris jika itu adalah pesan "Barang tidak ditemukan" (hanya 1 kolom)
        if (baris.cells.length === 1) return;

        // Ambil seluruh teks yang ada di dalam baris tersebut
        const teksBaris = baris.textContent.toLowerCase();
        
        // Jika ada kata yang cocok, tampilkan barisnya. Jika tidak, sembunyikan!
        if (teksBaris.includes(kataKunci)) {
          baris.style.display = '';
        } else {
          baris.style.display = 'none';
        }
      });
    });
  }
});


