<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo isset($page_title) ? $page_title : 'Admin Panel - KRL'; ?></title>
  
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="style.css" />
</head>
<body class="bg-slate-50 font-sans text-gray-800 relative min-h-screen">

  <nav class="bg-[#0054A6] text-white shadow-md sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex items-center space-x-3">
          <span class="font-bold text-xl tracking-wide">Admin Panel KRL</span>
        </div>
        <div class="flex items-center space-x-4">
          <span class="text-sm font-medium hidden sm:block">Halo, <?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?>!</span>
          <a href="/krl/auth/logout.php" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition shadow-sm">
            <i class="fas fa-sign-out-alt mr-1"></i> Keluar
          </a>
        </div>
      </div>
    </div>
  </nav>