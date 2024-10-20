<?php
require '../../vendor/autoload.php';
require_once 'PriceController.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

require '../../functions.php'; // Ensure this file sets up your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if a file was uploaded
    if (isset($_FILES['filexls'])) {
        $fileTmpPath = $_FILES['filexls']['tmp_name'];
        $fileName = $_FILES['filexls']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Check if the file is an Excel file
        if ($fileExtension === 'xlsx' || $fileExtension === 'xls') {
            if (isset($_POST['lapangan_id'])) {
                $lapangan_id = $_POST['lapangan_id'];
                // Dompdf, Mpdf or Tcpdf (as appropriate)
                $reader = IOFactory::createReaderForFile($fileTmpPath);
                $spreadSheet = $reader->load($fileTmpPath);
                $sheetData = $spreadSheet->getActiveSheet()->toArray();
                $cleanedData = array_filter($sheetData, function($row) {
                    return array_filter($row);
                });


                $jumlahData = 0;
                $errors = [];

                foreach ($cleanedData as $index => $row) {
                    if ($index === 0) {
                        continue; // Lewati baris header
                    }
                
                    $date = $row['0'];
                    $mulai = $row['1'];
                    $habis = $row['2'];
                    $harga = $row['3'];
                    $diskon = $row['4'];
                    $total = $row['5'];
                    $hargaMember = $row['6'];
                    $diskonMember = $row['7'];
                    $totalMember = $row['8'];
                    
                    $date_mysql = explode("/", $date);
                    $date = $date_mysql['2'] . "-" . $date_mysql['0'] . "-" . $date_mysql['1'];
                    
                    // Check if the entry already exists
                    $checkQuery = "SELECT COUNT(*) AS count FROM available_times WHERE lapangan_id = '$lapangan_id' AND tanggal = '$date' AND start_time = '$mulai' AND end_time = '$habis'";
                    $result = mysqli_query($conn, $checkQuery);
                    $row = mysqli_fetch_assoc($result);

                    if ($row['count'] > 0) {
                        $errors[] = "Terdapat duplikat data pada tanggal: $date, waktu mulai: $mulai, waktu habis: $habis";
                    } else {
                        $query = "INSERT INTO available_times (lapangan_id, tanggal, start_time, end_time) VALUES ('$lapangan_id', '$date', '$mulai', '$habis')";

                        if (!mysqli_query($conn, $query)) {
                            $errors[] = "Error inserting data for Date: $date, Start Time: $mulai, End Time: $habis";
                        } else {
                            $timesId = mysqli_insert_id($conn);

                             //INSERT WAKTUNYA
                            $insertMember = new PriceController($conn);

                            if (!$insertMember->insertPrice($timesId, $harga, $diskon, $total, $hargaMember, $diskonMember, $totalMember) ) {
                         
                                //HAPUS TIMES JIKA GAGAL INSERT HARGA
                                $deleteQuery = 'DELETE FROM available_times WHERE id = ?';
                                if ($deleteStmt = $conn->prepare($deleteQuery)) {
                                    $deleteStmt->bind_param('i', $timesId);
                                    $deleteStmt->execute();
                                    $deleteStmt->close();
                                }
                                $errors[] =  'Failed to prepare statement: ' . $conn->error;
                            }
                            //END DARI INSERT WAKTU
                            $jumlahData++;
                        }
                    }
                }

                if (empty($errors)) {
                    echo json_encode(['status' => 'success', 'message' => $jumlahData . ' data berhasil ditambahkan.']);
                } else {
                    echo json_encode(['status' => 'warning', 'message' => implode('<br>', $errors)]);
                }

                exit; 

            } else {
                echo json_encode(['status' => 'error', 'message' => 'Lapangan ID is missing.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Yang kamu upload bukan excel!']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Upload file terlebih dahulu']);
    }
}
?>
