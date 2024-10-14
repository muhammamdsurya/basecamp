<?php
require '../../functions.php'; // Ensure this file sets up your database connection
header('Content-Type: application/json');

// Read and decode JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON.']);
    exit;
}

// Validate and extract input
$id = $input['id'] ?? '';

if (empty($id)) {
    echo json_encode(['success' => false, 'message' => 'ID is required.']);
    exit;
}

// Prepare and execute SQL statement to delete the record
$stmt = $conn->prepare('DELETE FROM available_times WHERE id = ?');
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Data Berhasil dihapus!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete data: ' . $stmt->error]);
}

// Close connections
$stmt->close();
$conn->close();
?>
