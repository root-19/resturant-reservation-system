<?php
date_default_timezone_set('Asia/Manila');
require_once __DIR__ . '/../../../config/database.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require __DIR__ . '/../../../vendor/autoload.php'; 

$pdo = Database::connect();

// Pagination Setup
$perPage = 3;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $perPage;

// Add this after the database connection
$statusFilter = isset($_GET['status']) ? $_GET['status'] : 'all';

// Get total count
$totalStmt = $pdo->query("SELECT COUNT(*) FROM reservations");
$totalRows = $totalStmt->fetchColumn();
$totalPages = ceil($totalRows / $perPage);

// Fetch reservations with limit
$stmt = $pdo->prepare("
    SELECT *, 
    CASE 
        WHEN status = 'accepted' THEN 'accepted'
        WHEN status = 'rejected' THEN 'rejected'
        WHEN image_path IS NULL THEN 'pending'
        WHEN image_path IS NOT NULL AND status IS NULL THEN 'pending_accept'
        ELSE 'pending'
    END as reservation_status
    FROM reservations 
    WHERE 
        CASE 
            WHEN :status = 'pending' THEN (status IS NULL OR status = 'pending')
            WHEN :status = 'accepted' THEN status = 'accepted'
            WHEN :status = 'rejected' THEN status = 'rejected'
            ELSE 1=1
        END
    ORDER BY 
        CASE 
            WHEN status = 'accepted' THEN 1
            WHEN status = 'rejected' THEN 2
            ELSE 0 
        END,
        day DESC, 
        time DESC 
    LIMIT :limit OFFSET :offset
");

$stmt->bindValue(':status', $statusFilter, PDO::PARAM_STR);
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

$debug = $pdo->query("SELECT id, created_at, status FROM reservations")->fetchAll(PDO::FETCH_ASSOC);
// echo '<pre>'; print_r($debug); echo '</pre>';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reservationId = $_POST['id'];
    $email = $_POST['email'];
    $name = $_POST['name'];

    if (isset($_POST['accept'])) {
        $tableNumber = rand(1, 20); // Random table number

        // Update reservation to assign table number
        $updateStmt = $pdo->prepare("UPDATE reservations SET status = 'accepted', table_number = ? WHERE id = ?");
        $updateStmt->execute([$tableNumber, $reservationId]);

        $subject = "Reservation Accepted";
        $message = "Hi $name,\n\nYour reservation has been accepted. Your table number is: $tableNumber. We look forward to serving you!";

        // Send Email using PHPMailer
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; 
            $mail->SMTPAuth = true;
            $mail->Username = 'hperformanceexhaust@gmail.com';
            $mail->Password = 'wolv wvyy chhl rvvm';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            // Recipients
            $mail->setFrom('hperformanceexhaust@gmail.com', 'Reservation System');
            $mail->addAddress($email, $name);

            // Content
            $mail->isHTML(false);
            $mail->Subject = $subject;
            $mail->Body    = $message;

            $mail->send();
        } catch (Exception $e) {
            error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        }
    } elseif (isset($_POST['reject'])) {
        $updateStmt = $pdo->prepare("UPDATE reservations SET status = 'rejected' WHERE id = ?");
        $updateStmt->execute([$reservationId]);
        // No email notification for rejected reservations
    }

    // Refresh to avoid resubmission
    $_SESSION['success_message'] = 'Reservation status updated!';
    if (isset($_POST['reject'])) {
        header("Location: ?status=rejected");
    } else {
        header("Location: " . $_SERVER['REQUEST_URI']);
    }
    exit;

}

include "layout/sidebar.php";
?>


<!-- Table Container -->
<main class="p-6 bg-gray-50">
  <div class="max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-800">Reservation Management</h1>
      <p class="text-gray-600">Manage and track all restaurant reservations</p>
    </div>

    <!-- Status Filters -->
    <div class="mb-6 flex space-x-4">
      <a href="?status=all" 
         class="px-4 py-2 <?php echo $statusFilter === 'all' ? 'bg-indigo-600' : 'bg-indigo-500'; ?> text-white rounded-lg hover:bg-indigo-700 transition-colors">
        All Reservations
      </a>
      <a href="?status=pending" 
         class="px-4 py-2 <?php echo $statusFilter === 'pending' ? 'bg-yellow-500' : 'bg-yellow-400'; ?> text-white rounded-lg hover:bg-yellow-600 transition-colors">
        Pending
      </a>
      <a href="?status=accepted" 
         class="px-4 py-2 <?php echo $statusFilter === 'accepted' ? 'bg-green-600' : 'bg-green-400'; ?> text-white rounded-lg hover:bg-green-700 transition-colors">
        Accepted
      </a>
      <a href="?status=rejected" 
         class="px-4 py-2 <?php echo $statusFilter === 'rejected' ? 'bg-red-600' : 'bg-red-400'; ?> text-white rounded-lg hover:bg-red-700 transition-colors">
        Rejected
      </a>
    </div>

    <!-- Main Table Container -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer Info</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reservation Details</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <?php if (count($reservations) > 0): ?>
              <?php foreach ($reservations as $index => $row): ?>
                <tr class="hover:bg-gray-50 transition-colors">
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <?php echo $offset + $index + 1; ?>
                  </td>
                  
                  <!-- Customer Info Column -->
                  <td class="px-6 py-4">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center">
                        <span class="text-gray-500 font-medium"><?php echo substr($row['name'], 0, 1); ?></span>
                      </div>
                      <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($row['name']); ?></div>
                        <div class="text-sm text-gray-500"><?php echo htmlspecialchars($row['email']); ?></div>
                      </div>
                    </div>
                  </td>

                  <!-- Reservation Details Column -->
                  <td class="px-6 py-4">
                    <div class="text-sm text-gray-900">
                      <div class="flex items-center space-x-2">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span><?php echo htmlspecialchars($row['day']); ?></span>
                      </div>
                      <div class="flex items-center space-x-2 mt-1">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span><?php echo date('h:i A', strtotime($row['time'])); ?></span>
                      </div>
                      <div class="flex items-center space-x-2 mt-1">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span><?php echo htmlspecialchars($row['table_size']); ?> Pax</span>
                      </div>
                    </div>
                  </td>

                  <!-- Time Left Column -->
                  <td class="px-6 py-4 whitespace-nowrap">
                    <?php
                      $createdAt = strtotime($row['created_at']);
                      $now = time();
                      $timeLeft = max(0, 10800 - ($now - $createdAt)); // 10800 seconds = 3 hours
                      $status = $row['status'];
                    ?>
                    <?php if ($status === 'accepted'): ?>
                      <span class="text-emerald-600">Confirmed</span>
                    <?php elseif ($status === 'rejected'): ?>
                      <span class="text-rose-600">Rejected</span>
                    <?php elseif ($row['image_path'] === null): ?>
                      <span class="text-red-600 font-medium">Incomplete</span>
                    <?php elseif ($row['image_path'] !== null): ?>
                      <span class="text-green-600 font-medium">Complete</span>
                    <?php endif; ?>
                  </td>

                  <!-- Status Column -->
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 border border-gray-300">
                      <?php echo htmlspecialchars($row['status'] ?? 'N/A'); ?>
                    </span>
                  </td>

                  <!-- Action Column -->
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <?php
                      if ($row['status'] === 'accepted') {
                          echo '<span class="text-emerald-600 font-medium">Reservation Confirmed</span>';
                      } elseif ($row['status'] === 'rejected') {
                          echo '<span class="text-rose-600 font-medium">Reservation Rejected</span>';
                      } elseif ($row['image_path'] === null && ($row['status'] === null || $row['status'] === 'pending')) {
                          // Payment incomplete, show Reject
                    ?>
                          <form method="POST" class="inline">
                            <input type="hidden" name="id" value="<?= $row['id']; ?>">
                            <input type="hidden" name="email" value="<?= $row['email']; ?>">
                            <input type="hidden" name="name" value="<?= $row['name']; ?>">
                            <button type="submit" name="reject" class="text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200 px-4 py-2 rounded-lg transition-colors font-medium">
                              Reject
                            </button>
                          </form>
                    <?php
                      } elseif ($row['image_path'] !== null && ($row['status'] === null || $row['status'] === 'pending' || $row['status'] === 'pending_accept')) {
                          // Payment complete, show Accept
                    ?>
                          <form method="POST" class="inline">
                            <input type="hidden" name="id" value="<?= $row['id']; ?>">
                            <input type="hidden" name="email" value="<?= $row['email']; ?>">
                            <input type="hidden" name="name" value="<?= $row['name']; ?>">
                            <button type="submit" name="accept" class="text-emerald-600 hover:text-emerald-900 bg-emerald-100 hover:bg-emerald-200 px-4 py-2 rounded-lg transition-colors font-medium">
                              Accept
                            </button>
                          </form>
                    <?php
                      }
                    ?>
                  </td>
                </tr>

                <!-- Order Details Dropdown -->
                <?php
                  $stmtItems = $pdo->prepare("
                    SELECT ci.*, mi.food_name, mi.price 
                    FROM cart_items ci 
                    JOIN menu_items mi ON ci.menu_item_id = mi.id 
                    WHERE ci.reservation_id = ?
                  ");
                  $stmtItems->execute([$row['id']]);
                  $cartItems = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
                ?>

                <?php if (count($cartItems) > 0): ?>
                  <tr class="bg-gray-50">
                    <td colspan="5" class="px-6 py-4">
                      <div class="bg-white rounded-lg shadow-sm p-4">
                        <div class="flex items-center justify-between mb-4">
                          <h3 class="text-lg font-semibold text-gray-900">Order Details</h3>
                          <button onclick="toggleOrderDetails(<?php echo $row['id']; ?>)" 
                                  class="text-blue-600 hover:text-blue-800 transition-colors">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                          </button>
                        </div>
                        <div id="orderDetails-<?php echo $row['id']; ?>" class="hidden">
                          <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                              <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                              </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                              <?php foreach ($cartItems as $item): ?>
                                <tr>
                                  <td class="px-4 py-2 text-sm text-gray-900"><?php echo htmlspecialchars($item['food_name']); ?></td>
                                  <td class="px-4 py-2 text-sm text-gray-500">₱<?php echo number_format($item['price'], 2); ?></td>
                                  <td class="px-4 py-2 text-sm text-gray-500"><?php echo $item['quantity']; ?></td>
                                  <td class="px-4 py-2 text-sm text-gray-900">₱<?php echo number_format($item['total_price'], 2); ?></td>
                                </tr>
                              <?php endforeach; ?>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </td>
                  </tr>
                <?php endif; ?>

                <?php
                  // DEBUG: Output created_at and timestamps
                  echo '<!-- created_at: ' . $row['created_at'] . ' | now: ' . date('Y-m-d H:i:s') . ' -->';
                ?>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                  No reservations found.
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex justify-center items-center space-x-4">
      <?php if ($page > 1): ?>
        <a href="?page=<?php echo $page - 1; ?>" 
           class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
          Previous
        </a>
      <?php endif; ?>

      <span class="text-gray-700 font-medium">
        Page <?php echo $page; ?> of <?php echo $totalPages; ?>
      </span>

      <?php if ($page < $totalPages): ?>
        <a href="?page=<?php echo $page + 1; ?>" 
           class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
          Next
        </a>
      <?php endif; ?>
    </div>
  </div>
</main>




<!-- Add SweetAlert2 CDN if not already included -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if (isset($_SESSION['success_message'])): ?>
<script>
Swal.fire({
  icon: 'success',
  title: '<?= $_SESSION['success_message'] ?>',
  showConfirmButton: false,
  timer: 2000
});
</script>
<?php unset($_SESSION['success_message']); ?>
<?php endif; ?>


<script>
function toggleOrderDetails(id) {
  const detailsElement = document.getElementById(`orderDetails-${id}`);
  detailsElement.classList.toggle('hidden');
}

// Add smooth scrolling for pagination
document.addEventListener("DOMContentLoaded", function () {
  if (window.location.search.includes('page=')) {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }
});
</script>
