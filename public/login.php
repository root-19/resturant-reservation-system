<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Misaki Bistro - Restaurant Booking System</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
    }
    .animate-fade-in {
      animation: fadeIn 0.5s ease-in;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body class="bg-gradient-to-br from-pink-50 to-red-50 min-h-screen font-sans">

  <!-- Top Navbar -->
  <nav class="bg-gradient-to-r from-red-600 to-red-700 text-white flex items-center justify-between px-6 py-4 shadow-lg">
    <div class="flex items-center space-x-3 text-xl font-bold">
      <img src="../resources/image/logo.jpg" alt="Logo" class="h-10 w-10 rounded-full ring-2 ring-white" />
      <span class="tracking-wide">MISAKI BISTRO</span>
    </div>
    <ul class="flex space-x-8 text-sm">
      <li><button onclick="goToLogin()" class="hover:text-red-200 transition-colors duration-300 font-medium">Login</button></li>
    </ul>
  </nav>

  <!-- Hero Section -->
  <div id="heroSection" class="flex flex-col md:flex-row items-center justify-between px-10 py-20 transition-all duration-500 animate-fade-in">
    <!-- Text Section -->
    <div class="md:w-1/2 text-center md:text-left mb-10 md:mb-0">
      <h1 class="text-4xl md:text-6xl font-extrabold text-gray-900 leading-tight mt-20 mb-6">
        Experience Fine Dining<br />
        <span class="text-red-600">Made Simple</span>
      </h1>
      <p class="text-gray-600 text-lg mb-8 max-w-lg">
        Welcome to Misaki Bistro's reservation system. Book your table in seconds and enjoy our exquisite Japanese-French fusion cuisine in a sophisticated atmosphere.
      </p>
      <div class="space-y-4">
        <div class="flex items-center space-x-3">
          <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
          </svg>
          <span class="text-gray-700">Instant table confirmation</span>
        </div>
        <div class="flex items-center space-x-3">
          <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
          </svg>
          <span class="text-gray-700">Special occasion arrangements</span>
        </div>
        <div class="flex items-center space-x-3">
          <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
          </svg>
          <span class="text-gray-700">Exclusive member benefits</span>
        </div>
      </div>
    </div>

    <!-- Image Section -->
    <div class="md:w-1/2 flex justify-center relative group">
      <!-- Decorative Elements -->
      <div class="absolute -top-6 -left-6 w-24 h-24 bg-red-100 rounded-full opacity-50 group-hover:scale-110 transition-transform duration-500"></div>
      <div class="absolute -bottom-8 -right-8 w-32 h-32 bg-red-200 rounded-full opacity-30 group-hover:scale-110 transition-transform duration-500"></div>
      
      <!-- Main Image Container -->
      <div class="relative z-10">
        <!-- Image Frame -->
        <div class="relative overflow-hidden rounded-2xl shadow-2xl transform group-hover:scale-105 transition-all duration-500">
          <img 
            src="../resources/image/imagefront.webp" 
            alt="Food Platter" 
            class="max-w-lg w-full object-cover aspect-[4/3] transform group-hover:scale-110 transition-transform duration-700"
          />
          <!-- Image Overlay -->
          <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-black/10 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500">
            <!-- Hover Content -->
            <div class="absolute bottom-0 left-0 right-0 p-6 transform translate-y-full group-hover:translate-y-0 transition-transform duration-500">
              <h3 class="text-white text-xl font-bold mb-2">Experience Fine Dining</h3>
              <p class="text-white/90 text-sm">Discover our exquisite Japanese-French fusion cuisine</p>
              <button class="mt-4 bg-white text-red-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-50 transition-colors duration-300">
                View Menu
              </button>
            </div>
          </div>
        </div>
        
        <!-- Floating Info Card -->
        <div class="absolute -bottom-6 -right-6 bg-white p-4 rounded-xl shadow-lg transform group-hover:scale-105 group-hover:rotate-3 transition-all duration-300">
          <div class="flex items-center space-x-2">
            <div class="w-2 h-2 bg-red-600 rounded-full animate-pulse"></div>
            <span class="text-sm font-medium text-gray-700">Now Serving</span>
          </div>
          <p class="text-xs text-gray-500 mt-1">Fresh Daily Specials</p>
        </div>
      </div>

      <!-- Decorative Line -->
      <div class="absolute top-1/2 -left-12 w-24 h-0.5 bg-red-300 transform -rotate-45 group-hover:scale-110 group-hover:bg-red-400 transition-all duration-500"></div>
    </div>
  </div>

  <!-- Login Form -->
  <div id="loginForm" class="hidden absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white w-full max-w-md p-10 rounded-2xl shadow-2xl z-50 animate-fade-in">
    <div class="text-center mb-8">
      <h2 class="text-3xl font-bold text-gray-800">Welcome Back</h2>
      <p class="text-gray-600 mt-2">Sign in to your account</p>
    </div>
    
    <!-- Error Message -->
    <?php if (isset($error)) { echo "<p class='text-red-500 text-center bg-red-50 p-3 rounded-lg mb-4'>$error</p>"; } ?>

    <form method="POST" action="/login" class="space-y-6">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
        <input type="email" name="email" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-300" required>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
        <input type="password" name="password" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-300" required>
      </div>
  
      <button type="submit" class="w-full bg-gradient-to-r from-red-600 to-red-700 text-white py-3 rounded-lg hover:from-red-700 hover:to-red-800 transition-all duration-300 font-medium shadow-lg hover:shadow-xl">
        Sign In
      </button>
    
      <div class="flex justify-between items-center mt-6 text-sm">
        <a href="/register" class="text-red-600 hover:text-red-700 font-medium transition-colors duration-300">Create Account</a>
        <a href="/forget-password" class="text-gray-600 hover:text-gray-800 transition-colors duration-300">Forgot Password?</a>
      </div>
    </form>
  </div>

  <script>
    function goToLogin() {
      document.getElementById('heroSection').classList.add('hidden');
      document.getElementById('loginForm').classList.remove('hidden');
    }

    function src() {
      window.location.href = "/menu";
    }
  </script>

</body>
</html>
