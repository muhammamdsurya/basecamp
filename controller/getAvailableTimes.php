<?php
require '../functions.php'; // Ensure this file sets up your database connection
header('Content-Type: application/json');

// Get the parameters
$id = isset($_GET['id']) ? trim($_GET['id']) : ''; // Trim whitespace
$dates = isset($_GET['dates']) ? $_GET['dates'] : [];

// Convert dates to an array if not already
if (!is_array($dates)) {
    $dates = explode(',', $dates); // Assuming dates are sent as a comma-separated string
}

// Filter out invalid dates and sanitize
$dates = array_filter($dates, function ($date) {
    $parsedDate = new DateTime($date);
    return $parsedDate && $parsedDate->format('Y-m-d') === $date;
});
$dates = array_map(function ($date) {
    global $conn;
    return $conn->real_escape_string($date);
}, $dates);

if (empty($id)) {
    echo json_encode(['success' => false, 'message' => 'ID is required.']);
    exit;
}

if (empty($dates)) {
    echo json_encode(['success' => false, 'message' => 'No valid dates provided.']);
    exit;
}

// Get the latest date from the array
$latestDate = max($dates);

// Query to get available times for the specified date
$query = "SELECT available_times.tanggal, available_times.start_time, available_times.end_time, available_times.status, lapangan.diskon AS lapangan_diskon,  normal_price.harga as harga, normal_price.diskon as diskon, normal_price.total as total, member_price.harga as harga_member, member_price.diskon as diskon_member, member_price.total as total_member
FROM available_times 
JOIN lapangan ON available_times.lapangan_id = lapangan.id
LEFT JOIN normal_price ON available_times.id = normal_price.times_id
LEFT JOIN member_price ON available_times.id = member_price.times_id
WHERE available_times.tanggal = ? AND available_times.status = 'available' AND available_times.lapangan_id = ? GROUP BY available_times.id;
";
$stmt = $conn->prepare($query);
$stmt->bind_param('si', $latestDate, $id);
$stmt->execute();
$result = $stmt->get_result();

$times = [];
while ($row = $result->fetch_assoc()) {
    $times[] = $row;
}

echo json_encode(['success' => true, 'data' => $times]);

$stmt->close();
$conn->close();
