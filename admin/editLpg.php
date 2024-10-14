<?php
require '../functions.php'; // Ensure this file sets up your database connection
header('Content-Type: application/json');

$id = $_POST["id"];
$fotoLama = $_POST["fotoLama"];
$name = $_POST["name"];
$diskon = $_POST["diskon_edit"];
$desc = $_POST["description"];

// Check if the user selected a new image
if ($_FILES["photo"]["error"] === 4) {
    $gambar = $fotoLama; // Use the old photo if no new photo is selected
} else {
    $uploadResponse = upload(); // Try to upload a new photo
    
    // Check if upload failed
    if ($uploadResponse['success'] === false) {
        echo json_encode($uploadResponse);
        exit;
    }
    
    $gambar = $uploadResponse['fileName']; // Get the new uploaded file name
}

// Begin transaction
$conn->begin_transaction();
try {
    // Prepare the UPDATE query for the `lapangan` table
    $stmt = $conn->prepare("UPDATE lapangan SET name = ?, photo = ?, description = ?, diskon = ? WHERE id = ?");
    $stmt->bind_param("sssii", $name, $gambar, $desc, $diskon, $id); // Use `sssi` for string, string, string, integer

    // Execute the query
    if ($stmt->execute()) {
        // Commit the transaction if no error occurs
        $conn->commit();
        echo json_encode(['success' => true]);
    } else {
        // Rollback transaction if the execution fails
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Failed to update data.']);
    }
} catch (Exception $e) {
    // Rollback transaction if any error occurs
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

// Close the statement and connection
$stmt->close();
$conn->close();
