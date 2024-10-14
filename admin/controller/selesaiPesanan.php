<?php
require "../../functions.php";
if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Sanitasi ID untuk mencegah SQL Injection
    $id = intval($id);

    // Mulai transaksi
    mysqli_begin_transaction($conn);

    try {
        // Mulai transaksi
        mysqli_begin_transaction($conn);

        // 1. Ubah status di bookings menjadi 'Selesai'
        $query1 = "UPDATE bookings SET status='Selesai' WHERE id = ?";
        $stmt1 = mysqli_prepare($conn, $query1);
        if (!$stmt1) {
            throw new Exception("Gagal menyiapkan statement: " . mysqli_error($conn));
        }
        mysqli_stmt_bind_param($stmt1, "i", $id);
        if (!mysqli_stmt_execute($stmt1)) {
            throw new Exception("Eksekusi statement gagal: " . mysqli_stmt_error($stmt1));
        }

        // 2. Ambil data booking terkait dari booking_times
        $queryBookingTimes = "SELECT lapangan_id, tanggal, start_time, end_time FROM booking_times WHERE booking_id = ?";
        $stmtBookingTimes = mysqli_prepare($conn, $queryBookingTimes);
        mysqli_stmt_bind_param($stmtBookingTimes, "i", $id);
        mysqli_stmt_execute($stmtBookingTimes);
        $resultBookingTimes = mysqli_stmt_get_result($stmtBookingTimes);

        // 3. Update status di available_times berdasarkan data di booking_times
        if ($resultBookingTimes) {
            while ($bookingData = mysqli_fetch_assoc($resultBookingTimes)) {
                $query2 = "
                UPDATE available_times 
                SET status = 'available'
                WHERE lapangan_id = ? 
                AND tanggal = ? 
                AND (
                    (start_time < ? AND end_time > ?)
                )";

                $stmt2 = mysqli_prepare($conn, $query2);
                if (!$stmt2) {
                    throw new Exception("Gagal menyiapkan statement: " . mysqli_error($conn));
                }
                mysqli_stmt_bind_param($stmt2, "isss", $bookingData['lapangan_id'], $bookingData['tanggal'], $bookingData['end_time'], $bookingData['start_time']);
                if (!mysqli_stmt_execute($stmt2)) {
                    throw new Exception("Eksekusi statement gagal: " . mysqli_stmt_error($stmt2));
                }
            }
        }
        // Commit transaksi setelah semua query berhasil
        mysqli_commit($conn);

        // Kirim response sukses
        $response = ['success' => true];
        echo json_encode($response);
    } catch (Exception $e) {
        // Rollback transaksi jika terjadi kesalahan
        mysqli_rollback($conn);
        $response = ['success' => false, 'message' => $e->getMessage()];
        echo json_encode($response);
    }
}
