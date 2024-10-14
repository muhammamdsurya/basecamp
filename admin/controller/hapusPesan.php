<?php
require "../../functions.php";
if (isset($_POST['id'])) {
  $id = $_POST['id'];

  // Sanitasi ID untuk mencegah SQL Injection
  $id = intval($id);

  // Mulai transaksi
  mysqli_begin_transaction($conn);

  try {
    // Hapus data dari bookings
    $query1 = "DELETE FROM bookings WHERE id = ?";
    $stmt1 = mysqli_prepare($conn, $query1);
    if (!$stmt1) {
      throw new Exception("Gagal menyiapkan statement: " . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($stmt1, "i", $id);
    if (!mysqli_stmt_execute($stmt1)) {
      throw new Exception("Eksekusi statement gagal: " . mysqli_stmt_error($stmt1));
    }
    $affectedRows1 = mysqli_stmt_affected_rows($stmt1);


    // Update status di available_times
    $query2 = "
    UPDATE available_times AS at
    LEFT JOIN booking_times AS bt
    ON at.lapangan_id = bt.lapangan_id 
       AND at.tanggal = bt.tanggal 
       AND at.start_time < bt.end_time 
       AND at.end_time > bt.start_time
    SET at.status = 'available'
    WHERE bt.id IS NULL
";

    $stmt2 = mysqli_prepare($conn, $query2);
    if (!$stmt2) {
      throw new Exception("Gagal menyiapkan statement: " . mysqli_error($conn));
    }
    if (!mysqli_stmt_execute($stmt2)) {
      throw new Exception("Eksekusi statement gagal: " . mysqli_stmt_error($stmt2));
    }

    // Commit transaksi
    mysqli_commit($conn);

    $response = ['success' => true];
    echo json_encode($response);
  } catch (Exception $e) {
    // Rollback transaksi jika terjadi kesalahan
    mysqli_rollback($conn);

    $response = ['success' => false];
    echo json_encode($response);
  }
}
