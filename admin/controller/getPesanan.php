<?php
require '../../functions.php'; // Ensure this file sets up your database connection
header('Content-Type: application/json');

// Query to get available pesanan for the specified date
$query = " SELECT * FROM bookings
";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

$pesanan = [];
while ($row = $result->fetch_assoc()) {
    $pesanan[] = $row;
}

// Return the data as a JSON response
echo json_encode(['success' => true, 'data' => $pesanan]);

$stmt->close();
$conn->close();
