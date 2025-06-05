<?php
require_once __DIR__ .  '/../../../config/database.php';
require_once __DIR__ . '/../../controller/MenuController.php';

$pdo = Database::connect();
$controller = new MenuController($pdo);

// Fetch categories from database
$stmt = $pdo->query("SELECT DISTINCT id, name FROM categories ORDER BY name");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

$controller->store();

include "layout/sidebar.php";
?>

<div class="max-w-2xl mx-auto mt-10 bg-white p-8 rounded-lg shadow-lg">
  <h2 class="text-3xl font-bold mb-8 text-center text-gray-800">Add New Menu Item</h2>

  <?php if (isset($_GET['success'])): ?>
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
      <p class="font-bold">Success!</p>
      <p>Menu item added successfully!</p>
    </div>
  <?php endif; ?>

  <form action="" method="POST" enctype="multipart/form-data" class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <label for="food_name" class="block text-sm font-medium text-gray-700 mb-2">Food Name</label>
        <input type="text" id="food_name" name="food_name" required
               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
      </div>

      <div>
        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
        <select id="category" name="category_id" required
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
          <option value="">Select a category</option>
          <?php foreach ($categories as $category): ?>
            <option value="<?php echo htmlspecialchars($category['id']); ?>">
              <?php echo htmlspecialchars($category['name']); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div>
      <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
      <textarea id="description" name="description" rows="3" required
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"></textarea>
    </div>

    <div>
      <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price</label>
      <div class="relative">
        <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
        <input type="number" id="price" name="price" step="0.01" required
               class="w-full pl-8 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
      </div>
    </div>

    <div>
      <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Food Image</label>
      <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-500 transition duration-200">
        <div class="space-y-1 text-center">
          <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          <div class="flex text-sm text-gray-600">
            <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
              <span>Upload a file</span>
              <input id="image" name="image_path" type="file" class="sr-only" accept="image/*" onchange="previewImage(this)">
            </label>
            <p class="pl-1">or drag and drop</p>
          </div>
          <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
        </div>
      </div>
      <div id="imagePreview" class="mt-4 hidden">
        <img id="preview" src="#" alt="Preview" class="max-h-48 rounded-lg shadow-md">
      </div>
    </div>

    <div class="flex justify-end space-x-4">
      <button type="button" onclick="window.history.back()"
              class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
        Cancel
      </button>
      <button type="submit"
              class="px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
        Save Menu Item
      </button>
    </div>
  </form>
</div>

<script>
function previewImage(input) {
  const preview = document.getElementById('preview');
  const previewDiv = document.getElementById('imagePreview');
  
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    
    reader.onload = function(e) {
      preview.src = e.target.result;
      previewDiv.classList.remove('hidden');
    }
    
    reader.readAsDataURL(input.files[0]);
  }
}
</script>
