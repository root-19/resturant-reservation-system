<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin page</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"/>
</head>
<body class="bg-gray-100 font-sans flex">

  <!-- Sidebar -->
  <div class="w-64 h-screen bg-blue-500 text-white flex flex-col p-6 space-y-6">
    <div class="text-2xl font-bold">Hello, <?php echo $_SESSION['username']; ?></div>

    <?php if ($_SESSION['role'] === 'admin'): ?>
    <nav class="flex flex-col space-y-4 text-sm font-medium">
      <a href="/admin/dashboard" class="flex items-center space-x-2 hover:bg-blue-700 px-3 py-2 rounded-md">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7m-9 2v8m0 0H5a2 2 0 01-2-2v-5a2 2 0 012-2h3m6 7v-8m0 8h6a2 2 0 002-2v-5a2 2 0 00-2-2h-3" />
        </svg>
        <span>Home</span>
      </a>

      <a href="/admin/create-menu" class="flex items-center space-x-2 hover:bg-blue-700 px-3 py-2 rounded-md">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        <span>Create Menu</span>
      </a>

      <a href="/admin/menu-list" class="flex items-center space-x-2 hover:bg-blue-700 px-3 py-2 rounded-md">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
        </svg>
        <span>List of Menu</span>
      </a>

      <a href="/admin/reservation-list" class="flex items-center space-x-2 hover:bg-blue-700 px-3 py-2 rounded-md">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M3 11h18M5 19h14a2 2 0 002-2v-5H3v5a2 2 0 002 2z" />
        </svg>
        <span>Reservation</span>
      </a>

      <a href="/admin/resturant-map" class="flex items-center space-x-2 hover:bg-blue-700 px-3 py-2 rounded-md">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A2 2 0 013 15.382V5a2 2 0 011.553-1.954L9 2.382m0 0l6 3.176m0 0L21 5a2 2 0 011.553 1.954v10.382a2 2 0 01-1.553 1.954L15 20m0 0V9M9 2.382v17.236" />
        </svg>
        <span>Restaurant Map</span>
      </a>

      <a href="/admin/done-reservation" class="flex items-center space-x-2 hover:bg-blue-700 px-3 py-2 rounded-md">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <span>Done Reservation</span>
      </a>

      <a href="/logout" class="flex items-center space-x-2 hover:bg-blue-700 px-3 py-2 rounded-md">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a1 1 0 11-2 0v-1m0-4V9a1 1 0 112 0v1" />
        </svg>
        <span>Logout</span>
      </a>
    </nav>
    <?php endif; ?>
  </div>

  <!-- Main Content -->
  <div class="flex-1 p-10">


</body>
</html>
