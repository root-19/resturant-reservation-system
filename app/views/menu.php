<?php

require_once __DIR__ . '/../models/Menu.php';
require_once __DIR__ . '/../../config/database.php';

$pdo = Database::connect();
$menu = new Menu($pdo);
$menuItems = $menu->getAll();

// Add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item_id'])) {
    $itemId = $_POST['item_id'];
    $foodName = $_POST['food_name'];
    $price = $_POST['price'];
    $imagePath = $_POST['image_path'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $found = false;
    foreach ($_SESSION['cart'] as &$cartItem) {
        if ($cartItem['id'] == $itemId) {
            $cartItem['quantity']++;
            $found = true;
            break;
        }
    }

    if (!$found) {
        $_SESSION['cart'][] = [
            'id' => $itemId,
            'name' => $foodName,
            'price' => $price,
            'image' => $imagePath,
            'quantity' => 1
        ];
    }
}

// Remove from cart
if (isset($_GET['remove_id'])) {
    $removeId = $_GET['remove_id'];
    unset($_SESSION['cart'][$removeId]);
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit;
}
?>

<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"/>

<script>
    function toggleCart() {
        const panel = document.getElementById("cartPanel");
        panel.classList.toggle("hidden");
    }
</script>

<!-- Navbar -->
<nav class="bg-red-600 text-white flex items-center justify-between px-6 py-4">
    <div class="text-xl font-bold">🍴 MISAKI BISTRO</div>
    <a href="logout" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">Logout</a>
    <button onclick="toggleCart()" class="bg-white text-red-600 px-4 py-2 rounded hover:bg-gray-200 transition">
        🛒 View Cart (<?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0 ?>)
    </button>
</nav>

<!-- Cart Panel -->
<div id="cartPanel" class="hidden fixed right-0 top-0 w-80 h-full bg-white shadow-lg z-50 overflow-y-auto">
    <div class="p-4 border-b bg-red-600 text-white flex justify-between items-center">
        <h2 class="text-xl font-bold">🛒 Cart</h2>
        <button onclick="toggleCart()" class="font-bold text-xl">✖</button>
    </div>
    <div class="p-4">
        <?php
        $grandTotal = 0;
        if (!empty($_SESSION['cart'])):
            foreach ($_SESSION['cart'] as $id => $cartItem):
                $itemTotal = $cartItem['price'] * $cartItem['quantity'];
                $grandTotal += $itemTotal;
        ?>
            <div class="flex items-center mb-3 border p-2 rounded">
                <img src="<?= $cartItem['image'] ?>" class="w-12 h-12 object-cover rounded" alt="">
                <div class="flex-1 ml-3">
                    <p class="font-medium"><?= htmlspecialchars($cartItem['name']) ?></p>
                    <p class="text-sm text-gray-500"><?= $cartItem['quantity'] ?> × <?= $cartItem['price'] ?> PHP</p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-semibold"><?= number_format($itemTotal, 2) ?> PHP</p>
                    <a href="?remove_id=<?= $id ?>" class="text-xs text-red-500 hover:underline">🗑 Remove</a>
                </div>
            </div>
        <?php endforeach; ?>
        <div class="border-t pt-4 mt-4 text-right font-bold">
            Total: <span class="text-green-600"><?= number_format($grandTotal, 2) ?> PHP</span>
        </div>
        <div class="mt-4">
            <a href="reservation" class="block bg-red-600 text-white py-2 rounded text-center hover:bg-red-700 transition">Reservation</a>
        </div>
        <?php else: ?>
            <p class="text-center text-gray-500">Cart is empty.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Menu List -->
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6 text-center text-gray-700">Menu List</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($menuItems as $item): ?>
            <div class="bg-white shadow rounded hover:shadow-lg transition overflow-hidden">
                <img src="<?= $item['image_path'] ?>" class="w-full h-48 object-cover" alt="">
                <div class="p-4">
                    <h2 class="text-xl font-semibold"><?= htmlspecialchars($item['food_name']) ?></h2>
                    <p class="text-green-600 text-lg mt-2"><?= $item['price'] ?> PHP</p>
                    <form method="POST" class="mt-4">
                        <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                        <input type="hidden" name="food_name" value="<?= htmlspecialchars($item['food_name']) ?>">
                        <input type="hidden" name="price" value="<?= $item['price'] ?>">
                        <input type="hidden" name="image_path" value="<?= $item['image_path'] ?>">
                        <button type="submit" class="w-full bg-red-600 text-white py-2 rounded hover:bg-red-700">
                            Add to Cart
                        </button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
