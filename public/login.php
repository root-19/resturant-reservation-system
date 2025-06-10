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
    .animate-pop {
      animation: popIn 0.45s cubic-bezier(0.4, 0, 0.2, 1);
    }
    @keyframes popIn {
      0% {
        opacity: 0;
        transform: scale(0.85) translateY(40px);
      }
      100% {
        opacity: 1;
        transform: scale(1) translateY(0);
      }
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body class="bg-gradient-to-br from-pink-50 to-red-50 min-h-screen font-sans">

  <!-- Main Content Wrapper (for easy hiding) -->
  <div id="mainContent">
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
              src="../resources/image/meu.jpg" 
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

    <!-- Footer Section -->
    <footer class="bg-gradient-to-r from-red-600 to-red-700 text-white mt-20">
      <div class="container mx-auto px-6 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <!-- Contact Information -->
          <div class="space-y-4">
            <h3 class="text-xl font-bold mb-4">Contact Us</h3>
            <div class="flex items-center space-x-3">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
              </svg>
              <span>+1 (555) 123-4567</span>
            </div>
            <div class="flex items-center space-x-3">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
              </svg>
              <span>info@misakibistro.com</span>
            </div>
            <div class="flex items-center space-x-3">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
              </svg>
              <span>Unit 14-16, Little Tokyo, 2277 Don Chino Roces Avenue, Makati</span>
            </div>
          </div>

          <!-- Opening Hours -->
          <div class="space-y-4">
            <h3 class="text-xl font-bold mb-4">Opening Hours</h3>
            <div class="space-y-2">
              <p>Monday - Friday: 11:00 AM - 10:00 PM</p>
              <p>Saturday: 10:00 AM - 11:00 PM</p>
              <p>Sunday: 10:00 AM - 9:00 PM</p>
            </div>
          </div>

          <!-- Social Media -->
          <div class="space-y-4">
            <h3 class="text-xl font-bold mb-4">Follow Us</h3>
            <div class="flex space-x-4">
              <a href="#" class="hover:text-red-200 transition-colors duration-300">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                </svg>
              </a>
              <a href="#" class="hover:text-red-200 transition-colors duration-300">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                </svg>
              </a>
              <a href="https://www.instagram.com/misakiphilippines/" class="hover:text-red-200 transition-colors duration-300">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/>
                </svg>
              </a>
            </div>
          </div>
        </div>

        <!-- Copyright -->
        <div class="border-t border-red-500 mt-8 pt-8 text-center">
          <p>&copy; 2024 Misaki Bistro. All rights reserved.</p>
        </div>
      </div>
    </footer>
  </div>

  <!-- Login Form Overlay -->
  <div id="loginOverlay" class="hidden fixed inset-0 bg-black bg-opacity-60 z-40"></div>

  <!-- Login Form -->
  <div id="loginForm" class="hidden fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white w-full max-w-md p-10 rounded-2xl shadow-2xl z-50 animate-pop">
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
      document.getElementById('mainContent').classList.add('hidden');
      document.getElementById('loginForm').classList.remove('hidden');
      document.getElementById('loginOverlay').classList.remove('hidden');
    }

    function src() {
      window.location.href = "/menu";
    }
  </script>

</body>
</html>
