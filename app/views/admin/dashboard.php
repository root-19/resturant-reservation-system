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

    <!-- suer Links -->
    <?php if ($_SESSION['role'] === 'admin'): ?>
      <nav class="flex flex-col space-y-4">
        <a href="/admin/dashbaord" class="hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium">Admin Home</a>
        <a href="/admin/create-menu" class="hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium">Create menu</a>
        <a href="/admin/menu-list" class="hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium">Menu-list</a>

        <a href="/admin/reservation-list" class="hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium">reservation</a>
        <a href="/logout" class="hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium">logout</a>  
    </nav>
    <?php endif; ?>
  </div>

  <!-- Main Content -->
  <div class="flex-1 p-10">
    

</body>
</html>
