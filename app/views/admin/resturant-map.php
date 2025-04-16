<?php
date_default_timezone_set('Asia/Manila'); // set Philippine time
require_once __DIR__ . '/../../../config/database.php';

$pdo = Database::connect();

$stmt = $pdo->prepare("SELECT * FROM reservations ORDER BY day DESC, time DESC");
$stmt->execute();
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

$upcoming = array_filter($reservations, function ($res) {
    $reservationDateTime = strtotime($res['day'] . ' ' . $res['time']);
    return $reservationDateTime >= time();
});

function groupByTableSize($reservations, $size) {
    return array_filter($reservations, function ($res) use ($size) {
        return intval($res['table_size']) === $size;
    });
}

$tables = [
    '2 Pax Table' => groupByTableSize($upcoming, 2),
    '4 Pax Table' => groupByTableSize($upcoming, 4),
    '6 Pax Table' => groupByTableSize($upcoming, 6),
    '10 Pax Table' => groupByTableSize($upcoming, 10),
];

include "layout/sidebar.php";
?>

<main class="p-8 bg-gray-50 min-h-screen">
  <h1 class="text-3xl font-bold mb-10 text-center">Restaurant Map View</h1>

  <!-- Top Tables -->
  <div class="flex justify-center flex-wrap gap-20 mb-24">
    <?php foreach (array_slice(array_keys($tables), 0, 2) as $key): ?>
      <?php $reservationsGroup = $tables[$key]; ?>
      <div class="flex flex-col items-center relative">
        <!-- Table Box -->
        <div class="w-32 h-20 bg-green-700 text-white font-semibold flex items-center justify-center rounded-md shadow-md z-10">
          <?= $key ?>
        </div>

        <!-- Line Connector -->
        <div class="h-24 w-1 bg-gray-400 absolute top-20 z-0"></div>

        <!-- Reservation Dots -->
        <div class="flex flex-col items-center mt-28 space-y-6 relative z-10">
          <?php if (count($reservationsGroup) > 0): ?>
            <?php foreach ($reservationsGroup as $res): ?>
              <div
                class="w-4 h-4 bg-blue-500 rounded-full hover:bg-blue-700 cursor-pointer"
                onmouseenter="showTooltip(event, <?= htmlspecialchars(json_encode([
                  'name' => $res['name'],
                  'email' => $res['email'],
                  'day' => date('F d, Y', strtotime($res['day'])),
                  'time' => date('h:i A', strtotime($res['time'])),
                ])) ?>)"
                onmouseleave="hideTooltip()"
              ></div>
            <?php endforeach; ?>
          <?php else: ?>
            <p class="text-sm text-gray-400 italic">No reservations</p>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Bottom Tables -->
  <div class="flex justify-center flex-wrap gap-20 mt-24">
    <?php foreach (array_slice(array_keys($tables), 2, 2) as $key): ?>
      <?php $reservationsGroup = $tables[$key]; ?>
      <div class="flex flex-col items-center relative">
        <!-- Table Box -->
        <div class="w-32 h-20 bg-green-700 text-white font-semibold flex items-center justify-center rounded-md shadow-md z-10">
          <?= $key ?>
        </div>

        <!-- Line Connector -->
        <div class="h-24 w-1 bg-gray-400 absolute top-20 z-0"></div>

        <!-- Reservation Dots -->
        <div class="flex flex-col items-center mt-28 space-y-6 relative z-10">
          <?php if (count($reservationsGroup) > 0): ?>
            <?php foreach ($reservationsGroup as $res): ?>
              <div
                class="w-4 h-4 bg-blue-500 rounded-full hover:bg-blue-700 cursor-pointer"
                onmouseenter="showTooltip(event, <?= htmlspecialchars(json_encode([
                  'name' => $res['name'],
                  'email' => $res['email'],
                  'day' => date('F d, Y', strtotime($res['day'])),
                  'time' => date('h:i A', strtotime($res['time'])),
                ])) ?>)"
                onmouseleave="hideTooltip()"
              ></div>
            <?php endforeach; ?>
          <?php else: ?>
            <p class="text-sm text-gray-400 italic">No reservations</p>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Tooltip -->
  <div id="tooltip"
       class="hidden fixed bg-white text-sm shadow-lg rounded-lg p-4 border border-gray-200 z-50 w-64 pointer-events-none transition-opacity duration-200 ease-in-out">
    <p><strong>Name:</strong> <span id="tooltipName"></span></p>
    <!-- <p><strong>Email:</strong> <span id="tooltipEmail"></span></p> -->
    <p><strong>Date:</strong> <span id="tooltipDay"></span></p>
    <p><strong>Time:</strong> <span id="tooltipTime"></span></p>
  </div>
</main>


<script>
  const tooltip = document.getElementById("tooltip");
  const nameEl = document.getElementById("tooltipName");
//   const emailEl = document.getElementById("tooltipEmail");
  const dayEl = document.getElementById("tooltipDay");
  const timeEl = document.getElementById("tooltipTime");

  function showTooltip(e, data) {
    nameEl.textContent = data.name;
    // emailEl.textContent = data.email;
    dayEl.textContent = data.day;
    timeEl.textContent = data.time;

    tooltip.classList.remove("hidden");

    const tooltipWidth = tooltip.offsetWidth;
    const tooltipHeight = tooltip.offsetHeight;
    const pageX = e.pageX;
    const pageY = e.pageY;

    tooltip.style.top = (pageY - tooltipHeight - 10) + "px";
    tooltip.style.left = (pageX - tooltipWidth / 2) + "px";
  }

  function hideTooltip() {
    tooltip.classList.add("hidden");
  }

  document.addEventListener("mousemove", function(e) {
    if (!tooltip.classList.contains("hidden")) {
      tooltip.style.top = (e.pageY - tooltip.offsetHeight - 10) + "px";
      tooltip.style.left = (e.pageX - tooltip.offsetWidth / 2) + "px";
    }
  });
</script>
