<?php
require_once __DIR__ . '/../../../config/database.php';

$pdo = Database::connect();

// Set the default timezone to Philippine Time (PHT)
date_default_timezone_set('Asia/Manila');

// Get current time in Philippine Time
$currentTime = date('Y-m-d H:i:s');

// Query to fetch reservations including both ongoing and completed ones
$stmt = $pdo->prepare("SELECT * FROM reservations ORDER BY day DESC, time DESC");
$stmt->execute();
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

include "layout/sidebar.php";
?>

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
                    <th class="px-4 py-2 border">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($reservations) > 0): ?>
                    <?php foreach ($reservations as $index => $row): ?>
                        <tr class="text-center border-t bg-gray-50">
                            <td class="px-4 py-2 border"><?php echo $index + 1; ?></td>
                            <td class="px-4 py-2 border"><?php echo htmlspecialchars($row['name']); ?></td>
                            <td class="px-4 py-2 border"><?php echo htmlspecialchars($row['email']); ?></td>
                            <td class="px-4 py-2 border"><?php echo htmlspecialchars($row['table_size']); ?> Pax</td>
                            <td class="px-4 py-2 border"><?php echo htmlspecialchars($row['day']); ?></td>
                            <td class="px-4 py-2 border"><?php echo htmlspecialchars($row['time']); ?></td>
                            <td class="px-4 py-2 border">
                                <?php
                                // Get the reservation date and time
                                $reservationDateTime = strtotime($row['day'] . ' ' . $row['time']);
                                $currentDateTime = time();
                                $remainingTime = $reservationDateTime - $currentDateTime;

                                if ($remainingTime <= 3600 && $remainingTime > 0) {
                                    // Countdown for ongoing reservation (within 1 hour)
                                    echo 'Ongoing - Countdown: ' . gmdate("i:s", $remainingTime);
                                } elseif ($remainingTime <= 0) {
                                    // Mark reservation as done and update the status in the database
                                    echo 'Done';
                                    $updateStmt = $pdo->prepare("UPDATE reservations SET status = 'done' WHERE id = ?");
                                    $updateStmt->execute([$row['id']]);
                                } else {
                                    echo 'Upcoming';
                                }
                                ?>
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

<script>
    // JavaScript to update countdown every second for ongoing reservations
    setInterval(function() {
        const countdownElements = document.querySelectorAll('.countdown');
        
        countdownElements.forEach(function(el) {
            const countdownDate = el.getAttribute('data-time');
            const targetTime = new Date(countdownDate).getTime();
            const currentTime = new Date().getTime();
            const remainingTime = targetTime - currentTime;
            
            if (remainingTime > 0) {
                const minutes = Math.floor((remainingTime % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((remainingTime % (1000 * 60)) / 1000);
                el.innerHTML = `Ongoing - Countdown: ${minutes}:${seconds}`;
            } else {
                el.innerHTML = 'Done';
            }
        });
    }, 1000); // Update countdown every second
</script>
