<?php
require "../../functions.php";
if (isset($_POST['id'])) {
  $id = $_POST['id'];

  try {
  mysqli_query($conn, "UPDATE bookings set status = ('Diterima') WHERE id = '$id'");

  $response = ['success' => true];
  echo json_encode($response);
  } catch (Exception $e) {

    $response = ['success' => false];
    echo json_encode($response);
  }
}
