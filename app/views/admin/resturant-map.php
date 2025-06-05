<?php
date_default_timezone_set('Asia/Manila');
require_once __DIR__ . '/../../../config/database.php';

$pdo = Database::connect();

$stmt = $pdo->prepare("SELECT * FROM reservations WHERE status != 'pending' ORDER BY day DESC, time DESC");
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

<main class="p-8 bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-800 mb-3">Restaurant Map View</h1>
            <p class="text-gray-600">Real-time table occupancy and reservation status</p>
        </div>

        <!-- Top Tables -->
        <div class="flex justify-center flex-wrap gap-32 mb-32">
            <?php foreach (array_slice(array_keys($tables), 0, 2) as $key): ?>
                <?php $reservationsGroup = $tables[$key]; ?>
                <div class="flex flex-col items-center relative">
                    <!-- Table Box -->
                    <div class="w-40 h-24 bg-gradient-to-r from-green-600 to-green-700 text-white font-semibold flex items-center justify-center rounded-xl shadow-lg transform hover:scale-105 transition-transform duration-200 z-10">
                        <div class="text-center">
                            <div class="text-lg"><?= $key ?></div>
                            <div class="text-sm opacity-80"><?= count($reservationsGroup) ?> Reservations</div>
                        </div>
                    </div>

                    <!-- Line Connector -->
                    <div class="h-32 w-1 bg-gradient-to-b from-gray-300 to-gray-400 absolute top-24 z-0"></div>

                    <!-- Reservation Dots -->
                    <div class="flex flex-col items-center mt-36 space-y-8 relative z-10">
                        <?php if (count($reservationsGroup) > 0): ?>
                            <?php foreach ($reservationsGroup as $res): ?>
                                <div
                                    class="w-5 h-5 bg-blue-500 rounded-full hover:bg-blue-600 cursor-pointer shadow-md transform hover:scale-110 transition-all duration-200"
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
                            <p class="text-sm text-gray-500 italic bg-white px-4 py-2 rounded-full shadow-sm">No reservations</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Bottom Tables -->
        <div class="flex justify-center flex-wrap gap-32">
            <?php foreach (array_slice(array_keys($tables), 2, 2) as $key): ?>
                <?php $reservationsGroup = $tables[$key]; ?>
                <div class="flex flex-col items-center relative">
                    <!-- Table Box -->
                    <div class="w-40 h-24 bg-gradient-to-r from-green-600 to-green-700 text-white font-semibold flex items-center justify-center rounded-xl shadow-lg transform hover:scale-105 transition-transform duration-200 z-10">
                        <div class="text-center">
                            <div class="text-lg"><?= $key ?></div>
                            <div class="text-sm opacity-80"><?= count($reservationsGroup) ?> Reservations</div>
                        </div>
                    </div>

                    <!-- Line Connector -->
                    <div class="h-32 w-1 bg-gradient-to-b from-gray-300 to-gray-400 absolute top-24 z-0"></div>

                    <!-- Reservation Dots -->
                    <div class="flex flex-col items-center mt-36 space-y-8 relative z-10">
                        <?php if (count($reservationsGroup) > 0): ?>
                            <?php foreach ($reservationsGroup as $res): ?>
                                <div
                                    class="w-5 h-5 bg-blue-500 rounded-full hover:bg-blue-600 cursor-pointer shadow-md transform hover:scale-110 transition-all duration-200"
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
                            <p class="text-sm text-gray-500 italic bg-white px-4 py-2 rounded-full shadow-sm">No reservations</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Tooltip -->
    <div id="tooltip"
         class="hidden fixed bg-white text-sm shadow-xl rounded-lg p-4 border border-gray-200 z-50 w-72 pointer-events-none transition-all duration-200 ease-in-out backdrop-blur-sm bg-opacity-95">
        <div class="space-y-2">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <p class="font-semibold text-gray-800"><span id="tooltipName"></span></p>
            </div>
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p class="text-gray-600"><span id="tooltipDay"></span></p>
            </div>
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-gray-600"><span id="tooltipTime"></span></p>
            </div>
        </div>
    </div>
</main>

<script>
    const tooltip = document.getElementById("tooltip");
    const nameEl = document.getElementById("tooltipName");
    const dayEl = document.getElementById("tooltipDay");
    const timeEl = document.getElementById("tooltipTime");

    function showTooltip(e, data) {
        nameEl.textContent = data.name;
        dayEl.textContent = data.day;
        timeEl.textContent = data.time;

        tooltip.classList.remove("hidden");
        tooltip.classList.add("opacity-100");

        const tooltipWidth = tooltip.offsetWidth;
        const tooltipHeight = tooltip.offsetHeight;
        const pageX = e.pageX;
        const pageY = e.pageY;

        // Ensure tooltip stays within viewport
        const viewportWidth = window.innerWidth;
        const viewportHeight = window.innerHeight;

        let left = pageX - tooltipWidth / 2;
        let top = pageY - tooltipHeight - 10;

        // Adjust if tooltip would go off screen
        if (left < 10) left = 10;
        if (left + tooltipWidth > viewportWidth - 10) left = viewportWidth - tooltipWidth - 10;
        if (top < 10) top = pageY + 10;

        tooltip.style.top = `${top}px`;
        tooltip.style.left = `${left}px`;
    }

    function hideTooltip() {
        tooltip.classList.add("hidden");
        tooltip.classList.remove("opacity-100");
    }

    document.addEventListener("mousemove", function(e) {
        if (!tooltip.classList.contains("hidden")) {
            const tooltipWidth = tooltip.offsetWidth;
            const tooltipHeight = tooltip.offsetHeight;
            const viewportWidth = window.innerWidth;
            const viewportHeight = window.innerHeight;

            let left = e.pageX - tooltipWidth / 2;
            let top = e.pageY - tooltipHeight - 10;

            if (left < 10) left = 10;
            if (left + tooltipWidth > viewportWidth - 10) left = viewportWidth - tooltipWidth - 10;
            if (top < 10) top = e.pageY + 10;

            tooltip.style.top = `${top}px`;
            tooltip.style.left = `${left}px`;
        }
    });
</script>
