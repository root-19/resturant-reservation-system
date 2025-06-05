<?php
if (!isset($_GET['order_id'])) {
    header('Location: menu');
    exit;
}
?>

<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"/>

<div class="min-h-screen bg-gray-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-lg shadow-md">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">Order Successful!</h2>
            <p class="mt-2 text-sm text-gray-600">
                Thank you for your order. Your order number is #<?= htmlspecialchars($_GET['order_id']) ?>
            </p>
        </div>
        <div class="mt-8 space-y-4">
            <p class="text-center text-gray-600">
                We will contact you shortly to confirm your order and delivery details.
            </p>
            <div class="flex justify-center space-x-4">
                <a href="menu" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                    Back to Menu
                </a>
                <a href="dashboard" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-red-600 bg-red-100 hover:bg-red-200">
                    Go to Home
                </a>
            </div>
        </div>
    </div>
</div> 