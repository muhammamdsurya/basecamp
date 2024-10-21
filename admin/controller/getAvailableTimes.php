<?php
require '../../functions.php'; // Ensure this file sets up your database connection
header('Content-Type: application/json');

// Get the parameters
$id = isset($_GET['id']) ? trim($_GET['id']) : ''; // Trim whitespace


if (empty($id)) {
    echo json_encode(['success' => false, 'message' => 'ID is required.']);
    exit;
}

// Query to get available times for the specified date
$query = "SELECT lapangan_id, tanggal, start_time, end_time, status,  normal_price.harga as harga, normal_price.diskon as diskon, normal_price.total as total, member_price.harga as harga_member, member_price.diskon as diskon_member, member_price.total as total_member FROM available_times 
LEFT JOIN normal_price ON available_times.id = normal_price.times_id
LEFT JOIN member_price ON available_times.id = member_price.times_id
          WHERE status = 'available' AND lapangan_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

$times = [];
while ($row = $result->fetch_assoc()) {
    $times[] = $row;
}

echo json_encode(['success' => true, 'data' => $times]);

$stmt->close();
$conn->close();
?>
