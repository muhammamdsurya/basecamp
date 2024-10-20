<?php
require '../functions.php'; // Ensure this file sets up your database connection
header('Content-Type: application/json');

// Get the parameters
$id = isset($_GET['id']) ? trim($_GET['id']) : ''; // Trim whitespace
$date = isset($_GET['dates']) ? $_GET['dates'] : [];

if (empty($id)) {
    echo json_encode(['success' => false, 'message' => 'ID is required.']);
    exit;
}


if (empty($date)) {
    echo json_encode(['success' => false, 'message' => 'Date is required.']);
    exit;
}

// Query to get available times for the specified date
$query = "SELECT start_time, end_time, status FROM available_times WHERE tanggal = ? AND lapangan_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('si', $date, $id);
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