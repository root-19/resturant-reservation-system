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

<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-white shadow-sm">
                <div class="card-body">
                    <h2 class="card-title text-dark mb-0">Welcome, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Guest'); ?>!</h2>
                    <p class="text-muted mt-2">Manage your reservations and view your dining history.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-center h-100 border-0 shadow-sm">
                <div class="card-body">
                    <i class="fas fa-calendar-check fa-2x text-primary mb-3"></i>
                    <h5 class="card-title">Active Reservations</h5>
                    <h2 class="display-4"><?php echo $activeReservations ?? 0; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center h-100 border-0 shadow-sm">
                <div class="card-body">
                    <i class="fas fa-history fa-2x text-success mb-3"></i>
                    <h5 class="card-title">Past Visits</h5>
                    <h2 class="display-4"><?php echo $pastVisits ?? 0; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center h-100 border-0 shadow-sm">
                <div class="card-body">
                    <i class="fas fa-wallet fa-2x text-info mb-3"></i>
                    <h5 class="card-title">Total Spent</h5>
                    <h2 class="display-4">₱<?php echo number_format($totalSpent, 2); ?></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="/reservation" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-calendar-plus me-2 text-primary"></i>Make a Reservation</span>
                            <i class="fas fa-chevron-right text-primary"></i>
                        </a>
                        <a href="/menu" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-utensils me-2 text-primary"></i>View Menu</span>
                            <i class="fas fa-chevron-right text-primary"></i>
                        </a>
                        <a href="/profile" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-user me-2 text-primary"></i>View Profile</span>
                            <i class="fas fa-chevron-right text-primary"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Reservations</h5>
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-primary btn-sm active" data-filter="all">All</button>
                        <button type="button" class="btn btn-outline-primary btn-sm" data-filter="active">Active</button>
                        <button type="button" class="btn btn-outline-primary btn-sm" data-filter="past">Past</button>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (!empty($upcomingReservations)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Table Size</th>
                                        <th>Status</th>
                                        <th>Payment</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($upcomingReservations as $reservation):
                                        $reservationDateTime = strtotime($reservation['day'] . ' ' . $reservation['time']);
                                        $isPast = $reservationDateTime < time();

                                        if ($reservation['status'] === 'rejected') {
                                            $status = 'Rejected';
                                            $statusClass = 'bg-secondary';
                                        } elseif ($reservation['status'] === 'accepted') {
                                            $status = 'Accepted';
                                            $statusClass = 'bg-success';
                                        } elseif ($reservation['status'] === 'pending') {
                                            $status = 'Pending';
                                            $statusClass = 'bg-warning';
                                        } elseif ($reservation['status'] === 'completed') {
                                            $status = 'Completed';
                                            $statusClass = 'bg-info';
                                        } else {
                                            $status = $isPast ? 'Completed' : 'Upcoming';
                                            $statusClass = $isPast ? 'bg-info' : 'bg-primary';
                                        }

                                        $paymentStatus = $reservation['image_path'] ? 'Paid' : 'Unpaid';
                                        $paymentClass = $reservation['image_path'] ? 'bg-success' : 'bg-warning';
                                    ?>
                                        <tr class="reservation-row" data-status="<?php echo $isPast ? 'past' : 'active'; ?>">
                                            <td><?php echo date('F d, Y', strtotime($reservation['day'])); ?></td>
                                            <td><?php echo date('h:i A', strtotime($reservation['time'])); ?></td>
                                            <td><?php echo htmlspecialchars($reservation['table_size']); ?> Pax</td>
                                            <td><span class="badge <?php echo $statusClass; ?>"><?php echo $status; ?></span></td>
                                            <td><span class="badge <?php echo $paymentClass; ?>"><?php echo $paymentStatus; ?></span></td>
                                            <td>
                                                <?php if ($reservation['status'] === 'pending'): ?>
                                                    <a href="#" class="btn btn-sm btn-primary update-btn" 
                                                       data-id="<?php echo $reservation['id']; ?>"
                                                       data-day="<?php echo htmlspecialchars($reservation['day']); ?>"
                                                       data-time="<?php echo htmlspecialchars($reservation['time']); ?>"
                                                       data-table_size="<?php echo htmlspecialchars($reservation['table_size']); ?>">
                                                       Update
                                                    </a>
                                                <?php endif; ?>
                                                <?php if ($reservation['status'] === 'accepted'): ?>
                                                    <button class="btn btn-sm btn-success" disabled>Confirmed</button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No reservations found.</p>
                            <a href="/reservation" class="btn btn-primary">Make a Reservation</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Reservation Modal -->
<div class="modal fade" id="editReservationModal" tabindex="-1" aria-labelledby="editReservationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editReservationModalLabel">Edit Reservation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editReservationForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="id" id="editReservationId">
                    <div class="mb-3">
                        <label for="editDay" class="form-label">Date</label>
                        <input type="date" class="form-control" name="day" id="editDay" required>
                    </div>
                    <div class="mb-3">
                        <label for="editTime" class="form-label">Time</label>
                        <input type="time" class="form-control" name="time" id="editTime" required>
                    </div>
                    <div class="mb-3">
                        <label for="editTableSize" class="form-label">Table Size</label>
                        <select class="form-control" name="table_size" id="editTableSize" required>
                            <option value="2">2 Pax</option>
                            <option value="4">4 Pax</option>
                            <option value="6">6 Pax</option>
                            <option value="10">10 Pax</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="receipt" class="form-label">Upload Receipt</label>
                        <input type="file" class="form-control" name="receipt" id="receipt" accept="image/*,.pdf">
                        <small class="text-muted">Accepted formats: JPG, PNG, PDF</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .card {
        transition: transform 0.2s;
    }
    .card:hover {
        transform: translateY(-2px);
    }
    .list-group-item {
        border: none;
        border-bottom: 1px solid #eee;
    }
    .list-group-item:last-child {
        border-bottom: none;
    }
    .list-group-item:hover {
        background-color: #f8f9fa;
    }
    .table {
        margin-bottom: 0;
    }
    .badge {
        padding: 0.5em 0.8em;
    }
    .btn-group .btn {
        border-radius: 0;
    }
    .btn-group .btn:first-child {
        border-top-left-radius: 0.25rem;
        border-bottom-left-radius: 0.25rem;
    }
    .btn-group .btn:last-child {
        border-top-right-radius: 0.25rem;
        border-bottom-right-radius: 0.25rem;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var editModal = new bootstrap.Modal(document.getElementById('editReservationModal'));
    
    // Filter buttons functionality
    document.querySelectorAll('[data-filter]').forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.dataset.filter;
            
            // Update active button
            document.querySelectorAll('[data-filter]').forEach(btn => {
                btn.classList.remove('active');
            });
            this.classList.add('active');
            
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
            editModal.show();
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
                const modal = bootstrap.Modal.getInstance(document.getElementById('editReservationModal'));
                modal.hide();
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