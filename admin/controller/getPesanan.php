<?php
require '../../functions.php'; // Ensure this file sets up your database connection
header('Content-Type: application/json');

// SUM(price.harga) AS total_harga,  -- Sum of harga for the same booking_id
// SUM(ROUND(price.harga * price.diskon / 100)) AS total_diskon  -- Sum of total discount in Rupiah for the same booking_id

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
    b.total
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
    
    $bookingId = $row['booking_id'];

    
    $countQry= "
    SELECT COUNT(*) AS count 
    FROM booking_times 
    WHERE booking_times.booking_id = ".$bookingId.";
    ";

    $resultCount = mysqli_query($conn, $countQry);
    $rowCount = mysqli_fetch_assoc($resultCount);

    $count = $rowCount['count']; 

    $table = "normal_price";
    if($count >= 4){
        $table = "member_price";

    }

    $countQry= "
    SELECT booking_times.tanggal, booking_times.start_time, booking_times.end_time, price.harga, price.diskon, price.total AS available_total FROM bookings JOIN lapangan ON bookings.lapangan_id = lapangan.id LEFT JOIN booking_times ON bookings.id = booking_times.booking_id LEFT JOIN available_times ON booking_times.lapangan_id = available_times.lapangan_id AND booking_times.tanggal = available_times.tanggal AND booking_times.start_time = available_times.start_time AND booking_times.end_time = available_times.end_time LEFT JOIN ".$table." price ON available_times.id = price.times_id WHERE bookings.id  = ".$bookingId.";
    ";

    $countStmt = $conn->prepare($countQry);
    $countStmt->execute();
    $resultCount = $countStmt->get_result();
    $bookingTimesList = $resultCount->fetch_all(MYSQLI_ASSOC);


    $totalHarga = 0;
    $totalDisc = 0;
    foreach($bookingTimesList as $bookTime){
        $totalHarga = $totalHarga+$bookTime['harga'];
        $totalDisc = $totalDisc+($bookTime['harga']-$bookTime['available_total']);
    }




    $row['total_harga'] = $totalHarga;
    $row['total_diskon'] = $totalDisc;


    $pesanan[] = $row;
}

// Return the data as a JSON response
echo json_encode(['success' => true, 'data' => $pesanan]);

$stmt->close();
$conn->close();
