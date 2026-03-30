<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo isset($page_title) ? $page_title : 'Portal Penumpang - KRL Lost & Found'; ?></title>
  
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  
  <?php if(isset($page_css)): ?>
    <link rel="stylesheet" href="<?php echo $page_css; ?>" />
  <?php endif; ?>
</head>
<body class="bg-slate-50 font-sans text-gray-800 relative min-h-screen">

  <nav class="bg-[#0054A6] text-white shadow-md sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        
        <div class="flex items-center space-x-3">
          <a href="/krl/core/index.php" class="flex items-center space-x-2 hover:opacity-80 transition">
            <span class="font-bold text-xl tracking-wide">Portal Penumpang KRL</span>
          </a>
        </div>
        
        <div class="flex items-center space-x-3 sm:space-x-5">
          
          <a href="/krl/pelapor/my_profile.php" class="flex items-center space-x-2 hover:bg-blue-800 p-1.5 pr-3 rounded-full transition border border-transparent hover:border-blue-400">
            <div class="bg-white text-[#0054A6] h-8 w-8 rounded-full flex items-center justify-center font-bold text-sm shadow-sm">
              <?php 
                // Mengambil huruf pertama dari Username (atau nama lengkap jika username tidak ada di session)
                $inisial = isset($_SESSION['username']) ? $_SESSION['username'] : $_SESSION['nama_lengkap'];
                echo strtoupper(substr($inisial, 0, 1)); 
              ?>
            </div>
            <span class="text-sm font-semibold hidden sm:block">
              <?php 
                echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'My Profile'; 
              ?>
            </span>
          </a>

          <div class="h-6 border-l border-blue-400 hidden sm:block"></div>

          <a href="/krl/auth/logout.php" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition shadow-sm flex items-center">
            <i class="fas fa-sign-out-alt sm:mr-1.5"></i> <span class="hidden sm:inline">Keluar</span>
          </a>

        </div>
        
      </div>
    </div>
  </nav>