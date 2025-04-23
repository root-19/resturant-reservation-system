<?php
require_once __DIR__ . '/../../../config/database.php';

$pdo = Database::connect();

// Reservation Revenue
$reservationCountStmt = $pdo->query("SELECT COUNT(*) FROM reservations");
$totalReservations = $reservationCountStmt->fetchColumn();
$reservationRevenue = $totalReservations * 300;

// Food Orders Revenue
$foodRevenueStmt = $pdo->query("
    SELECT SUM(ci.quantity * mi.price) AS total 
    FROM cart_items ci
    JOIN menu_items mi ON ci.menu_item_id = mi.id
");
$foodRevenue = $foodRevenueStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

// Total Menu Items
$menuItemsCount = $pdo->query("SELECT COUNT(*) FROM menu_items")->fetchColumn();

// Accepted Reservations
$acceptedCount = $pdo->query("SELECT COUNT(*) FROM reservations WHERE info = 'accepted'")->fetchColumn();

// Rejected Reservations
$rejectedCount = $pdo->query("SELECT COUNT(*) FROM reservations WHERE info = 'rejected'")->fetchColumn();

// Total Revenue
$totalRevenue = $reservationRevenue + $foodRevenue;

// Get all reservations (no pagination)
$stmt = $pdo->prepare("
    SELECT * 
    FROM reservations 
    ORDER BY 
        CASE 
            WHEN info = 'accepted' THEN 1
            WHEN info = 'rejected' THEN 2
            ELSE 0 
        END,
        day DESC, 
        time DESC
");
$stmt->execute();
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

include "layout/sidebar.php";
?>

<div class="p-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">

        <div class="bg-blue-100 p-4 rounded shadow flex items-center">
            <svg class="w-8 h-8 text-blue-800 mr-4" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 17v-6h6v6m2 4H7a2 2 0 01-2-2V7a2 2 0 012-2h3l2-2h4l2 2h3a2 2 0 012 2v12a2 2 0 01-2 2z" />
            </svg>
            <div>
                <h2 class="text-lg font-semibold text-blue-800">Reservation Revenue</h2>
                <p class="text-2xl font-bold text-blue-900">₱<?= number_format($reservationRevenue, 2); ?></p>
            </div>
        </div>

        <div class="bg-green-100 p-4 rounded shadow flex items-center">
            <svg class="w-8 h-8 text-green-800 mr-4" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 7h18M3 12h18M3 17h18" />
            </svg>
            <div>
                <h2 class="text-lg font-semibold text-green-800">Food Order Revenue</h2>
                <p class="text-2xl font-bold text-green-900">₱<?= number_format($foodRevenue, 2); ?></p>
            </div>
        </div>

        <div class="bg-yellow-100 p-4 rounded shadow flex items-center">
            <svg class="w-8 h-8 text-yellow-800 mr-4" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M20 13V6a2 2 0 00-2-2h-3.586a1 1 0 00-.707.293L12 5.586 10.293 3.879A1 1 0 009.586 3H6a2 2 0 00-2 2v7" />
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 12v9m0 0H6m6 0h6" />
            </svg>
            <div>
                <h2 class="text-lg font-semibold text-yellow-800">Total Menu Items</h2>
                <p class="text-2xl font-bold text-yellow-900"><?= $menuItemsCount; ?> items</p>
            </div>
        </div>

        <div class="bg-indigo-100 p-4 rounded shadow flex items-center">
            <svg class="w-8 h-8 text-indigo-800 mr-4" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M5 13l4 4L19 7" />
            </svg>
            <div>
                <h2 class="text-lg font-semibold text-indigo-800">Accepted Reservations</h2>
                <p class="text-2xl font-bold text-indigo-900"><?= $acceptedCount; ?></p>
            </div>
        </div>

        <div class="bg-red-100 p-4 rounded shadow flex items-center">
            <svg class="w-8 h-8 text-red-800 mr-4" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M6 18L18 6M6 6l12 12" />
            </svg>
            <div>
                <h2 class="text-lg font-semibold text-red-800">Rejected Reservations</h2>
                <p class="text-2xl font-bold text-red-900"><?= $rejectedCount; ?></p>
            </div>
        </div>

        <div class="bg-purple-100 p-4 rounded shadow flex items-center">
            <svg class="w-8 h-8 text-purple-800 mr-4" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 8c-1.657 0-3 1.343-3 3v4h6v-4c0-1.657-1.343-3-3-3z" />
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M5 20h14a2 2 0 002-2v-5a9 9 0 00-18 0v5a2 2 0 002 2z" />
            </svg>
            <div>
                <h2 class="text-lg font-semibold text-purple-800">Total Revenue</h2>
                <p class="text-2xl font-bold text-purple-900">₱<?= number_format($totalRevenue, 2); ?></p>
            </div>
        </div>

    </div>
</div>
