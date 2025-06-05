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
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-8">
            <h1 class="text-4xl font-bold text-center mb-8 text-gray-800 tracking-tight">Menu Management</h1>

            <!-- Filter Form -->
            <form action="" method="GET" class="mb-8">
                <div class="flex items-center justify-center space-x-4">
                    <div class="relative flex-1 max-w-xl">
                        <input type="text" 
                               name="filter" 
                               placeholder="Search menu items..." 
                               value="<?= htmlspecialchars($filter) ?>" 
                               class="w-full px-6 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 outline-none">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                    <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 font-medium shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        Search
                    </button>
                </div>
            </form>

            <!-- Menu Table -->
            <div class="overflow-hidden rounded-xl border border-gray-200 shadow-sm">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Food Name</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($menuItemsPage as $item): ?>
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <img src="<?= '/uploads/' . basename($item['image_path']) ?>" 
                                             alt="<?= htmlspecialchars($item['food_name']) ?>" 
                                             class="w-20 h-20 object-cover rounded-lg shadow-md">
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($item['food_name']) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-sm font-semibold text-green-600 bg-green-100 rounded-full">
                                        <?= htmlspecialchars($item['price']) ?> PHP
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <form action="" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?')">
                                        <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                        <button type="submit" 
                                                name="delete" 
                                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-200">
                                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination Controls -->
            <div class="flex items-center justify-center mt-8 space-x-4">
                <a href="?page=<?= max(1, $currentPage - 1) ?>&filter=<?= htmlspecialchars($filter) ?>" 
                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200 <?= $currentPage <= 1 ? 'opacity-50 cursor-not-allowed' : '' ?>">
                    Previous
                </a>
                <span class="text-sm font-medium text-gray-700">
                    Page <?= $currentPage ?> of <?= $totalPages ?>
                </span>
                <a href="?page=<?= min($totalPages, $currentPage + 1) ?>&filter=<?= htmlspecialchars($filter) ?>" 
                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200 <?= $currentPage >= $totalPages ? 'opacity-50 cursor-not-allowed' : '' ?>">
                    Next
                </a>
            </div>
        </div>
    </div>
</div>
