<?php
require '../functions.php'; // Ensure this file sets up your database connection
header('Content-Type: application/json');

$id = $_POST['id'];
$tgl_book = $_POST['tgl_book'];
$nama = $_POST['nama'];
$no_hp = $_POST['no_hp'];
$catatan = $_POST['catatan'];
$diskon = $_POST['diskon_member'];
$nominal = $_POST['nominal'];
$total = $_POST['subTotal'];

$selectedBookings = $_POST['selectedBookings'];


// Determine the status based on the amount paid
if ($nominal < $total) {
    $status = 'Belum Lunas'; // Payment is less than total, set to "Not Paid"
} else {
    $status = 'Diterima'; // Payment is equal to or more than total, set to "Paid"
}

// Handle file upload
$uploadResponse = upload();
if ($uploadResponse['success'] === false) {
    echo json_encode($uploadResponse);
    exit;
}

$namaFileBaru = $uploadResponse['fileName'];

// Mulai transaksi
$conn->begin_transaction();
try {
    // Begin transaction
    $conn->begin_transaction();

    // Masukkan data ke tabel bookings
    $stmt = $conn->prepare("INSERT INTO bookings (lapangan_id, booking_date, name, no_hp, catatan, nominal, diskon_member, total, photo, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssssss", $id, $tgl_book, $nama, $no_hp, $catatan, $nominal,$diskon, $total, $namaFileBaru, $status);

    if (!$stmt->execute()) {
        throw new Exception('Failed to insert booking: ' . $stmt->error);
    }

    // Ambil ID booking yang barusan ditambahkan
    $booking_id = $conn->insert_id;

    // Assuming $selectedBookings contains the array of selected time slots
    foreach ($selectedBookings as $time) {
        $start_time = $time['start'];
        $end_time = $time['end'];
        $tanggal = $time['tanggal']; // You might need this for availability checks

        // Validasi data pada tabel available_times
        $stmt = $conn->prepare("SELECT 1 FROM available_times WHERE start_time = ? AND end_time = ? AND tanggal = ? AND lapangan_id = ?");
        $stmt->bind_param("sssi", $start_time, $end_time, $tanggal, $id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 0) {
            $stmt->close(); // Close before exiting
            throw new Exception('Selected time slots are not available.');
        }
        $stmt->close(); // Close the statement after checking availability

        // Insert into booking_times
        $stmt = $conn->prepare("INSERT INTO booking_times (booking_id, start_time, end_time, tanggal, lapangan_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isssi", $booking_id, $start_time, $end_time, $tanggal, $id);

        if (!$stmt->execute()) {
            throw new Exception('Failed to insert booking time: ' . $stmt->error);
        }

        // Update status di available_times
        $stmt = $conn->prepare("UPDATE available_times SET status = 'booked' WHERE lapangan_id = ? AND tanggal = ? AND start_time = ? AND end_time = ?");
        $stmt->bind_param("isss", $id, $tanggal, $start_time, $end_time);

        if (!$stmt->execute()) {
            throw new Exception('Failed to update availability status: ' . $stmt->error);
        }
        $stmt->close(); // Close after updating
    }

    // Commit transaksi
    $conn->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    // Rollback transaksi jika ada kesalahan
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->close();
