<?php
require_once __DIR__ . '/../../../config/database.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require __DIR__ . '/../../../vendor/autoload.php'; 

$pdo = Database::connect();

// Pagination Setup
$perPage = 3;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $perPage;

// Get total count
$totalStmt = $pdo->query("SELECT COUNT(*) FROM reservations");
$totalRows = $totalStmt->fetchColumn();
$totalPages = ceil($totalRows / $perPage);

// Fetch reservations with limit
$stmt = $pdo->prepare("SELECT * FROM reservations ORDER BY day DESC, time DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reservationId = $_POST['id'];
    $email = $_POST['email'];
    $name = $_POST['name'];

    if (isset($_POST['accept'])) {
        $tableNumber = rand(1, 20); // Random table number

        // Update reservation to assign table number
        $updateStmt = $pdo->prepare("UPDATE reservations SET info = 'accepted', table_number = ? WHERE id = ?");
        $updateStmt->execute([$tableNumber, $reservationId]);

        $subject = "Reservation Accepted";
        $message = "Hi $name,\n\nYour reservation has been accepted. Your table number is: $tableNumber. We look forward to serving you!";
    } elseif (isset($_POST['reject'])) {
        $updateStmt = $pdo->prepare("UPDATE reservations SET info = 'rejected' WHERE id = ?");
        $updateStmt->execute([$reservationId]);

        $subject = "Reservation Rejected";
        $message = "Hi $name,\n\nWe regret to inform you that your reservation has been rejected. Please try booking again at another time.";
    }

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

    // Refresh to avoid resubmission
    $_SESSION['success_message'] = 'Reservation Accepted! Email has been sent.';
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;

}


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
    LIMIT :limit OFFSET :offset
");
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

include "layout/sidebar.php";
?>


<!-- Table Container -->
<main class="p-6">
  <div class="max-w-7x2 mx-auto bg-white rounded-lg shadow-md overflow-x-auto">
    <table class="min-w-full table-auto border-collapse border border-gray-300">
      <thead class="bg-blue-600 text-white">
        <tr>
          <th class="px-4 py-2 border">#</th>
          <th class="px-4 py-2 border">Name</th>
          <th class="px-4 py-2 border">Email</th>
          <th class="px-4 py-2 border">Table Size</th>
          <th class="px-4 py-2 border">Date</th>
          <th class="px-4 py-2 border">Time</th>
          <th class="px-4 py-2 border">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($reservations) > 0): ?>
         <?php foreach ($reservations as $index => $row): ?>
            <tr class="text-center border-t bg-gray-50">
              <td class="px-4 py-2 border"><?php echo $offset + $index + 1; ?></td>
              <td class="px-4 py-2 border"><?php echo htmlspecialchars($row['name']); ?></td>
              <td class="px-4 py-2 border"><?php echo htmlspecialchars($row['email']); ?></td>
              <td class="px-4 py-2 border"><?php echo htmlspecialchars($row['table_size']); ?> Pax</td>
              <td class="px-4 py-2 border"><?php echo htmlspecialchars($row['day']); ?></td>
              <td class="px-4 py-2 border"><?php echo htmlspecialchars($row['time']); ?></td>
              <td class="px-4 py-2 border">
              
              <?php if ($row['info'] === 'accepted' || $row['info'] === 'rejected'): ?>
        <button onclick="Swal.fire('Already Processed', 'This reservation has already been <?php echo $row['info']; ?>.', 'info')" class="bg-gray-400 text-white px-3 py-1 rounded cursor-not-allowed">
          <?php echo ucfirst($row['info']); ?>
        </button>
      <?php else: ?>
        <form method="POST">
          <input type="hidden" name="id" value="<?= $row['id']; ?>">
          <input type="hidden" name="email" value="<?= $row['email']; ?>">
          <input type="hidden" name="name" value="<?= $row['name']; ?>">

          <button type="submit" name="accept" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">
            Accept
          </button>

          <button type="submit" name="reject" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">
            Reject
          </button>
        </form>
      <?php endif; ?>

              </td>

              <td class="px-4 py-2 border">
                <?php if (!empty($row['image']) && file_exists($row['image'])): ?>
                  <img src="<?= '/uploads/' . basename($row['image']) ?>" class="w-24 h-24 object-cover rounded-md">
                <?php endif; ?>
              </td>
              
            </tr>

            <!-- Cart items -->
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
              <tr>
                <td colspan="7" class="px-4 py-2 bg-white border">
                  <div class="text-left font-medium mb-2 text-blue-600">Ordered Items:</div>
                  <table class="w-full text-sm border border-gray-300">
                    <thead class="bg-gray-200 text-gray-800">
                      <tr>
                        <th class="px-2 py-1 border">#</th>
                        <th class="px-2 py-1 border">Food Name</th>
                        <th class="px-2 py-1 border">Price</th>
                        <th class="px-2 py-1 border">Quantity</th>
                        <th class="px-2 py-1 border">Total</th>
                       </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($cartItems as $itemIndex => $item): ?>
                        <tr class="text-center">
                          <td class="px-2 py-1 border"><?php echo $itemIndex + 1; ?></td>
                          <td class="px-2 py-1 border"><?php echo htmlspecialchars($item['food_name']); ?></td>
                          <td class="px-2 py-1 border">₱<?php echo number_format($item['price'], 2); ?></td>
                          <td class="px-2 py-1 border"><?php echo $item['quantity']; ?></td>
                          <td class="px-2 py-1 border">₱<?php echo number_format($item['total_price'], 2); ?></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </td>
              </tr>
            <?php endif; ?>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="7" class="text-center py-6 text-gray-500">No reservations found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>

    <!-- Pagination Buttons -->
    <div class="mt-4 flex justify-center items-center space-x-4">
      <?php if ($page > 1): ?>
        <a href="?page=<?php echo $page - 1; ?>" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Previous</a>
      <?php endif; ?>

      <span class="text-gray-700 font-medium">Page <?php echo $page; ?> of <?php echo $totalPages; ?></span>

      <?php if ($page < $totalPages): ?>
        <a href="?page=<?php echo $page + 1; ?>" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Next</a>
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



  document.addEventListener("DOMContentLoaded", function () {
    if (window.location.search.includes('page=')) {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }
  });
</script>
