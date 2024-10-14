<?php
require '../functions.php'; // Pastikan file ini mengatur koneksi database
header('Content-Type: application/json');

// Cek apakah ID dikirim via GET
$id = isset($_GET['id']) ? trim($_GET['id']) : ''; // Trim untuk menghapus spasi yang tidak perlu

// Validasi jika ID kosong
if (empty($id)) {
    echo json_encode(['success' => false, 'message' => 'ID is required.']);
    exit;
}

// Query untuk mengambil diskon dari lapangan berdasarkan id
$query = "SELECT diskon FROM lapangan WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id); // Bind parameter id
$stmt->execute();
$result = $stmt->get_result();

// Cek apakah data ditemukan
if ($result->num_rows > 0) {
    $data = $result->fetch_assoc(); // Ambil satu baris data
    echo json_encode(['success' => true, 'diskon' => $data['diskon']]);
} else {
    echo json_encode(['success' => false, 'message' => 'Data not found.']);
}

$stmt->close();
$conn->close();
?>
