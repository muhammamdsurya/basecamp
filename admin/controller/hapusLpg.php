<?php
header('Content-Type: application/json');
require '../../functions.php';

if (isset($_POST['id'])) {
  $id = $_POST['id'];

  // Your deletion query
  $stmt = $conn->prepare("DELETE FROM lapangan WHERE id = ?");
  $stmt->bind_param("i", $id);

  if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Data berhasil dihapus!']);  // Ensure proper JSON format
  } else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete record.']);
  }

  $stmt->close();
  $conn->close();
} else {
  echo json_encode(['success' => false, 'message' => 'No ID provided.']);
}
