<?php
require_once __DIR__ . '/../../../config/database.php';

$pdo = Database::connect();

// Fetch reservations
$stmt = $pdo->prepare("SELECT * FROM reservations ORDER BY day DESC, time DESC");
$stmt->execute();
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

include "layout/sidebar.php";
?>

<!-- Table Container -->
<main class="p-6">
    <div class="max-w-7xl mx-auto bg-white rounded-lg shadow-md overflow-x-auto">
      <table class="min-w-full table-auto border-collapse border border-gray-300">
        <thead class="bg-blue-600 text-white">
          <tr>
            <th class="px-4 py-2 border">#</th>
            <th class="px-4 py-2 border">Name</th>
            <th class="px-4 py-2 border">Email</th>
            <th class="px-4 py-2 border">Table Size</th>
            <th class="px-4 py-2 border">Date</th>
            <th class="px-4 py-2 border">Time</th>
            <th class="px-4 py-2 border">Image</th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($reservations) > 0): ?>
            <?php foreach ($reservations as $index => $row): ?>
              <tr class="text-center border-t">
                <td class="px-4 py-2 border"><?php echo $index + 1; ?></td>
                <td class="px-4 py-2 border"><?php echo htmlspecialchars($row['name']); ?></td>
                <td class="px-4 py-2 border"><?php echo htmlspecialchars($row['email']); ?></td>
                <td class="px-4 py-2 border"><?php echo htmlspecialchars($row['table_size']); ?> Pax</td>
                <td class="px-4 py-2 border"><?php echo htmlspecialchars($row['day']); ?></td>
                <td class="px-4 py-2 border"><?php echo htmlspecialchars($row['time']); ?></td>
                <td class="px-4 py-2 border">
                  <?php if (!empty($row['image'])): ?>
                    <img src="../uploads/<?php echo $row['image']; ?>" alt="Image" class="h-12 mx-auto rounded">
                  <?php else: ?>
                    <span class="text-gray-400 italic">None</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="7" class="text-center py-6 text-gray-500">No reservations found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>