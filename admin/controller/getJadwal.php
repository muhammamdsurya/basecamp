<?php
require '../../functions.php'; // Ensure this file sets up your database connection
require_once 'PriceController.php';
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

            $times_id = $row['id']; // Assuming available_times has a times_id column
            
            // Fetch harga from normal_price
            $priceQuery = "SELECT * FROM normal_price WHERE times_id = ?";
            if ($priceStmt = $conn->prepare($priceQuery)) {
                $priceStmt->bind_param('i', $times_id); 
                $priceStmt->execute();
                $priceResult = $priceStmt->get_result();
                
                if ($priceRow = $priceResult->fetch_assoc()) {
                    $row['normal'] = $priceRow; 
                } else {
                    $row['normal'] = null; 
                }
                
                $priceStmt->close();
            } else {
                $row['normal'] = null;
            }
            // Fetch harga from member_price
            $priceQuery = "SELECT * FROM member_price WHERE times_id = ?";
            if ($priceStmt = $conn->prepare($priceQuery)) {
                $priceStmt->bind_param('i', $times_id); 
                $priceStmt->execute();
                $priceResult = $priceStmt->get_result();
                
                if ($priceRow = $priceResult->fetch_assoc()) {
                    $row['member'] = $priceRow; 
                } else {
                    $row['member'] = null; 
                }
                
                $priceStmt->close();
            } else {
                $row['member'] = null;
            }

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
    $start_time = $input['start_time'] ?? '';
    $end_time = $input['end_time'] ?? '';
    $harga = $input['harga'] ?? '';
    $diskon = $input['diskon'] ?? '';
    $total = $input['total'] ?? '';
    $hargaMember = $input['hargaMember'] ?? '';
    $diskonMember = $input['diskonMember'] ?? '';
    $totalMember = $input['totalMember'] ?? '';

    if (empty($lapangan_id) || empty($date) || empty($harga) || empty($start_time) || empty($end_time) || empty($diskon)|| empty($hargaMember) || empty($diskonMember) || empty($totalMember)) {
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
    $insertQuery = 'INSERT INTO available_times (lapangan_id, tanggal, start_time, end_time ) VALUES (?, ?, ?, ?)';
    if ($stmt = $conn->prepare($insertQuery)) {
        $stmt->bind_param('ssss', $lapangan_id, $date, $start_time, $end_time);
        if ($stmt->execute()) {
            // JIKA BERHASIL 
            $timesId = $conn->insert_id;

            //INSERT WAKTUNYA
            $insertMember = new PriceController($conn);

            if ($insertMember->insertPrice($timesId, $harga, $diskon, $total, $hargaMember, $diskonMember, $totalMember) ) {
                echo json_encode(['success' => true]);
            } else {
                //HAPUS TIMES JIKA GAGAL INSERT HARGA
                $deleteQuery = 'DELETE FROM available_times WHERE id = ?';
                if ($deleteStmt = $conn->prepare($deleteQuery)) {
                    $deleteStmt->bind_param('i', $timesId);
                    $deleteStmt->execute();
                    $deleteStmt->close();
                }
                echo json_encode(['success' => false, 'message' => 'Failed to prepare statement: ' . $conn->error]);
            }
            //END DARI INSERT WAKTU



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
