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
        <div class="bg-blue-100 p-4 rounded shadow">
            <h2 class="text-lg font-semibold text-blue-800">Reservation Revenue</h2>
            <p class="text-2xl font-bold text-blue-900">₱<?= number_format($reservationRevenue, 2); ?></p>
        </div>
        <div class="bg-green-100 p-4 rounded shadow">
            <h2 class="text-lg font-semibold text-green-800">Food Order Revenue</h2>
            <p class="text-2xl font-bold text-green-900">₱<?= number_format($foodRevenue, 2); ?></p>
        </div>
        <div class="bg-yellow-100 p-4 rounded shadow">
            <h2 class="text-lg font-semibold text-yellow-800">Total Menu Items</h2>
            <p class="text-2xl font-bold text-yellow-900"><?= $menuItemsCount; ?> items</p>
        </div>
        <div class="bg-indigo-100 p-4 rounded shadow">
            <h2 class="text-lg font-semibold text-indigo-800">Accepted Reservations</h2>
            <p class="text-2xl font-bold text-indigo-900"><?= $acceptedCount; ?></p>
        </div>
        <div class="bg-red-100 p-4 rounded shadow">
            <h2 class="text-lg font-semibold text-red-800">Rejected Reservations</h2>
            <p class="text-2xl font-bold text-red-900"><?= $rejectedCount; ?></p>
        </div>
        <div class="bg-purple-100 p-4 rounded shadow">
            <h2 class="text-lg font-semibold text-purple-800">Total Revenue</h2>
            <p class="text-2xl font-bold text-purple-900">₱<?= number_format($totalRevenue, 2); ?></p>
        </div>
    </div>

    <!-- You can display reservation table or anything else here -->
</div>
