<?php
require '../../functions.php'; // Ensure this file sets up your database connection
header('Content-Type: application/json');

// Check request method
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $lapangan_id = $_GET['id'] ?? '';
    // Fetch data from the database
    $query = "SELECT * FROM available_times WHERE lapangan_id = ? "; // Adjust the query as needed
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param('s', $lapangan_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $stmt->close();
        echo json_encode(['success' => true, 'data' => $data]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to prepare statement: ' . $conn->error]);
    }
    $conn->close();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read and decode JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['success' => false, 'message' => 'Invalid JSON.']);
        exit;
    }

    // Validate and extract input
    $lapangan_id = $input['id'] ?? '';
    $date = $input['date'] ?? '';
    $harga = $input['harga'] ?? '';
    $start_time = $input['start_time'] ?? '';
    $end_time = $input['end_time'] ?? '';
    $diskon = $input['diskon'] ?? '';
    $total = $input['total'] ?? '';

    if (empty($lapangan_id) || empty($date) || empty($harga) || empty($start_time) || empty($end_time) || empty($diskon)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }

    // Check for existing record
    $checkQuery = "SELECT COUNT(*) FROM available_times WHERE lapangan_id = ? AND tanggal = ? AND start_time = ? AND end_time = ?";
    if ($stmt = $conn->prepare($checkQuery)) {
        $stmt->bind_param('ssss', $lapangan_id, $date, $start_time, $end_time);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            echo json_encode(['success' => false, 'message' => 'Record already exists.']);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to prepare statement: ' . $conn->error]);
        exit;
    }

    // Prepare and execute SQL statement
    $insertQuery = 'INSERT INTO available_times (lapangan_id, tanggal, harga, start_time, end_time, diskon, total ) VALUES (?, ?, ?, ?, ?, ?, ?)';
    if ($stmt = $conn->prepare($insertQuery)) {
        $stmt->bind_param('sssssii', $lapangan_id, $date, $harga, $start_time, $end_time, $diskon, $total);
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add data: ' . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to prepare statement: ' . $conn->error]);
    }

    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
