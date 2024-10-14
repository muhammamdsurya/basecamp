<?php
require '../../functions.php'; // Ensure this file sets up your database connection
header('Content-Type: application/json');

// Query to get available data for the specified date
$query = " SELECT * FROM lapangan";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Return the data as a JSON response
echo json_encode(['success' => true, 'data' => $data]);

$stmt->close();
$conn->close();
