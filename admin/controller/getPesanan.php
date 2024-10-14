<?php
require '../../functions.php'; // Ensure this file sets up your database connection
header('Content-Type: application/json');

// Query to get available pesanan for the specified date
$query = " SELECT 
    b.id AS booking_id,  -- Booking ID
    b.booking_date AS tanggal_booking,
    b.name,
    b.nominal,
    b.no_hp,
    b.catatan,
    b.photo,
    b.status,
    b.diskon_member,
    b.total,
    SUM(av.harga) AS total_harga,  -- Sum of harga for the same booking_id
    SUM(ROUND(av.harga * av.diskon / 100)) AS total_diskon  -- Sum of total discount in Rupiah for the same booking_id
FROM bookings b
JOIN booking_times bt ON b.id = bt.booking_id
LEFT JOIN available_times av ON bt.lapangan_id = av.lapangan_id 
    AND bt.tanggal = av.tanggal 
    AND (bt.start_time < av.end_time AND bt.end_time > av.start_time)
JOIN lapangan l ON b.lapangan_id = l.id
GROUP BY 
    b.id, 
    b.booking_date,
    b.name,
    b.no_hp,
    b.nominal,
    b.catatan,  
    b.photo,
    b.status,
    b.diskon_member,
    b.total
    -- Grouping by all the fields you want to display
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
