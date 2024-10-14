<?php
require '../../functions.php'; // Database connection
header('Content-Type: application/json');


$id = $_POST['id'];
$bayar = $_POST['bayar'];

// Fetch current nominal and total for validation
$stmt = $conn->prepare("SELECT nominal, total FROM bookings WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($nominal, $total);
$stmt->fetch();
$stmt->close();

// Update nominal
$newNominal = $nominal + $bayar;

if ($newNominal >= $total) {
    $status = 'Diterima';
} else {
    $status = 'Belum Lunas';
}

// Update the database
$stmt = $conn->prepare("UPDATE bookings SET nominal = ?, status = ? WHERE id = ?");
$stmt->bind_param("ssi", $newNominal, $status, $id);
$stmt->execute();

echo json_encode(['status' => 'success', 'desc' => $status ]);

$stmt->close(); // Close statement after execution


?>
