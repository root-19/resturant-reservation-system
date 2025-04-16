<?php
require_once __DIR__ . '/../../models/Menu.php';
require_once __DIR__ . '/../../../config/database.php';

// Database connection
$pdo = Database::connect();

$menu = new Menu($pdo);

// Fetch menu items
$menuItems = $menu->getAll();

$filter = isset($_GET['filter']) ? $_GET['filter'] : '';

// Apply filter if present
if ($filter) {
    $menuItems = array_filter($menuItems, function($item) use ($filter) {
        return stripos($item['food_name'], $filter) !== false;
    });
}

if (isset($_POST['delete']) && isset($_POST['id'])) {
    $menuItemId = (int)$_POST['id'];
    
    if ($menu->delete($menuItemId)) {
        // Redirect with a success message
        header('Refresh: 0');  
        exit;
    } else {
        header('Location: ' . $_SERVER['PHP_SELF'] . '?error=Failed to delete item');
        exit;
    }
}

// Pagination setup
$itemsPerPage = 3;
$totalItems = count($menuItems);
$totalPages = ceil($totalItems / $itemsPerPage); 


$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Ensure the page is within bounds
$currentPage = max(1, min($currentPage, $totalPages));


$startIndex = ($currentPage - 1) * $itemsPerPage;
$menuItemsPage = array_slice($menuItems, $startIndex, $itemsPerPage);

include "layout/sidebar.php";
?>

<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"/>
<div class="container mx-auto p-4">
    <h1 class="text-3xl font-extrabold text-center mb-8 text-gray-800">Menu List</h1>

    <!-- Filter Form -->
    <form action="" method="GET" class="mb-6 flex justify-center">
        <input type="text" name="filter" placeholder="Filter by description..." value="<?= htmlspecialchars($filter) ?>" class="border px-4 py-2 rounded-md w-1/3 mr-4">
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-200">Filter</button>
    </form>

    <!-- Menu Table -->
    <!-- Menu Table -->
<div class="overflow-x-auto bg-white rounded-lg shadow-md">
    <table class="min-w-full table-auto">
        <thead>
            <tr class="bg-gray-100">
                <th class="px-4 py-2 text-left">Image</th>
                <th class="px-4 py-2 text-left">Food Name</th>
                <th class="px-4 py-2 text-left">Price</th>
                <th class="px-4 py-2 text-left">Actions</th> <!-- Added Actions column -->
            </tr>
        </thead>
        <tbody>
            <?php foreach ($menuItemsPage as $item): ?>
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-2">
                        <img src="<?= '/uploads/' . basename($item['image_path']) ?>" alt="<?= htmlspecialchars($item['food_name']) ?>" class="w-24 h-24 object-cover rounded-md">
                    </td>
                    <td class="px-4 py-2"><?= htmlspecialchars($item['food_name']) ?></td>
                    <td class="px-4 py-2 text-green-500"><?= htmlspecialchars($item['price']) ?> PHP</td>
                    <td class="px-4 py-2">
                        <!-- Delete Button -->
                        <form action="" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?')">
                            <input type="hidden" name="id" value="<?= $item['id'] ?>"> <!-- Pass the ID of the item -->
                            <button type="submit" name="delete" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition duration-200">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


    <!-- Pagination Controls -->
    <div class="flex justify-center mt-6">
        <a href="?page=<?= max(1, $currentPage - 1) ?>&filter=<?= htmlspecialchars($filter) ?>" class="px-4 py-2 mx-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition duration-200">Previous</a>
        <span class="px-4 py-2 mx-2">Page <?= $currentPage ?> of <?= $totalPages ?></span>
        <a href="?page=<?= min($totalPages, $currentPage + 1) ?>&filter=<?= htmlspecialchars($filter) ?>" class="px-4 py-2 mx-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition duration-200">Next</a>
    </div>
</div>
