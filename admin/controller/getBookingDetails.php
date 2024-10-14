<?php
require '../../functions.php'; // Ensure this file sets up your database connection
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $booking_id = $_GET['id'] ?? '';

    // Query to get available pesanan for the specified date
    $query = " SELECT 
    b.id AS booking_id, 
    bt.tanggal, 
    bt.start_time, 
    bt.end_time, 
    av.harga, 
    av.diskon, 
    av.total AS available_total, 
    l.name AS lapangan_name
FROM 
    bookings b 
JOIN 
    booking_times bt ON b.id = bt.booking_id 
LEFT JOIN 
    available_times av ON bt.lapangan_id = av.lapangan_id 
    AND bt.tanggal = av.tanggal 
    AND (bt.start_time < av.end_time AND bt.end_time > av.start_time) 
JOIN 
    lapangan l ON b.lapangan_id = l.id
WHERE 
    b.id = ?;

";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $booking_id); // Bind the booking ID to the placeholder

    $stmt->execute();
    $result = $stmt->get_result();
    
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);


    // $pesanan = [];
    // while ($row = $result->fetch_assoc()) {
    //     $pesanan[] = $row;
    // }

    // // Return the data as a JSON response
    // echo json_encode(['success' => true, 'data' => $pesanan]);

    $stmt->close();
    $conn->close();
}
