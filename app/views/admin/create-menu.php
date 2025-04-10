<?php
require_once __DIR__ .  '/../../../config/database.php';
require_once __DIR__ . '/../../controller/MenuController.php';

$pdo = Database::connect();
$controller = new MenuController($pdo);
$controller->store();

include "layout/sidebar.php";
?>

<div class="max-w-md mx-auto mt-10 bg-white p-8 rounded shadow">
  <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Add New Menu Item</h2>

  <?php if (isset($_GET['success'])): ?>
    <div class="bg-green-100 text-green-800 p-3 mb-4 rounded">Menu item added successfully!</div>
  <?php endif; ?>

  <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
    <div>
      <label for="food_name" class="block text-gray-700 font-medium">Food Name</label>
      <input type="text" id="food_name" name="food_name" required
             class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
    </div>

    <div>
      <label for="description" class="block text-gray-700 font-medium">description</label>
      <input type="text" id="description" name="description" required
             class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
    </div>

    <div>
      <label for="price" class="block text-gray-700 font-medium">Price</label>
      <input type="number" id="price" name="price" step="0.01" required
             class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
    </div>

    <div>
      <label for="image" class="block text-gray-700 font-medium">Upload Image</label>
      <input type="file" id="image" name="image_path" accept="image/*"
             class="w-full px-3 py-2 border rounded focus:outline-none file:mr-4 file:py-2 file:px-4 file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
    </div>

    <div>
      <button type="submit"
              class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-200">
        Save Menu Item
      </button>
    </div>
  </form>
</div>
