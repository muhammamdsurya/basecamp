<?php
require '../../functions.php'; // Include database connection
require '../../vendor/autoload.php'; // Load mPDF library

// Query 1: Fetch general booking data
$id = $_GET['id']; // Get the booking ID from the query parameter

$stmt1 = $conn->prepare("
    SELECT bookings.booking_date, bookings.id, lapangan.name AS lapangan_name, bookings.name, bookings.no_hp, bookings.catatan, bookings.diskon_member, bookings.total
    FROM bookings
    JOIN lapangan ON bookings.lapangan_id = lapangan.id
    WHERE bookings.id = ?
");
$stmt1->bind_param("i", $id);
$stmt1->execute();
$result1 = $stmt1->get_result();
$bookingData = $result1->fetch_assoc(); // Fetch data for booking

$stmt1->close(); // Close the first statement


$countQry= "
SELECT COUNT(*) AS count 
FROM booking_times 
WHERE booking_times.booking_id = ".$id.";
";

$resultCount = mysqli_query($conn, $countQry);
$rowCount = mysqli_fetch_assoc($resultCount);

    $count = $rowCount['count']; 

    // Query 2: Fetch booking times and additional info
        $stmt2 = $conn->prepare("
        SELECT booking_times.tanggal, booking_times.start_time, booking_times.end_time, normal_price.harga, normal_price.diskon, normal_price.total AS available_total FROM bookings JOIN lapangan ON bookings.lapangan_id = lapangan.id LEFT JOIN booking_times ON bookings.id = booking_times.booking_id LEFT JOIN available_times ON booking_times.lapangan_id = available_times.lapangan_id AND booking_times.tanggal = available_times.tanggal AND booking_times.start_time = available_times.start_time AND booking_times.end_time = available_times.end_time LEFT JOIN normal_price ON available_times.id = normal_price.times_id WHERE bookings.id = ?;

        ");


    if($count >= 4){
        $stmt2 = $conn->prepare("
        SELECT booking_times.tanggal, booking_times.start_time, booking_times.end_time, member_price.harga, member_price.diskon, member_price.total AS available_total FROM bookings JOIN lapangan ON bookings.lapangan_id = lapangan.id LEFT JOIN booking_times ON bookings.id = booking_times.booking_id LEFT JOIN available_times ON booking_times.lapangan_id = available_times.lapangan_id AND booking_times.tanggal = available_times.tanggal AND booking_times.start_time = available_times.start_time AND booking_times.end_time = available_times.end_time LEFT JOIN member_price ON available_times.id = member_price.times_id WHERE bookings.id = ?;
    
    ");
    }
    







$stmt2->bind_param("i", $id);
$stmt2->execute();
$result2 = $stmt2->get_result();
$bookingTimesData = $result2->fetch_all(MYSQLI_ASSOC); // Fetch all time slots

$stmt2->close(); // Close the second statement

// Initialize mPDF
$mpdf = new \Mpdf\Mpdf();


// Set watermark image (this acts as a background image with opacity)
$mpdf->SetWatermarkImage('../../assets/img/logo.png', 0.1, 'F');
$mpdf->showWatermarkImage = true;

$html = '
<style>

    body {
        font-family: Arial, sans-serif;
        font-size: 16px;
        color: #333;
    }
    .header-table {
        width: 100%;
        border-bottom: 2px solid #000;
        padding-bottom: 10px;
    }
    .header-table td {
        vertical-align: middle;
    }
    .header-title {
        font-size: 40px;
        font-weight: bold;
        text-align: left;
    }
    .title {
        text-align: center;
        margin-top: 30px;
    }
    .details-table {
        width: 100%;
        margin-top: 20px;
    }
    .details-table td {
        padding: 5px;
    }
    .table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }
    .table th, .table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }
    .table th {
        background-color: #9cd203;
        font-weight: bold;
    }
    .subtotal-container {
        margin-top: 30px;
        border-top: 1px solid #ddd; /* Garis pemisah atas */
        padding-top: 20px;
    }
    
    .subtotal {
        font-size: 18px;
        font-weight: bold;
        text-align: right;
        margin-bottom: 10px; /* Jarak di bawah subtotal */
    }

    .footer-title {
        font-size: 16px;
        margin-top: 30px; /* Jarak di atas footer title */
        margin-bottom: 15px; /* Jarak di bawah footer title */
    }

    .footer-text {
        margin-top: 5px; /* Jarak antara footer text */
    }
    .logo {
    width: 100px;
    }
</style>


<!-- Header dengan logo dan teks sejajar -->
<table class="header-table">
    <tr>
        <td class="header-title">INVOICE</td>
        <td style="text-align: right;">
            <img src="../../assets/img/logo.png" alt="Logo" class="logo">
        </td>
    </tr>
</table>

<!-- Detail bagian atas -->
<table class="details-table">
    <tr>
        <td>
            <strong>Data pelanggan</strong><br>
            Nama: ' . $bookingData['name'] . '<br>
            No HP: ' . $bookingData['no_hp'] . '
        </td>
        <td style="text-align: right;">
            <strong>Booking Info</strong><br>
            Tanggal Booking: ' . date('d-m-Y', strtotime($bookingData['booking_date'])) . '<br>
            Booking ID: ' . $bookingData['id'] . '
        </td>
    </tr>
</table>

<!-- Bagian tengah dengan tabel booking -->
<h3 class="title">Rincian Pembayaran</h3>

<p class="section-title">Sewa Lapangan: ' . $bookingData['lapangan_name'] . '</p>
<table class="table">
    <thead>
        <tr>
            <th>Tanggal Main</th>
            <th>Waktu</th>
            <th>Harga</th>
            <th>Diskon</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>';

foreach ($bookingTimesData as $row) {
    $html .= '
        <tr>
            <td>' . date('d-m-Y', strtotime($row['tanggal']))  . '</td>
            <td>' . $row['start_time'] . ' - ' . $row['end_time'] . '</td>
            <td>Rp ' . number_format($row['harga'], 0, ',', '.') . '</td>
            <td>' . number_format($row['diskon'], 0, ',', '.') . ' % </td>
            <td>Rp ' . number_format($row['available_total'], 0, ',', '.') . '</td>
        </tr>';
}

$html .= '
    </tbody>
</table>
<!-- Subtotal dan footer -->
<div class="subtotal-container">
    <table style="width: 100%;">
        <tr>
            <td style="text-align: left; vertical-align: top;">
                <p>*Catatan: ' . $bookingData['catatan'] . '</p>
            </td>
            
            <td style="text-align: right; vertical-align: top;">
            <p class="subtotal">Diskon Member: Rp' . number_format($bookingData['diskon_member'], 0, ',', '.') . '</p> <br>
                <p class="subtotal">Total Pembayaran: Rp' . number_format($bookingData['total'], 0, ',', '.') . '</p>
            </td>
        </tr>
    </table>
    
    <h3 class="footer-title">Rekening Pembayaran</h3>
    <p class="footer-text">Rek. BRI an. Novie Rosmayanti & Elis Lisdiani <strong>No. 0401-01-888000-56-9</strong></p>
    <p class="footer-text">*Pembayaran Maks. Tgl 25 di bulan sebelum jadwal sewa</p>
</div>

';


// Write content to PDF
$mpdf->WriteHTML($html);

// Output PDF (download)
$mpdf->Output('invoice.pdf', 'D');
