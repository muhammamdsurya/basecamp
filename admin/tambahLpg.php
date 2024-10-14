<?php
require '../functions.php'; // Ensure this file sets up your database connection
header('Content-Type: application/json');

$name = $_POST["name"];
$desc = $_POST["description"];
$diskon = $_POST["diskon_member"];

$uploadResponse = upload();
if ($uploadResponse['success'] === false) {
    echo json_encode($uploadResponse);
    exit;
}

$namaFileBaru = $uploadResponse['fileName'];


// Begin transaction
$conn->begin_transaction();
try {
    // Insert data into the `lapangan` table
    $stmt = $conn->prepare("INSERT INTO lapangan (name,photo,description,diskon) VALUES  (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $name, $namaFileBaru, $desc, $diskon); // Change `sis` to `sss` for string binding
    $stmt->execute();

    // Commit transaction
    $conn->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    // Rollback transaction if something goes wrong
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$stmt->close();
$conn->close();
