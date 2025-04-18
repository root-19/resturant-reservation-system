<?php

require_once __DIR__ . '/../controller/ReserveController.php';
require_once __DIR__ . '/../../config/database.php';

$pdo = Database::connect();
$controller = new ReservationController($pdo);
$controller->handleReservation();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reservation</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    function showStep2() {
      const selectedTableSize = document.getElementById('table_size_select').value;
      if (selectedTableSize === '') {
        alert('Please select a table size.');
        return;
      }
      document.getElementById('table_size_hidden').value = selectedTableSize;
      document.getElementById('step1').classList.add('hidden');
      document.getElementById('step2').classList.remove('hidden');
      document.getElementById('step-indicator-1').classList.add('text-gray-400', 'border-gray-300');
      document.getElementById('step-indicator-2').classList.remove('text-gray-400');
      document.getElementById('step-indicator-2').classList.add('text-red-600', 'border-red-600');
    }
  </script>
</head>
<body class="bg-gray-100 min-h-screen">
<?php if (isset($_SESSION['reservation_success'])): ?>
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Reservation Confirmed!',
      text: 'Your table has been reserved successfully.',
      confirmButtonColor: '#dc2626'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = 'logout'; 
      }
    });
  </script>
  <?php unset($_SESSION['reservation_success']); ?>
<?php endif; ?>


  <!-- Header -->
  <header class="bg-white shadow sticky top-0 z-10">
    <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
      <h1 class="text-xl font-bold text-red-700">Restaurant Reservation</h1>
      <a href="logout" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">Back to Home</a>
    </div>
  </header>

  <!-- Main Container -->
  <main class="flex justify-center items-start p-6 mt-4">
    <div class="bg-white shadow-xl rounded-xl w-full max-w-2xl p-8">

      <!-- Step Indicator -->
      <div class="flex justify-between items-center mb-8">
        <div class="w-full flex items-center">
          <div id="step-indicator-1" class="text-red-600 border-b-4 border-red-600 font-semibold px-4 py-2">Step 1: Table Size</div>
          <div class="w-6 border-b border-gray-300 mx-2"></div>
          <div id="step-indicator-2" class="text-gray-400 border-b-4 border-gray-300 font-semibold px-4 py-2">Step 2: Details</div>
        </div>
      </div>

      <!-- Step 1 -->
      <div id="step1">
        <form onsubmit="event.preventDefault(); showStep2();">
          <div class="mb-6">
            <label class="block mb-2 text-gray-700 font-medium">Select Table Size:</label>
            <select name="table_size" id="table_size_select" class="w-full border border-gray-300 p-3 rounded focus:ring focus:ring-red-200" required>
              <option value="">Choose a table size</option>
              <option value="2">2 Pax</option>
              <option value="4">4 Pax</option>
              <option value="6">6 Pax</option>
              <option value="10">10 Pax</option>
            </select>
          </div>
          <div class="text-right">
            <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded hover:bg-red-700 transition">Next</button>
          </div>
        </form>
      </div>

      <!-- Step 2 -->
      <div id="step2" class="hidden">
        <form action="" method="POST" enctype="multipart/form-data" class="space-y-5">
          <input type="hidden" name="table_size" id="table_size_hidden">

          <div>
            <label class="block text-gray-700 font-medium mb-1">Reservation Date:</label>
            <input type="date" name="day" class="w-full border border-gray-300 p-3 rounded" required>
          </div>

          <div>
            <label class="block text-gray-700 font-medium mb-1">Time:</label>
            <input type="time" name="time" class="w-full border border-gray-300 p-3 rounded" required>
          </div>

          <div>
            <label class="block text-gray-700 font-medium mb-1">Your Name:</label>
            <input type="text" name="name" class="w-full border border-gray-300 p-3 rounded" required>
          </div>

          <div>
            <label class="block text-gray-700 font-medium mb-1">Email:</label>
            <input type="email" name="email" class="w-full border border-gray-300 p-3 rounded" required>
          </div>

        <!-- QR Code Image Display -->
<div class="mb-4">
    <label class="block text-gray-700 font-medium mb-1">QR Code:</label>
    <!-- Display QR code image -->
    <img src="../../resources/image/gcashhshhs.jpg" alt="QR Code" class="w-32 h-32 object-cover rounded-md" id="qrCodeImage">
</div>

<!-- Image Upload Form -->
<div class="mb-4">
    <label class="block text-gray-700 font-medium mb-1">Upload Image:</label>
    <!-- File input for image upload -->
    <input type="file" name="image" class="w-full border border-gray-300 p-3 rounded" id="imageUpload" onchange="displayImagePreview(event)">
</div>

<!-- Image Preview -->
<div class="mt-4" id="imagePreviewContainer" style="display:none;">
    <label class="block text-gray-700 font-medium mb-1">Image Preview:</label>
    <img id="imagePreview" src="" alt="Image Preview" class="w-32 h-32 object-cover rounded-md">
</div>

<!-- Price with 300 PHP fee -->
<div class="mt-4">
    <label class="block text-gray-700 font-medium mb-1">Price:</label>
    <input type="text" value="300 PHP" readonly class="w-full border border-gray-300 p-3 rounded" disabled>
</div>

<script>
    // Function to display image preview when file is selected
    function displayImagePreview(event) {
        const imagePreviewContainer = document.getElementById('imagePreviewContainer');
        const imagePreview = document.getElementById('imagePreview');

        if (event.target.files && event.target.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                // Display the image preview and set the image source
                imagePreview.src = e.target.result;
                imagePreviewContainer.style.display = 'block'; // Show the preview
            }

            reader.readAsDataURL(event.target.files[0]);
        }
    }
</script>

          <div class="text-right">
            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 transition">Submit Reservation</button>
          </div>
        </form>
      </div>

    </div>
  </main>
</body>
</html>
