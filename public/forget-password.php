<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Forgot Password</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white flex items-center justify-center min-h-screen">
  <div class="bg-gray-800 p-8 rounded-lg shadow-md w-full max-w-md">
    <h2 class="text-2xl font-bold text-center mb-6">Reset Password</h2>

    <?php if (isset($error)): ?>
      <p class="text-red-500 text-center mb-4"><?php echo $error; ?></p>
    <?php elseif (isset($success)): ?>
      <p class="text-green-500 text-center mb-4"><?php echo $success; ?></p>
    <?php endif; ?>

    <form method="POST" action="">
      <label class="block mb-2">Email:</label>
      <input type="email" name="email" required class="w-full p-3 mb-4 rounded text-black">

      <label class="block mb-2">New Password:</label>
      <input type="password" name="new_password" required class="w-full p-3 mb-4 rounded text-black">

      <label class="block mb-2">Retype New Password:</label>
      <input type="password" name="retype_password" required class="w-full p-3 mb-6 rounded text-black">

      <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded">
        Reset Password
      </button>
    </form>

    <div class="text-center mt-4">
      <a href="/login" class="text-blue-400 hover:underline">Back to Login</a>
    </div>
  </div>
</body>
</html>
