<?php
require_once __DIR__ . '/../../config/database.php';

$pdo = Database::connect();

// Get user's reservations
$stmt = $pdo->prepare("
    SELECT * FROM reservations 
    ORDER BY day DESC, time DESC
");
$stmt->execute();
$upcomingReservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Count active reservations (only accepted ones)
$activeReservations = count(array_filter($upcomingReservations, function ($res) {
    return $res['status'] === 'accepted';
}));

// Count rejected reservations
$rejectedReservations = count(array_filter($upcomingReservations, function ($res) {
    return $res['status'] === 'rejected';
}));

// Count past visits (only completed ones)
$pastVisits = count(array_filter($upcomingReservations, function ($res) {
    $reservationDateTime = strtotime($res['day'] . ' ' . $res['time']);
    return $reservationDateTime < time() && $res['status'] === 'accepted';
}));

// Get total spent
$reservationRevenue = 0;
foreach ($upcomingReservations as $res) {
    if ($res['status'] === 'accepted') {
        $reservationRevenue += 300; // Base reservation fee
    }
}

$foodOrderRevenue = 1500;
$totalMenuItems = 3;
$totalRevenue = $reservationRevenue + $foodOrderRevenue;

// Fetch monthly revenue data for chart
$stmt = $pdo->prepare("
    SELECT 
        strftime('%Y-%m', day) as month_year,
        COUNT(*) as accepted_reservations_count
    FROM reservations
    WHERE status = 'accepted'
    GROUP BY month_year
    ORDER BY month_year ASC
");
$stmt->execute();
$monthlyReservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

$chartLabels = [];
$chartData = [];

foreach ($monthlyReservations as $monthData) {
    $dateObj = DateTime::createFromFormat('Y-m', $monthData['month_year']);
    $chartLabels[] = $dateObj->format('M Y'); // e.g., Jan 2023
    $monthlyRevenue = $monthData['accepted_reservations_count'] * 300; // 300 base fee per reservation
    $chartData[] = $monthlyRevenue;
}

$title = 'Dashboard';
$homeUrl = '/dashboard';
$navItems = [
    ['url' => '/menu', 'text' => 'Menu'],
    ['url' => '/reservation', 'text' => 'Make Reservation']
];


ob_start();
?>

<div class="container mx-auto py-4 px-4">
    <div class="mb-4">
        <div class="w-full">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-0">Welcome, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Guest'); ?>!</h2>
                <p class="text-gray-600 mt-2">Manage your reservations and view your dining history.</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <div class="">
            <div class="bg-[#e6f0ff] text-center p-6 rounded-lg shadow-md h-full flex flex-col items-center justify-center">
                <i class="fas fa-receipt fa-2x text-blue-600 mb-3"></i>
                <h5 class="text-lg font-semibold">Reservation Revenue</h5>
                <h2 class="text-4xl font-bold">₱<?php echo number_format($reservationRevenue, 2); ?></h2>
            </div>
        </div>
        <div class="">
            <div class="bg-[#e6ffe6] text-center p-6 rounded-lg shadow-md h-full flex flex-col items-center justify-center">
                <i class="fas fa-utensils fa-2x text-green-600 mb-3"></i>
                <h5 class="text-lg font-semibold">Food Order Revenue</h5>
                <h2 class="text-4xl font-bold">₱<?php echo number_format($foodOrderRevenue, 2); ?></h2>
            </div>
        </div>
        <div class="">
            <div class="bg-[#fffbe6] text-center p-6 rounded-lg shadow-md h-full flex flex-col items-center justify-center">
                <i class="fas fa-list fa-2x text-yellow-600 mb-3"></i>
                <h5 class="text-lg font-semibold">Total Menu Items</h5>
                <h2 class="text-4xl font-bold"><?php echo $totalMenuItems ?? 0; ?> Items</h2>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <div class="">
            <div class="bg-[#f0e6ff] text-center p-6 rounded-lg shadow-md h-full flex flex-col items-center justify-center">
                <i class="fas fa-check-circle fa-2x text-blue-600 mb-3"></i>
                <h5 class="text-lg font-semibold">Accepted Reservations</h5>
                <h2 class="text-4xl font-bold"><?php echo $activeReservations ?? 0; ?></h2>
            </div>
        </div>
        <div class="">
            <div class="bg-[#ffe6e6] text-center p-6 rounded-lg shadow-md h-full flex flex-col items-center justify-center">
                <i class="fas fa-times-circle fa-2x text-red-600 mb-3"></i>
                <h5 class="text-lg font-semibold">Rejected Reservations</h5>
                <h2 class="text-4xl font-bold"><?php echo $rejectedReservations ?? 0; ?></h2>
            </div>
        </div>
        <div class="">
            <div class="bg-[#f0e6ff] text-center p-6 rounded-lg shadow-md h-full flex flex-col items-center justify-center">
                <i class="fas fa-wallet fa-2x text-indigo-600 mb-3"></i>
                <h5 class="text-lg font-semibold">Total Revenue</h5>
                <h2 class="text-4xl font-bold">₱<?php echo number_format($totalRevenue, 2); ?></h2>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div class="">
            <div class="bg-white shadow-md rounded-lg h-full p-6">
                <h5 class="text-xl font-semibold text-gray-800 mb-4">Quick Actions</h5>
                <div class="">
                    <a href="/reservation" class="flex items-center justify-between p-4 border-b border-gray-200 hover:bg-gray-50 transition duration-150 ease-in-out">
                        <span class="flex items-center"><i class="fas fa-calendar-plus mr-2 text-blue-600"></i>Make a Reservation</span>
                        <i class="fas fa-chevron-right text-blue-600"></i>
                    </a>
                    <a href="/menu" class="flex items-center justify-between p-4 border-b border-gray-200 hover:bg-gray-50 transition duration-150 ease-in-out">
                        <span class="flex items-center"><i class="fas fa-utensils mr-2 text-blue-600"></i>View Menu</span>
                        <i class="fas fa-chevron-right text-blue-600"></i>
                    </a>
                    <a href="/profile" class="flex items-center justify-between p-4 border-b border-gray-200 hover:bg-gray-50 transition duration-150 ease-in-out">
                        <span class="flex items-center"><i class="fas fa-user mr-2 text-blue-600"></i>View Profile</span>
                        <i class="fas fa-chevron-right text-blue-600"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <div class="w-full">
            <div class="bg-white shadow-md rounded-lg h-full p-6">
                <h5 class="text-xl font-semibold text-gray-800 mb-4">Monthly Revenue Overview</h5>
                <canvas id="monthlyRevenueChart"></canvas>
            </div>
        </div>
    </div>

    <div class="">
        <div class="w-full">
            <div class="bg-white shadow-md rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h5 class="text-xl font-semibold text-gray-800">Reservations</h5>
                    <div class="flex space-x-2">
                        <button type="button" class="px-4 py-2 text-sm font-medium rounded-md border border-blue-600 text-blue-600 bg-white hover:bg-blue-600 hover:text-white transition-colors duration-200 active" data-filter="all">All</button>
                        <button type="button" class="px-4 py-2 text-sm font-medium rounded-md border border-blue-600 text-blue-600 bg-white hover:bg-blue-600 hover:text-white transition-colors duration-200" data-filter="active">Active</button>
                        <button type="button" class="px-4 py-2 text-sm font-medium rounded-md border border-blue-600 text-blue-600 bg-white hover:bg-blue-600 hover:text-white transition-colors duration-200" data-filter="past">Past</button>
                    </div>
                </div>
                <?php if (!empty($upcomingReservations)): ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-red-600">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Time</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Table Size</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Payment</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Action</th>
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
                                    <tr class="reservation-row" data-status="<?php echo $isPast ? 'past' : 'active'; ?>">
                                        <td class="px-6 py-4 whitespace-nowrap"><?php echo date('F d, Y', strtotime($reservation['day'])); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap"><?php echo date('h:i A', strtotime($reservation['time'])); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($reservation['table_size']); ?> Pax</td>
                                        <td class="px-6 py-4 whitespace-nowrap"><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $statusClass; ?> text-white"><?php echo $status; ?></span></td>
                                        <td class="px-6 py-4 whitespace-nowrap"><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $paymentClass; ?> text-white"><?php echo $paymentStatus; ?></span></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <?php if ($reservation['status'] === 'pending'): ?>
                                                <a href="#" class="text-indigo-600 hover:text-indigo-900 update-btn"
                                                   data-id="<?php echo $reservation['id']; ?>"
                                                   data-day="<?php echo htmlspecialchars($reservation['day']); ?>"
                                                   data-time="<?php echo htmlspecialchars($reservation['time']); ?>"
                                                   data-table_size="<?php echo htmlspecialchars($reservation['table_size']); ?>">
                                                   Update
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($reservation['status'] === 'accepted'): ?>
                                                <button class="px-2 py-1 text-xs font-medium text-white bg-green-500 rounded-md cursor-not-allowed opacity-50" disabled>Confirmed</button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8">
                        <i class="fas fa-calendar-times fa-3x text-gray-400 mb-4"></i>
                        <p class="text-gray-600 mb-4">No reservations found.</p>
                        <a href="/reservation" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">Make a Reservation</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Edit Reservation Modal -->
<div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" id="editReservationModal">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center pb-3">
            <h5 class="text-xl font-semibold text-gray-800" id="editReservationModalLabel">Edit Reservation</h5>
            <button type="button" class="text-gray-400 hover:text-gray-600" data-bs-dismiss="modal" aria-label="Close">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form id="editReservationForm" enctype="multipart/form-data">
            <input type="hidden" name="id" id="editReservationId">
            <div class="mb-4">
                <label for="editDay" class="block text-gray-700 text-sm font-bold mb-2">Date</label>
                <input type="date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="day" id="editDay" required>
            </div>
            <div class="mb-4">
                <label for="editTime" class="block text-gray-700 text-sm font-bold mb-2">Time</label>
                <input type="time" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="time" id="editTime" required>
            </div>
            <div class="mb-4">
                <label for="editTableSize" class="block text-gray-700 text-sm font-bold mb-2">Table Size</label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="table_size" id="editTableSize" required>
                    <option value="2">2 Pax</option>
                    <option value="4">4 Pax</option>
                    <option value="6">6 Pax</option>
                    <option value="10">10 Pax</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="receipt" class="block text-gray-700 text-sm font-bold mb-2">Upload Receipt</label>
                <input type="file" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="receipt" id="receipt" accept="image/*,.pdf">
                <small class="text-gray-500 text-xs italic">Accepted formats: JPG, PNG, PDF</small>
            </div>
            <div class="flex items-center justify-end space-x-2">
                <button type="button" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-400 transition duration-200" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<style>
/* No custom styles needed beyond Tailwind for basic layout */
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Modal functionality for Tailwind CSS
    const editReservationModal = document.getElementById('editReservationModal');
    const updateButtons = document.querySelectorAll('.update-btn');
    const closeModalButtons = document.querySelectorAll('[data-bs-dismiss="modal"]');
    
    function showModal() {
        editReservationModal.classList.remove('hidden');
    }

    function hideModal() {
        editReservationModal.classList.add('hidden');
    }

    updateButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('editReservationId').value = this.getAttribute('data-id');
            document.getElementById('editDay').value = this.getAttribute('data-day');
            document.getElementById('editTime').value = this.getAttribute('data-time').slice(0, 5);
            document.getElementById('editTableSize').value = this.getAttribute('data-table_size');
            showModal();
        });
    });

    closeModalButtons.forEach(button => {
        button.addEventListener('click', hideModal);
    });

    // Close modal if clicked outside
    editReservationModal.addEventListener('click', function(e) {
        if (e.target === editReservationModal) {
            hideModal();
        }
    });
    
    // Monthly Revenue Chart
    const ctx = document.getElementById('monthlyRevenueChart').getContext('2d');
    const monthlyRevenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($chartLabels); ?>,
            datasets: [{
                label: 'Total Revenue (₱)',
                data: <?php echo json_encode($chartData); ?>,
                borderColor: '#dc3545',
                backgroundColor: 'rgba(220, 53, 69, 0.2)',
                tension: 0.1,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Total Revenue Per Month'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Revenue (₱)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Month'
                    }
                }
            }
        }
    });

    // Filter buttons functionality
    document.querySelectorAll('[data-filter]').forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.dataset.filter;
            
            // Update active button
            document.querySelectorAll('[data-filter]').forEach(btn => {
                btn.classList.remove('active');
                btn.classList.remove('bg-blue-600');
                btn.classList.remove('text-white');
                btn.classList.add('bg-white');
                btn.classList.add('text-blue-600');
            });
            this.classList.add('active');
            this.classList.remove('bg-white');
            this.classList.remove('text-blue-600');
            this.classList.add('bg-blue-600');
            this.classList.add('text-white');
            
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
    
    // Original Bootstrap modal JS removed

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
                hideModal(); // Use custom hideModal function
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message,
                    confirmButtonColor: '#0d6efd'
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: data.message || 'Failed to update reservation. Please try again.',
                    confirmButtonColor: '#0d6efd'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'An unexpected error occurred. Please try again.',
                confirmButtonColor: '#0d6efd'
            });
        });
    });
});
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/layout.php';
?>