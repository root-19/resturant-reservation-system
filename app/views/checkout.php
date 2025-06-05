<?php
require_once __DIR__ . '/../../config/database.php';

if (empty($_SESSION['cart'])) {
    header('Location: menu');
    exit;
}

$grandTotal = 0;
foreach ($_SESSION['cart'] as $cartItem) {
    $grandTotal += $cartItem['price'] * $cartItem['quantity'];
}
?>

<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"/>

<div class="min-h-screen bg-gray-100 py-6">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-center mb-8">Checkout</h1>
            
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">Order Summary</h2>
                <?php foreach ($_SESSION['cart'] as $cartItem): ?>
                    <div class="flex justify-between items-center py-2 border-b">
                        <div>
                            <p class="font-medium"><?= htmlspecialchars($cartItem['name']) ?></p>
                            <p class="text-sm text-gray-500">Quantity: <?= $cartItem['quantity'] ?></p>
                        </div>
                        <p class="font-medium"><?= number_format($cartItem['price'] * $cartItem['quantity'], 2) ?> PHP</p>
                    </div>
                <?php endforeach; ?>
                <div class="mt-4 text-right">
                    <p class="text-lg font-bold">Total: <span class="text-green-600"><?= number_format($grandTotal, 2) ?> PHP</span></p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Customer Information</h2>
                <form action="process_order.php" method="POST" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input type="text" name="customer_name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <input type="tel" name="phone" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Delivery Address</label>
                        <textarea name="address" required rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Payment Method</label>
                        <select name="payment_method" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                            <option value="cash">Cash on Delivery</option>
                            <option value="gcash">GCash</option>
                        </select>
                    </div>
                    <div class="flex justify-between items-center pt-4">
                        <a href="menu" class="text-red-600 hover:text-red-700">← Back to Menu</a>
                        <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 transition">
                            Place Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 