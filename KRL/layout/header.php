<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>KRL Lost & Found</title>
  
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  
  <link rel="stylesheet" href="style.css" />
</head>
<body class="font-sans bg-slate-50 text-gray-800" id="home">

  <nav class="fixed w-full bg-white shadow-md z-50 border-b-4 border-[#E32227]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-20">
        <div class="flex-shrink-0 flex items-center space-x-2">
          <span class="text-xl font-bold text-[#0054A6]">KRL Lost & Found</span>
        </div>
        <div class="hidden md:flex items-center space-x-8">
          <a href="index.php#home" class="menu-item text-[#0054A6] hover:text-[#E32227] font-medium">Beranda</a>
          <a href="index.php#tentang" class="menu-item text-[#0054A6] hover:text-[#E32227] font-medium">Tentang Kami</a>
          <div class="auth-buttons flex items-center ml-6 space-x-4">
            <a href="auth/login.php" class="login px-4 py-2 border border-[#0054A6] text-[#0054A6] rounded-full text-sm font-bold hover:bg-[#0054A6] hover:text-white transition duration-300">Login</a>
            <a href="auth/login.php" class="register px-4 py-2 bg-[#E32227] text-white rounded-full text-sm font-bold hover:bg-red-700 transition duration-300">Lapor Kehilangan</a>
          </div>
        </div>
        <div class="md:hidden flex items-center">
          <button id="mobile-menu-button" class="text-[#0054A6] hover:text-[#E32227] focus:outline-none">
            <i class="fas fa-bars text-2xl"></i>
          </button>
        </div>
      </div>
    </div>

    <div id="mobile-menu" class="mobile-menu md:hidden fixed inset-y-0 right-0 w-64 bg-white shadow-lg border-l-4 border-[#E32227]">
      <div class="flex items-center justify-between px-6 pt-6 pb-4 border-b">
        <div class="flex items-center space-x-2">
          <i class="fas fa-train text-[#E32227]"></i>
          <span class="text-lg font-bold text-[#0054A6]">Lost & Found</span>
        </div>
        <button id="close-mobile-menu" class="text-[#0054A6] hover:text-[#E32227] focus:outline-none">
          <i class="fas fa-times text-xl"></i>
        </button>
      </div>
      <div class="px-6 py-4">
        <div class="flex flex-col space-y-6">
          <a href="index.php#home" class="menu-item text-[#0054A6] hover:text-[#E32227] font-medium block">Beranda</a>
          <a href="index.php#tentang" class="menu-item text-[#0054A6] hover:text-[#E32227] font-medium block">Tentang Kami</a>
          <a href="index.php#kontak" class="menu-item text-[#0054A6] hover:text-[#E32227] font-medium block">Pusat Bantuan</a>
          <div class="pt-4 border-t">
            <a href="login.php" class="block w-full text-center px-4 py-2 border border-[#0054A6] text-[#0054A6] rounded-full text-sm font-bold mb-3">Login Admin</a>
            <a href="lapor.php" class="block w-full text-center px-4 py-2 bg-[#E32227] text-white rounded-full text-sm font-bold">Lapor Kehilangan</a>
          </div>
        </div>
      </div>
    </div>
  </nav>