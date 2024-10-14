<?php
require '../functions.php'; // Pastikan file ini mengatur koneksi database
header('Content-Type: application/json');
$id = $_POST['id'];
$tgl_book = $_POST['tgl_book'];
$nama = $_POST['nama'];
$no_hp = $_POST['no_hp'];
$catatan = $_POST['catatan'];
$diskon= $_POST['diskon_member'];
$total = $_POST['total'];
$selected_times = $_POST['selected_times'];


// Handle file upload
$uploadResponse = upload();
if ($uploadResponse['success'] === false) {
    echo json_encode($uploadResponse);
    exit;
}

$namaFileBaru = $uploadResponse['fileName'];


// Start transaction
$conn->begin_transaction();

try {
    // Insert data into bookings table
    $stmt = $conn->prepare("INSERT INTO bookings (lapangan_id, booking_date, name, no_hp, catatan, diskon_member, total, photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssiss", $id, $tgl_book, $nama, $no_hp, $catatan, $diskon, $total, $namaFileBaru);
    $stmt->execute();

    // Get the newly inserted booking ID
    $booking_id = $conn->insert_id;

    // Prepare the insert statement for booking_times
    $insertBookingTimesStmt = $conn->prepare("INSERT INTO booking_times (booking_id, start_time, end_time, tanggal, lapangan_id) VALUES (?, ?, ?, ?, ?)");
    $insertBookingTimesStmt->bind_param("isssi", $booking_id, $start_time, $end_time, $tgl_main, $id);

    // Prepare the update statement for available_times
    $updateAvailableTimesStmt = $conn->prepare("UPDATE available_times SET status = 'booked' WHERE lapangan_id = ? AND tanggal = ? AND start_time = ? AND end_time = ?");
    $updateAvailableTimesStmt->bind_param("isss", $id, $tgl_main, $start_time, $end_time);

    // Iterate through selected time slots
    foreach ($selected_times as $time) {
        $start_time = $time['startTime'];
        $end_time = $time['endTime'];

        foreach ($time['selectedDates'] as $tgl_main) {
            // Cek apakah booking_time sudah ada
            $stmt = $conn->prepare("SELECT 1 FROM booking_times WHERE start_time = ? AND end_time = ? AND tanggal = ? AND lapangan_id = ?");
            $stmt->bind_param("sssi", $start_time, $end_time, $tgl_main, $id);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                // Peringatkan bahwa slot waktu sudah ada
                echo json_encode(['success' => false, 'message' => 'Slot waktu sudah ada untuk tanggal ini.']);
                exit;
            }
            $stmt->close();

            // Insert into booking_times
            if (!$insertBookingTimesStmt->execute()) {
                throw new Exception("Insert booking times error: " . $insertBookingTimesStmt->error);
            }

            // Update available_times to 'booked'
            if (!$updateAvailableTimesStmt->execute()) {
                throw new Exception("Update available times error: " . $updateAvailableTimesStmt->error);
            }
        }
    }


    // Commit the transaction if all is successful
    $conn->commit();
    echo json_encode(['success' => true, 'message' => 'Pesanan berhasil di pesan!, silahkan upload invoice yang sudah terdownload ke whatsapp admin.',  'booking_id' => $booking_id]);
} catch (Exception $e) {
    // Rollback the transaction in case of any failure
    $conn->rollback();
    error_log($e->getMessage()); // Log the error message

    echo json_encode(['success' => false, 'message' => 'Error during booking process', 'error' => $e->getMessage()]);
}

$conn->close();
