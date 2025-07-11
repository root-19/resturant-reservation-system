<?php
require_once __DIR__ . '/../../config/database.php';

$pdo = Database::connect();

// Get user's reservations
$stmt = $pdo->prepare("
    SELECT * FROM reservations 
    WHERE name = ? 
    ORDER BY day DESC, time DESC
");
$stmt->execute([$_SESSION['username']]);
$upcomingReservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Count active reservations (only accepted ones)
$activeReservations = count(array_filter($upcomingReservations, function ($res) {
    $reservationDateTime = strtotime($res['day'] . ' ' . $res['time']);
    return $reservationDateTime >= time() && $res['status'] === 'accepted';
}));

// Count past visits (only completed ones)
$pastVisits = count(array_filter($upcomingReservations, function ($res) {
    $reservationDateTime = strtotime($res['day'] . ' ' . $res['time']);
    return $reservationDateTime < time() && $res['status'] === 'accepted';
}));

// Get total spent
$totalSpent = 0;
foreach ($upcomingReservations as $res) {
    if ($res['status'] === 'accepted') {
        $totalSpent += 300; // Base reservation fee
    }
}

$title = 'Dashboard';
$homeUrl = '/dashboard';
$navItems = [
    ['url' => '/menu', 'text' => 'Menu'],
    ['url' => '/reservation', 'text' => 'Make Reservation']
];


ob_start();
?>

<div class="container mx-auto py-8 px-4">
    <div class="mb-8">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-2xl font-bold text-gray-800">Welcome, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Guest'); ?>!</h2>
            <p class="text-gray-600 mt-2">Manage your reservations and view your dining history.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-sm p-6 text-center">
            <div class="text-red-600 text-3xl mb-3">
                <i class="fas fa-calendar-check"></i>
            </div>
            <h5 class="text-lg font-semibold text-gray-800">Active Reservations</h5>
            <h2 class="text-4xl font-bold text-gray-900 mt-2"><?php echo $activeReservations ?? 0; ?></h2>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6 text-center">
            <div class="text-green-600 text-3xl mb-3">
                <i class="fas fa-history"></i>
            </div>
            <h5 class="text-lg font-semibold text-gray-800">Past Visits</h5>
            <h2 class="text-4xl font-bold text-gray-900 mt-2"><?php echo $pastVisits ?? 0; ?></h2>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6 text-center">
            <div class="text-blue-600 text-3xl mb-3">
                <i class="fas fa-wallet"></i>
            </div>
            <h5 class="text-lg font-semibold text-gray-800">Total Spent</h5>
            <h2 class="text-4xl font-bold text-gray-900 mt-2">₱<?php echo number_format($totalSpent, 2); ?></h2>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="md:col-span-2">
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-4 border-b">
                    <h5 class="text-lg font-semibold text-gray-800">Quick Actions</h5>
                </div>
                <div class="p-4">
                    <div class="space-y-2">
                        <a href="/reservation" class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition duration-200">
                            <span class="flex items-center text-gray-700">
                                <i class="fas fa-calendar-plus text-red-600 mr-3"></i>
                                Make a Reservation
                            </span>
                            <i class="fas fa-chevron-right text-red-600"></i>
                        </a>
                        <a href="/menu" class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition duration-200">
                            <span class="flex items-center text-gray-700">
                                <i class="fas fa-utensils text-red-600 mr-3"></i>
                                View Menu
                            </span>
                            <i class="fas fa-chevron-right text-red-600"></i>
                        </a>
                        <a href="/profile" class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition duration-200">
                            <span class="flex items-center text-gray-700">
                                <i class="fas fa-user text-red-600 mr-3"></i>
                                View Profile
                            </span>
                            <i class="fas fa-chevron-right text-red-600"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-4 border-b flex justify-between items-center">
            <h5 class="text-lg font-semibold text-gray-800">Reservations</h5>
            <div class="flex space-x-2">
                <button type="button" class="px-4 py-2 text-sm font-medium text-red-600 bg-red-50 rounded-md hover:bg-red-100 active" data-filter="all">All</button>
                <button type="button" class="px-4 py-2 text-sm font-medium text-red-600 bg-red-50 rounded-md hover:bg-red-100" data-filter="active">Active</button>
                <button type="button" class="px-4 py-2 text-sm font-medium text-red-600 bg-red-50 rounded-md hover:bg-red-100" data-filter="past">Past</button>
            </div>
        </div>
        <div class="p-4">
            <?php if (!empty($upcomingReservations)): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Table Size</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($upcomingReservations as $reservation):
                                $reservationDateTime = strtotime($reservation['day'] . ' ' . $reservation['time']);
                                $isPast = $reservationDateTime < time();

                                if ($reservation['status'] === 'rejected') {
                                    $status = 'Rejected';
                                    $statusClass = 'bg-gray-500';
                                } elseif ($reservation['status'] === 'accepted') {
                                    $status = 'Accepted';
                                    $statusClass = 'bg-green-500';
                                } elseif ($reservation['status'] === 'pending') {
                                    $status = 'Pending';
                                    $statusClass = 'bg-yellow-500';
                                } elseif ($reservation['status'] === 'completed') {
                                    $status = 'Completed';
                                    $statusClass = 'bg-blue-500';
                                } else {
                                    $status = $isPast ? 'Completed' : 'Upcoming';
                                    $statusClass = $isPast ? 'bg-blue-500' : 'bg-red-500';
                                }

                                $paymentStatus = $reservation['image_path'] ? 'Paid' : 'Unpaid';
                                $paymentClass = $reservation['image_path'] ? 'bg-green-500' : 'bg-yellow-500';
                            ?>
                                <tr class="reservation-row hover:bg-gray-50" data-status="<?php echo $isPast ? 'past' : 'active'; ?>">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo date('F d, Y', strtotime($reservation['day'])); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo date('h:i A', strtotime($reservation['time'])); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($reservation['table_size']); ?> Pax</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full text-white <?php echo $statusClass; ?>">
                                            <?php echo $status; ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full text-white <?php echo $paymentClass; ?>">
                                            <?php echo $paymentStatus; ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php if ($reservation['status'] === 'pending'): ?>
                                            <a href="#" class="text-red-600 hover:text-red-900 update-btn" 
                                               data-id="<?php echo $reservation['id']; ?>"
                                               data-day="<?php echo htmlspecialchars($reservation['day']); ?>"
                                               data-time="<?php echo htmlspecialchars($reservation['time']); ?>"
                                               data-table_size="<?php echo htmlspecialchars($reservation['table_size']); ?>">
                                               Update
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($reservation['status'] === 'accepted'): ?>
                                            <button class="px-3 py-1 text-sm text-white bg-green-500 rounded-md cursor-not-allowed" disabled>Confirmed</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <div class="text-gray-400 text-5xl mb-4">
                        <i class="fas fa-calendar-times"></i>
                    </div>
                    <p class="text-gray-500 mb-4">No reservations found.</p>
                    <a href="/reservation" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                        Make a Reservation
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Edit Reservation Modal -->
<div class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden" id="editReservationModal">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="flex items-center justify-between p-4 border-b">
                <h5 class="text-lg font-semibold text-gray-800">Edit Reservation</h5>
                <button type="button" class="text-gray-400 hover:text-gray-500" onclick="closeModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="editReservationForm" enctype="multipart/form-data">
                <div class="p-4">
                    <input type="hidden" name="id" id="editReservationId">
                    <div class="mb-4">
                        <label for="editDay" class="block text-sm font-medium text-gray-700">Date</label>
                        <input type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" name="day" id="editDay" required>
                    </div>
                    <div class="mb-4">
                        <label for="editTime" class="block text-sm font-medium text-gray-700">Time</label>
                        <input type="time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" name="time" id="editTime" required>
                    </div>
                    <div class="mb-4">
                        <label for="editTableSize" class="block text-sm font-medium text-gray-700">Table Size</label>
                        <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" name="table_size" id="editTableSize" required>
                            <option value="2">2 Pax</option>
                            <option value="4">4 Pax</option>
                            <option value="6">6 Pax</option>
                            <option value="10">10 Pax</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="receipt" class="block text-sm font-medium text-gray-700">Upload Receipt</label>
                        <input type="file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100" name="receipt" id="receipt" accept="image/*,.pdf">
                        <p class="mt-1 text-sm text-gray-500">Accepted formats: JPG, PNG, PDF</p>
                    </div>
                </div>
                <div class="flex items-center justify-end p-4 border-t">
                    <button type="button" class="mr-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter buttons functionality
    document.querySelectorAll('[data-filter]').forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.dataset.filter;
            
            // Update active button
            document.querySelectorAll('[data-filter]').forEach(btn => {
                btn.classList.remove('bg-red-600', 'text-white');
                btn.classList.add('text-red-600', 'bg-red-50');
            });
            this.classList.remove('text-red-600', 'bg-red-50');
            this.classList.add('bg-red-600', 'text-white');
            
            // Filter rows
            document.querySelectorAll('.reservation-row').forEach(row => {
                if (filter === 'all' || row.dataset.status === filter) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
    
    document.querySelectorAll('.update-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('editReservationId').value = btn.getAttribute('data-id');
            document.getElementById('editDay').value = btn.getAttribute('data-day');
            document.getElementById('editTime').value = btn.getAttribute('data-time').slice(0,5);
            document.getElementById('editTableSize').value = btn.getAttribute('data-table_size');
            document.getElementById('editReservationModal').classList.remove('hidden');
        });
    });

    document.getElementById('editReservationForm').addEventListener('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        
        fetch('/reservation/edit_reservation_ajax', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeModal();
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message,
                    confirmButtonColor: '#dc2626'
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: data.message || 'Failed to update reservation. Please try again.',
                    confirmButtonColor: '#dc2626'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'An unexpected error occurred. Please try again.',
                confirmButtonColor: '#dc2626'
            });
        });
    });
});

function closeModal() {
    document.getElementById('editReservationModal').classList.add('hidden');
}
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/layout.php';
?>