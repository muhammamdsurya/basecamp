<?php

$conn = mysqli_connect("localhost", "root", "", "basecamp");

function query($query)
{
  global $conn; // Asumsikan $conn adalah koneksi ke database
  $result = $conn->query($query);
  if ($result === false) {
    die('Query error: ' . $conn->error);
  }
  return $result->fetch_all(MYSQLI_ASSOC);
}

function getCount($query)
{
  global $conn;
  $result = mysqli_query($conn, $query);
  if ($row = mysqli_fetch_assoc($result)) {
    return (int) $row['total'];
  }
  return 0;
}


function hapusAdmin($id)
{
  global $conn;
  mysqli_query($conn, "DELETE FROM admin WHERE id_user = $id");

  return mysqli_affected_rows($conn);
}

function upload()
{
  $namaFile = $_FILES['photo']['name'];
  $ukuranFile = $_FILES['photo']['size'];
  $error = $_FILES['photo']['error'];
  $tmpName = $_FILES['photo']['tmp_name'];

  // Check if no file is uploaded
  if ($error === 4) {
    return ['success' => false, 'message' => 'Please select an image to upload'];
  }

  // Check if the file is an image
  $extensiValid = ['jpg', 'png', 'jpeg'];
  $extensiGambar = explode('.', $namaFile);
  $extensiGambar = strtolower(end($extensiGambar));

  if (!in_array($extensiGambar, $extensiValid)) {
    return ['success' => false, 'message' => 'The uploaded file is not an image!'];
  }

  // Check if the image size is too large
  if ($ukuranFile > 1000000) {
    return ['success' => false, 'message' => 'Image size is too large!'];
  }

  // Generate a new unique file name
  $namaFileBaru = uniqid();
  $namaFileBaru .= '.' . $extensiGambar;

  // Move the file to the server
  if (move_uploaded_file($tmpName, '../img/' . $namaFileBaru)) {
    return ['success' => true, 'fileName' => $namaFileBaru];
  } else {
    return ['success' => false, 'message' => 'Failed to move uploaded file'];
  }
}

function tambahAdmin($data)
{
  global $conn;

  $username = $data["username"];
  $password = $data["password"];
  $nama = $data["nama"];
  $no_handphone = $data["hp"];
  $type = $data["type"];

  $query = "INSERT INTO admin (role,username,password,nama,no_hp) VALUES ('$type','$username','$password','$nama','$no_handphone')";

  mysqli_query($conn, $query);
  return mysqli_affected_rows($conn);
}

function editAdmin($data)
{
  global $conn;

  $id = (int) $data["id"];
  $username = $data["username"];
  $password = $data["password"];
  $nama = $data["nama"];
  $no_handphone = $data["hp"];
  $type = $data["type"];

  $query = "UPDATE admin SET 
  username = '$username',
  password = '$password',
  nama = '$nama',
  no_hp = '$no_handphone',
  role  = '$type' WHERE id_user = '$id'
  ";

  mysqli_query($conn, $query);
  return mysqli_affected_rows($conn);
}
