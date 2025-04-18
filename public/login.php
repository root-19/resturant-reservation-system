<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Restaurant Booking System</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-pink-100 min-h-screen font-sans">

  <!-- Top Navbar -->
  <nav class="bg-red-600 text-white flex items-center justify-between px-6 py-4">
  <div class="flex items-center space-x-2 text-xl font-bold">
    <img src="../resources/image/logo.jpg" alt="Logo" class="h-8 w-8 rounded-full" />
    <span>MISAKI BISTRO</span>
  </div>
  <ul class="flex space-x-6 text-sm">
    <!-- <li><a href="#" class="hover:underline">Reservations</a></li>
    <li><a href="#" class="hover:underline">New Reservation</a></li>
    <li><a href="#" class="hover:underline">Search</a></li> -->
    <li><button onclick="goToLogin()" class="hover:underline">Login</button></li>
  </ul>
</nav>


  <!-- Hero Section (Text + Image) -->
  <div id="heroSection" class="flex flex-col md:flex-row items-center justify-between px-10 py-20 transition-all duration-500">
    <!-- Text Section -->
    <div class="md:w-1/2 text-center md:text-left mb-10 md:mb-0">
      <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight mt-20">
        STRAIGHT TO THE POINT<br />
        RESTAURANT BOOKING<br />
        SYSTEM
      </h1>

      <!-- Buttons -->
      <div class="mt-20 flex flex-col sm:flex-row justify-center md:justify-start space-y-4 sm:space-y-0 sm:space-x-4">
        <button onclick="src()" class="bg-black text-white px-6 py-3 rounded font-semibold hover:bg-gray-800 transition">
          New Reservation
        </button>
        <!-- <input type="text" placeholder="Search" class="px-6 py-3 rounded border border-gray-400 w-full sm:w-auto" /> -->
      </div>
    </div>

    <!-- Image Section -->
    <div class="md:w-1/2 flex justify-center">
      <img src="../resources/image/imagefront.webp" alt="Food Platter" class="max-w-sm rounded-lg shadow-lg" />
    </div>
  </div>

  <!-- Login Form -->
  <div id="loginForm" class="hidden absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-gray-800 text-white w-full max-w-md p-10 rounded-lg shadow-xl z-50">
    <h2 class="text-3xl font-bold mb-4 text-center">Login</h2>
    
    <!-- Error Message -->
    <?php if (isset($error)) { echo "<p class='text-red-500 text-center'>$error</p>"; } ?>

    <form method="POST" action="/login">
      <label class="block text-lg">Email:</label>
      <input type="email" name="email" class="w-full p-3 mb-4 border border-gray-300 rounded text-black" required>

      <label class="block text-lg">Password:</label>
      <input type="password" name="password" class="w-full p-3 mb-6 border border-gray-300 rounded text-black" required>
  
      <button type="submit" class="w-full bg-red-600 text-white py-3 rounded-full hover:bg-red-700 transition">
        Login
      </button>

      <div class="mb-6 text-right mt-5">
      <a href="/forget-password" class="text-sm text-blue-400 hover:underline">Forgot Password?</a>
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
