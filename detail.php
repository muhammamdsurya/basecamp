<?php
require 'functions.php';

$idUrl = isset($_GET['id']) ? intval($_GET['id']) : 0;

$id = intval($idUrl);

$detail = query("SELECT * FROM lapangan WHERE id = $id")[0];


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Lapangan</title>

    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css' rel='stylesheet' />

    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <link href="assets/css/main.css" rel="stylesheet">
    <style>
        /* General table styling */
        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
        }

        .table-bordered {
            border: 1px solid #dee2e6;
        }

        .table-hover tbody tr:hover {
            background-color: #f5f5f5;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f9f9f9;
        }

        /* Table header styling */
        .thead-dark th {
            background-color: #343a40;
            color: #fff;
            text-align: center;
        }

        /* Status color coding */
        .table-success {
            background-color: #d4edda;
        }

        .table-danger {
            background-color: #f8d7da;
        }

        /* Additional padding and border radius */
        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        .table thead th {
            border-bottom: 2px solid #dee2e6;
        }

        .table td,
        .table th {
            border: 1px solid #dee2e6;
        }

        .table-hover tbody tr:hover {
            background-color: #e9ecef;
        }

        .booking-form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .booking-form-container h3 {
            font-weight: bold;
            font-size: 1.8rem;
            margin-bottom: 15px;
            color: #333;
            text-align: center;
        }

        #date-container .form-control {
            width: 100% !important;
        }

        #date-container .row {
            width: 100% !important;
        }


        .form-group label {
            font-weight: 600;
            color: #555;
        }

        .form-control {
            border-radius: 8px;
            padding: 10px;
            border: 1px solid #ddd;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #28a745;
            /* Custom accent color */
            box-shadow: 0 0 5px rgba(40, 167, 69, 0.3);
        }


        .form-group {
            margin-bottom: 20px;
        }

        /* Adjusting button margins for a cleaner look */
        #add-date-btn {
            width: 100%;
        }

        /* Time category buttons */
        .d-flex .btn {
            margin-bottom: 10px;
        }

        /* Responsive tweaks */
        @media (max-width: 768px) {
            .btn-outline-primary {
                font-size: 0.9rem;
            }

            #add-date-btn {
                font-size: 0.9rem;
            }
        }

        @media (max-width: 576px) {
            .form-control {
                font-size: 0.9rem;
            }

            .btn {
                font-size: 0.8rem;
            }

            .booking-form-container h3 {
                font-size: 1.6rem;
            }
        }

        .detail-img {
            width: 100%;
            height: 70vh;
            object-fit: cover;
            /* Memastikan gambar mengisi area tanpa merubah rasio aspek */
            display: block;
            /* Pastikan gambar ditampilkan sebagai blok */
            image-rendering: -webkit-optimize-contrast;
            /* Mengurangi efek buram */
        }



        .time-button {
            margin-bottom: 10px;
            border-radius: 0.25rem;
            /* Rounded corners for buttons */
            border: 1px solid #007bff;
            /* Border color */
            color: #007bff;
            /* Text color */
        }

        .time-button.active {
            background-color: #007bff;
            /* Active background color */
            color: white;
            /* Active text color */
        }

        .calendar-container {
            height: 100%;
            /* Memastikan kalender mengisi ruang yang tersedia */
            overflow: hidden;
        }

        /* Sorot hari yang dipilih dengan warna */
        .fc-daygrid-day.fc-day-today {
            background-color: #f0f8ff;
            /* Warna latar belakang untuk hari ini */
            /* Warna border untuk hari ini */
            border-radius: 5px;
            /* Radius border untuk tampilan yang lebih baik */
        }

        /* Sorot hari saat hover dengan warna */
        .fc-daygrid-day:hover {
            background-color: #e9ecef;
            /* Warna latar belakang saat hover */
            cursor: pointer;
            /* Mengubah cursor menjadi pointer saat hover */
            border-radius: 5px;
            /* Radius border untuk tampilan yang lebih baik */
        }

        /* Sorot hari yang diklik dengan warna */
        .fc-daygrid-day.fc-day-selected {
            background-color: #000000;
            /* Warna latar belakang saat diklik */
            color: white;
            /* Warna teks saat diklik */
            border-radius: 5px;
            /* Radius border untuk tampilan yang lebih baik */
        }

        .table td,
        .table th {
            text-align: center;
        }
    </style>
</head>

<body>

    <header id="header" class="header d-flex align-items-center sticky-top shadow-sm">
        <div class="container-fluid container-xl position-relative d-flex align-items-center">

            <a href="index.html" class="logo d-flex align-items-center me-auto">
                <!-- Uncomment the line below if you also wish to use an image logo -->
                <img src="assets/img/logo.png" alt="">
                <h3 class="text-dark mx-3">Basecamp</h3>

            </a>

            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="index.php">Beranda<br></a></li>
                    <li><a href="lapangan.php" class="active">Lapangan</a></li>
                    <li><a href="kontak.php">Kontak</a></li>
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>
        </div>
    </header>
    <div class="container mt-5">
        <!-- Foto Lapangan -->
        <div class="mb-4">
            <img src="img/<?= htmlspecialchars($detail['photo']); ?>" alt="<?= htmlspecialchars($detail['name']); ?>" class="detail-img rounded">
        </div>

        <div class="row my-3 ">
            <div class="col-md-6 mb-5">
                <div class="card shadow-sm rounded border-0">
                    <div class="card-body">
                        <h2 class="card-title"><?= htmlspecialchars($detail['name']); ?></h2>
                        <hr class="my-4">
                        <p class="card-text"><?= htmlspecialchars($detail['description']); ?></p>
                    </div>
                </div>
            </div>

            <!-- Form Pemesanan Lapangan -->
            <div class="col-md-6">
                <div class="booking-form-container shadow rounded">
                    <form id="booking-form">
                        <h3>Pesan Lapangan</h3>
                        <div id="date-container">
                            <div class="row d-flex justify-content-between align-items-center">
                                <div class="col-8">
                                    <div class="form-group">
                                        <label for="booking_date_1">Tanggal</label>
                                        <input type="date" id="booking_date_1" name="booking_date[]" placeholder="Select date" class="form-control booking-date" required>
                                    </div>
                                </div>
                                <div class="col-4 d-flex justify-content-center align-items-center">
                                    <button type="button" id="add-date-btn" class="btn btn-primary ">+ Tanggal</button>
                                </div>

                            </div>
                        </div>

                        <div class="form-group">
                            <label>Waktu</label>
                            <div class="d-flex flex-wrap gap-3">
                                <button type="button" class="btn btn-outline-primary" data-time-category="morning" disabled>Pagi (06.00 - 16.00)</button>
                                <button type="button" class="btn btn-outline-primary" data-time-category="evening" disabled>Malam (16.00 - 22.00)</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Jam Tersedia</label>
                            <div class="d-flex flex-wrap gap-3" id="available-times">
                                <!-- Available times will be populated by JavaScript -->
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="catatan">Catatan</label>
                            <input type="text" id="catatan" name="catatan" class="form-control">
                        </div>
                        <div>
                            <small>*Pesan 4 Jadwal akan mendapatkan harga diskon member</small>
                        </div>
                        <button type="button" class="btn btn-success pesan mt-3 w-100" data-bs-toggle="modal" data-bs-target="#bayarModal" id="pesan" disabled>Pesan</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="row justify-content-center mt-5 gap-5">
            <div class="col-md-7 col-lg-7 calendar-container">
                <div id="calendarEl"></div>
            </div>

            <div class="col-md-4 col-lg-4">
                <div id="schedule"></div>
            </div>
        </div>
    </div>

    <footer id="footer" class="footer position-relative bg-success-rgb shadow">

        <div class="container footer-top mt-5">
            <div class="row gy-4">
                <div class="col-6 col-lg-4 col-md-6 footer-about">
                    <a href="index.html" class="logo d-flex align-items-center">
                        <span class="sitename">Basecamp</span>
                    </a>
                    <div class="footer-contact pt-3">
                        <p>jl. Gatot Subroto 96 (Lingkar Selatan, Lengkong, Turangga)</p>
                        <p>Bandung, Jawa Barat</p>
                        <p>Indonesia</p>
                        <p class="mt-3"><strong>Telp 1 :</strong> <span>+62 878 2413 0067</span></p>
                        <p><strong>Telp 2 :</strong> <span>+62 831 0010 0370</span></p>
                    </div>
                    <div class="social-links d-flex mt-4">
                        <a href="wa.me/6287824130067"><i class="bi bi-whatsapp"></i></a>
                        <a href=""><i class="bi bi-facebook"></i></a>
                        <a href="instagram.com/basecampsportcenter"><i class="bi bi-instagram"></i></a>
                    </div>
                </div>

                <div class=" col-6 col-lg-4 col-md-6 footer-links">
                    <h4>Navigasi</h4>
                    <div class="row">
                        <div class="col-6 col-lg-4">
                            <ul>
                                <li><a href="index.php">Beranda</a></li>
                                <li><a href="lapangan.php">Lapangan</a></li>
                                <li><a href="kontak.php">Kontak</a></li>
                            </ul>
                        </div>
                    </div>
                </div>


                <div class="col-6 col-lg-4 col-md-6 footer-links">
                    <h4>Syarat & Ketentuan</h4>
                    <ul>
                        <li><a href="#">Lihat Syarat & Ketentuan</a></li>
                    </ul>
                </div>

            </div>
        </div>

    </footer>


    <!-- Modal -->
    <div class="modal fade" id="bayarModal" tabindex="-1" aria-labelledby="bayarModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 rounded-3 shadow-sm">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="tambahBookingModalLabel">Bayar Pesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="post" id="bayar-form" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $id; ?>">
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="tgl_main" class="form-label">Tanggal Booking</label>
                                <input type="date" name="tgl_book" class="form-control" id="tgl_main" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="lapanganSelect" class="form-label">Lapangan:</label>
                                <input type="text" name="name" class="form-control" id="name" value="<?= $detail['name']; ?>" readonly>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nama" class="form-label">Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control" id="nama" required>
                            </div>
                            <div class="col-md-6">
                                <label for="no_hp" class="form-label">No Handphone</label>
                                <input type="number" name="no_hp" class="form-control" id="no_hp" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="table" class="form-label">List Pesanan</label>
                            <div class="table-responsive">
                                <table id="jam_main" class="table table-striped ">
                                    <thead class="table-dark">
                                        <tr>
                                            <th scope="col">Tanggal</th>
                                            <th scope="col">Jam</th>
                                            <th scope="col">Harga</th>
                                            <th scope="col">Diskon</th>
                                            <th scope="col">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Baris data akan diisi di sini -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="catatan" class="form-label">Catatan</label>
                                <textarea name="catatan" class="form-control" id="catatan" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="row mb-3">

                            <div class="col-md-6">
                                <label for="total" class="form-label">Total</label>
                                <div class="input-group">
                                    <span class="input-group-text">IDR</span>
                                    <input type="text" name="totalAwal" class="form-control" id="totalAwal" readonly>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="total" class="form-label">Diskon Member</label>
                                <div class="input-group">
                                    <span class="input-group-text">IDR</span>
                                    <input type="text" name="diskon_member" class="form-control" id="diskon_member" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="total" class="form-label">Sub Total</label>
                                <div class="input-group">
                                    <span class="input-group-text">IDR</span>
                                    <input type="text" name="total" class="form-control" id="total" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="photo" class="form-label">Upload Bukti</label>
                                <input type="file" name="photo" class="form-control" id="photo" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Transfer ke : Rek. BRI an. Novie Rosmayanti & Elis Lisdiani <strong>No. 0401-01-888000-56-9</strong></label>
                        </div>
                        <!-- Submit Button -->
                        <div class="d-grid gap-2">
                            <button type="button" id="bayar" class="btn btn-success">Bayar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script src="assets/js/main.js"></script>
        
    <script>
        // Inisialisasi Flatpickr pada elemen input booking-date
        flatpickr("#booking_date_1", {
            dateFormat: "Y-m-d", // Format tanggal yang diinginkan (misalnya: 2024-09-25)
            minDate: "today", // Hanya memungkinkan pemilihan tanggal dari hari ini ke depan
            enableTime: false // Mengatur apakah akan memasukkan waktu juga (false karena hanya tanggal)
        });
    </script>

    <script>
        $(document).ready(function() {
            let activeCategoryButton = null; // Variable to keep track of the currently active button
            let id = <?php echo json_encode($id); ?>;
            let total = '';
            let selectedDates = [];
            let selectedTimeCategory = '';
            let selectedTimes = [];
            let dateCount = 1; // Number of date inputs currently on the form
            const maxDates = 4; // Maximum number of date inputs allowed
            // Initial total price
            let totalPrice = 0;

            // Fungsi untuk menghapus simbol 'Rp' dan pemisah ribuan
            function cleanRupiah(input) {
                return input.replace(/[^,\d]/g, '').replace(/,/g, '');
            }

            function formatRupiah(angka) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0, // Tidak menampilkan desimal
                    maximumFractionDigits: 0 // Tidak menampilkan desimal
                }).format(angka);
            }

            function formatDate(dateString) {
                const dateParts = dateString.split('-'); // Memisahkan string berdasarkan tanda "-"
                const year = dateParts[0]; // Tahun (YYYY)
                const month = dateParts[1]; // Bulan (MM)
                const day = dateParts[2]; // Hari (DD)

                // Mengembalikan format DD-MM-YYYY
                return `${day}-${month}-${year}`;
            }

            // Check if there are no selected dates or times
            if (!selectedDates || selectedDates.length === 0 || selectedTimes.length === 0) {
                $('.pesan').prop('disabled', true);
            } else {
                $('.pesan').prop('disabled', false);
            }

            // Function to update the total price display
            function updateTotalPrice() {
                const totalPrice = calculatePrice();
                $('#total').val(totalPrice); // Update the total price input field
            }


            // Handle date selection
            // Function to handle enabling/disabling the "Add Date" button
            function updateAddDateButton() {
                if (dateCount >= maxDates) {
                    $('#add-date-btn').prop('disabled', true); // Disable the button if the maximum is reached
                } else {
                    $('#add-date-btn').prop('disabled', false); // Enable the button if the number of dates is below the maximum
                }
            }

            // Handle date selection
            $(document).on('change', '.booking-date', function() {
                // Ambil semua nilai dari input tanggal
                selectedDates = $('.booking-date').map(function() {
                    return $(this).val();
                }).get();

                console.log("Selected Dates:", selectedDates); // Debugging

                if (selectedDates.length) {
                    // Disable buttons if no dates selected
                    $('.btn-outline-primary').prop('disabled', false);

                    // Pilih tanggal terbaru dari array
                    const latestDate = getLatestDate(selectedDates);
                    console.log("Latest Date:", latestDate);

                    // Call fetchAvailableTimes only if there are at least 2 dates selected
                    if (selectedDates.length > 0) {
                        $('#available-times').empty(); // Clear previous times

                    }
                } else {
                    $('.btn-outline-primary').prop('disabled', true);

                    $('#available-times').empty();
                }
            });

            $('#add-date-btn').on('click', function() {
                if (dateCount < maxDates) {
                    dateCount++;
                    $('#date-container').append(`
                    <div class="row d-flex justify-content-between align-items-center">
                                <div class="col-8">
                                    <div class="form-group">
                                        <label for="booking_date_${dateCount}">Tanggal</label>
                                        <input type="date" id="booking_date_${dateCount}" name="booking_date[]" placeholder="Select date" class="form-control booking-date" required>
                                    </div>
                                </div>
                                <div class="col-4 d-flex justify-content-center align-items-center">
                                    <button type="button" data-date="${dateCount}"  class="btn btn-danger ">Hapus</button>
                                </div>

                            </div>
                    `);

                    // Update the button state if needed
                    updateAddDateButton();

                    // Clear available times
                    $('#available-times').empty();
                }
            });

            // Optional: Use event delegation to handle dynamic elements
            $('#date-container').on('click', '.btn-danger', function() {
                const dateId = $(this).data('date'); // Get the date ID from the button
                const dateValue = $(`#booking_date_${dateId}`).val(); // Get the date value from the input

                $(this).closest('.row').remove();

                // Update the selectedDates array
                selectedDates = selectedDates.filter(date => date !== dateValue);

                // Update the selectedTimes array
                selectedTimes = selectedTimes.map(slot => {
                    // Remove the date from the selectedDates of each slot
                    return {
                        ...slot,
                        selectedDates: slot.selectedDates.filter(date => date !== dateValue)
                    };
                }).filter(slot => slot.selectedDates.length > 0); // Remove empty slots

                dateCount--;
                updateAddDateButton();
                updateTotalPrice();
            });


            // O
            // Helper function to get the latest date
            function getLatestDate(dates) {
                // Filter out invalid dates
                const validDates = dates.filter(date => {
                    const parsedDate = new Date(date);
                    return !isNaN(parsedDate.getTime()); // Check if the date is valid
                });

                // Check if there are valid dates
                if (validDates.length === 0) {
                    console.error('No valid dates provided.');
                    return null; // or return a default value if needed
                }

                // Convert valid dates to Date objects
                const dateObjects = validDates.map(date => new Date(date));
                // Find the latest date
                const latestDate = new Date(Math.max.apply(null, dateObjects));
                // Format the latest date to a string
                return latestDate.toISOString().split('T')[0];
            }


            // Handle time button clicks
            // Handle click event on time buttons
            $('#available-times').on('click', '.time-button', function() {

                const date = $(this).data('date');
                const startTime = $(this).data('start-time');
                const endTime = $(this).data('end-time');
                const price = $(this).data('price');
                const diskon = $(this).data('diskon');
                const total = $(this).data('total');
                const priceMember= $(this).data('price-member');
                const diskonMember = $(this).data('diskon-member');
                const totalMember = $(this).data('total-member');
                const status = $(this).data('status');

                console.log("Date from Button:", date);


                // Toggle selection
                if ($(this).hasClass('active')) {
                    $(this).removeClass('active');
                    // Remove time from selectedTimes if already selected
                    selectedTimes = selectedTimes.filter(slot => !(slot.startTime === startTime && slot.selectedDates.includes(date)));
                } else {
                    $(this).addClass('active');
                    // Add time slot with price to selectedTimes
                    selectedTimes.push({
                        selectedDates: [date], // Store current date only for the time slo
                        startTime,
                        endTime,
                        price,
                        diskon,
                        total,
                        priceMember,
                        diskonMember,
                        totalMember
                    });
                }

                // Check if there are no selected dates or times
                if (!selectedDates || selectedDates.length === 0 || selectedTimes.length === 0) {
                    // Disable the button if no dates or times are selected
                    $('.pesan').prop('disabled', true);
                } else {
                    // Enable the button if dates and times are selected
                    $('.pesan').prop('disabled', false);
                }

            });

            // Handle booking
            $('#pesan').on('click', function() {
                event.stopPropagation(); // Mencegah event bubbling

                console.log(selectedTimes);
                

                // Check if there are no selected dates or times
                if (!selectedDates || selectedDates.length === 0 || selectedTimes.length === 0) {
                    // Use SweetAlert to display the error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Pilih tanggal dan waktu main terlebih dahulu.',
                    });
                    return;
                }

                const catatan = $('#catatan').val();

                // Calculate total price
                let totalPrice = calculateHargaAwal();
                let totalPriceFinal = calculatePrice();
                let totalDiscount = calculateDiskon();

                const today = new Date();
                const yyyy = today.getFullYear();
                let mm = today.getMonth() + 1; // January is 0!
                let dd = today.getDate();

                // Format month and day to ensure two digits
                if (mm < 10) mm = '0' + mm;
                if (dd < 10) dd = '0' + dd;

                const todayFormatted = yyyy + '-' + mm + '-' + dd;

                $('#bayarModal #tgl_main').val(todayFormatted); // Set today's date in the input
                $('#bayarModal #lapangan').val(todayFormatted); // Set today's date in the input
                $('#bayarModal #catatan').val(catatan);

                let jamMainContainer = $('#bayarModal #jam_main tbody');
                jamMainContainer.empty(); // Clear previous times

                // Always append the selected times to the modal table
                selectedTimes.forEach(function(time) {
                    time.selectedDates.forEach(date => {
                        const row = $('<tr></tr>'); // Create a new table row

                        // Create and append cells
                        row.append($('<td></td>').text(formatDate(date))); // Date
                        row.append($('<td></td>').text(`${time.startTime} - ${time.endTime}`)); // Time range
                        row.append($('<td></td>').text(`${formatRupiah(selectedTimes.length >= 4?time.priceMember: time.price)}`)); // Price
                        row.append($('<td></td>').text(`${selectedTimes.length >= 4?time.diskonMember: time.diskon} %`)); // Default diskon 0, will be updated if needed
                        row.append($('<td></td>').text(`${formatRupiah(selectedTimes.length >= 4?time.totalMember: time.total)}`)); // Total


                        jamMainContainer.append(row); // Append row to the table
                    });
                });
                $('#bayarModal #totalAwal').val(`${formatRupiah(totalPrice)}`);
                $('#bayarModal #total').val(`${formatRupiah(totalPriceFinal)}`); 
                    $('#bayarModal #diskon_member').val(`${formatRupiah(totalDiscount)}`); 
                // Cek jumlah tanggal yang dipilih, jika kurang dari 4, diskon tidak akan diterapkan
                // if (selectedTimes.length < 4) {
                //     // Tidak ada diskon
                //     $('#bayarModal #diskon_member').val('Minimal booking 4'); // Atur diskon jadi 0
                // } 

                // Show the modal after everything is set
                $('#bayarModal').modal('show');
            });



            // Event handler for removing a row
            $('#jam_main').on('click', '.btn-danger', function() {
                const date = $(this).data('date');
                const startTime = $(this).data('start-time');
                const endTime = $(this).data('end-time');
                const price = $(this).data('price');
                const diskon = $(this).data('diskon');
                const total = $(this).data('total');

                // Remove the row from the table
                $(this).closest('tr').remove();

                // Remove the time slot from the selectedTimes array
                selectedTimes = selectedTimes.map(slot => {
                    return {
                        ...slot,
                        selectedDates: slot.selectedDates.filter(d => !(d === date && slot.startTime === startTime && slot.endTime === endTime))
                    };
                }).filter(slot => slot.selectedDates.length > 0); // Remove empty slot entries

                // Deduct the price from the total
                totalPrice -= total; // Use total to reflect the price with any discount included

                // Ensure total price does not go negative
                if (totalPrice < 0) totalPrice = 0;

                // Update the total price display
                updateTotalPrice();
            });



            // Function to fetch available times from the server
            function fetchAvailableTimes(dates) {

                const datesString = dates.join(',');


                if (!dates.length) {
                    console.error('Date is required.');
                    return;
                }



                $.ajax({
                    url: 'controller/getAvailableTimes.php',
                    type: 'GET',
                    data: {
                        dates: datesString,
                        id: id
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);
                        if (response.success) {
                            const availableTimes = response.data || [];
                            updateAvailableTimes(availableTimes);
                        } else {
                            console.error('Failed to fetch available times:', response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                        console.error('Response Text:', xhr.responseText);

                    }
                });
            }

            function calendarDisplay(dates) {
                $.ajax({
                    url: 'controller/getCalender.php',
                    type: 'GET',
                    data: {
                        dates: dates,
                        id: id
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // Create HTML table from data
                            let tableHtml = `
        <table class="table table-bordered table-hover table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Waktu</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
    `;

                            response.data.forEach(timeSlot => {
                                const timeRange = `${timeSlot.start_time} - ${timeSlot.end_time}`;
                                const status = timeSlot.status;
                                // Determine class based on status
                                const rowClass = status === 'booked' ? 'table-danger' : 'table-success';
                                tableHtml += `
            <tr class="${rowClass}">
                <td>${timeRange}</td>
                <td>${status}</td>
            </tr>
        `;
                            });

                            tableHtml += `
            </tbody>
        </table>
    `;

                            // Update the #schedule element with the HTML table
                            $('#schedule').html(tableHtml);
                        } else {
                            $('#schedule').html('<p>Data tidak ditemukan.</p>');
                        }

                    },
                    error: function(xhr, status, error) {
                        $('#schedule').html('<p>Terjadi kesalahan: ' + error + '</p>');
                    }
                });
            }


            $('.btn-outline-primary[data-time-category]').on('click', function(e) {
                e.preventDefault(); // Mencegah aksi default jika diperlukan

                // Remove 'active' class from previously active button
                if (activeCategoryButton) {
                    $(activeCategoryButton).removeClass('active');
                }

                // Add 'active' class to the currently clicked button
                $(this).addClass('active');

                // Update the reference to the currently active button
                activeCategoryButton = this;

                selectedTimeCategory = $(this).data('time-category');
                if (selectedDates) {
                    fetchAvailableTimes(selectedDates, selectedTimeCategory); // Fetch times based on date and category
                } else {
                    console.error('Please select a date first.');
                }
            });

            // Function to update available times based on fetched data
            function updateAvailableTimes(availableTimes) {
                $('#available-times').empty(); // Clear previous times


                if (!Array.isArray(availableTimes)) {
                    console.error('Invalid data format for available times');
                    return;
                }

                const morningTimes = [];
                const eveningTimes = [];

                availableTimes.forEach(timeSlot => {
                    if (!timeSlot.start_time || !timeSlot.end_time) {
                        console.error('Missing start_time or end_time in time slot:', timeSlot);
                        return;
                    }


                    const startHour = formatTime(timeSlot.start_time);
                    const endHour = formatTime(timeSlot.end_time);
                    // Determine if timeSlot is morning or evening
                    const startHourInt = parseInt(startHour.split(':')[0], 10);
                    const endHourInt = parseInt(endHour.split(':')[0], 10);
                    // Check if start or end time falls within morning or evening category
                    if ((startHourInt >= 8 && startHourInt < 16) || (endHourInt > 8 && endHourInt <= 16)) {
                        morningTimes.push({
                            date: timeSlot.tanggal,
                            startTime: timeSlot.start_time,
                            endTime: timeSlot.end_time,
                            price: timeSlot.harga,
                            diskon: timeSlot.diskon,
                            total: timeSlot.total,
                            priceMember: timeSlot.harga_member,
                            diskonMember: timeSlot.diskon_member,
                            totalMember: timeSlot.total_member,

                        });
                    } else if ((startHourInt >= 17 && startHourInt < 22) || (endHourInt >= 17 && endHourInt <= 22)) {
                        eveningTimes.push({
                            date: timeSlot.tanggal,
                            startTime: timeSlot.start_time,
                            endTime: timeSlot.end_time,
                            price: timeSlot.harga,
                            diskon: timeSlot.diskon,
                            total: timeSlot.total,
                            priceMember: timeSlot.harga_member,
                            diskonMember: timeSlot.diskon_member,
                            totalMember: timeSlot.total_member,
                        });
                    }
                });

                // Determine which category to show based on selectedTimeCategory
                const timesToDisplay = selectedTimeCategory === 'morning' ? morningTimes : eveningTimes;

                timesToDisplay.forEach(timeSlot => {
                    console.log(timeSlot);
                    console.log("Button Date:", timeSlot.date); // Ensure date matches

                    const button = $('<button></button>', {
                        type: 'button',
                        class: 'btn btn-outline-primary time-button',
                        html: `${timeSlot.startTime} - ${timeSlot.endTime}`,
                        'data-date': timeSlot.date,
                        'data-start-time': timeSlot.startTime,
                        'data-end-time': timeSlot.endTime,
                        'data-price': timeSlot.price,
                        'data-diskon': timeSlot.diskon,
                        'data-total': timeSlot.total,
                        'data-price-member': timeSlot.priceMember,
                        'data-diskon-member': timeSlot.diskonMember,
                        'data-total-member': timeSlot.totalMember
                    });


                    // Check if the current timeSlot is active and add the 'active' class if needed
                    if (selectedTimes.some(slot => slot.startTime === timeSlot.startTime && slot.selectedDates.includes(timeSlot.date))) {
                        button.addClass('active');
                    }


                    $('#available-times').append(button);

                });
            }


            function calculatePrice() {
                
                // Total price is calculated by summing up the price of each selected time slot
                var totalPrice = selectedTimes.reduce((total, slot) => total + slot.total || 0, 0);

                if(selectedTimes.length >= 4){
                     totalPrice = selectedTimes.reduce((totalMember, slot) => totalMember + slot.totalMember || 0, 0);
                }
                return totalPrice;
            }
            function calculateHargaAwal() {
                
                // Total price is calculated by summing up the price of each selected time slot
                var totalPrice = selectedTimes.reduce((price, slot) => price + slot.price || 0, 0);

                if(selectedTimes.length >= 4){
                     totalPrice = selectedTimes.reduce((priceMember, slot) => priceMember + slot.priceMember || 0, 0);
                }
                return totalPrice;
            }
            function calculateDiskon() {
                
                // Total price is calculated by summing up the price of each selected time slot
                var totalPrice = selectedTimes.reduce((total, slot) => total + ( slot.price - slot.total || 0), 0);

                if(selectedTimes.length >= 4){
                    totalPrice = selectedTimes.reduce((total, slot) => total + ( slot.priceMember - slot.totalMember || 0), 0);
                }
                return totalPrice;
            }

            function formatTime(timeString) {
                const date = new Date(`1970-01-01T${timeString}`);
                const hours = date.getHours().toString().padStart(2, '0');
                const minutes = date.getMinutes().toString().padStart(2, '0');
                return `${hours}:${minutes}`;
            }


            $('#bayar').on('click', function() {
                var formData = new FormData($('#bayar-form')[0]); // Ambil data dari form
                const name = $('#nama').val(); // Example for name input field
                const tgl_main = $('#tgl_main').val(); // Example for name input field
                const noHp = $('#no_hp').val(); // Example for phone number input field
                const totalValue = $('#total').val(); //
                const totalClean = cleanRupiah(totalValue);
                formData.set('total', totalClean);
                // Additional validation for other form fields (example: name and phone number)
                if (!name) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Masukan nama terlebih dahulu',
                    });
                    return; // Stop the execution if validation fails
                }

                if (!noHp) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Masukan No handphone terlebih dahulu',
                    });
                    return; // Stop the execution if validation fails
                }

                selectedTimes.forEach((slot, timeIndex) => {
                    slot.selectedDates.forEach((date, dateIndex) => {
                        formData.append(`selected_times[${timeIndex}][selectedDates][${dateIndex}]`, date);
                        formData.append(`selected_times[${timeIndex}][startTime]`, slot.startTime);
                        formData.append(`selected_times[${timeIndex}][endTime]`, slot.endTime);
                        formData.append(`selected_times[${timeIndex}][price]`, selectedTimes.length >= 4? slot.priceMember:slot.price);
                        formData.append(`selected_times[${timeIndex}][diskon]`, selectedTimes.length >= 4?slot.diskonMember: slot.diskon);
                        formData.append(`selected_times[${timeIndex}][total]`, selectedTimes.length >= 4?slot.totalMember:slot.total);
                    });
                });


                $.ajax({
                    url: 'controller/saveBooking.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.success === false) {
                            // Display SweetAlert if there's an error
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: response.message, // Use the error message returned from the server
                            });
                        } else {
                            Swal.fire({
                                icon: 'success',
                                title: 'Yess!',
                                text: response.message, // Gunakan pesan yang dikembalikan dari server
                                confirmButtonText: 'OK' // Menampilkan tombol "OK"
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Setelah tombol OK diklik, lakukan aksi berikut:

                                    // Memanggil fungsi untuk menampilkan kalender lagi
                                    calendarDisplay(tgl_main);

                                    var bookingId = response.booking_id;

                                    // URL untuk mengunduh invoice
                                    var invoiceUrl = 'admin/controller/invoice.php?id=' + bookingId;

                                    // Membuka invoice untuk diunduh di tab baru
                                    window.open(invoiceUrl, '_blank');

                                    // Pesan WhatsApp
                                    var whatsappMessage = "Saya sudah melakukan booking. Berikut adalah invoice saya: ";
                                    var whatsappPhoneNumber = "+6287824130067"; // Ganti dengan nomor WhatsApp Anda
                                    var encodedMessage = encodeURIComponent(whatsappMessage);
                                    var whatsappURL = `https://wa.me/${whatsappPhoneNumber}?text=${encodedMessage}`;

                                    // Mengarahkan pengguna ke WhatsApp setelah delay 1 detik
                                    setTimeout(function() {
                                        window.location.href = whatsappURL;
                                    }, 1000);
                                }
                            });
                        }

                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                        console.error('Response Text:', xhr.responseText);
                    }
                });

            });




            const calendarEl = document.getElementById('calendarEl');

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                responsive: true,
                views: {
                    dayGridMonth: {
                        titleFormat: {
                            year: 'numeric',
                            month: 'short'
                        }
                    }
                },
                height: 'auto',
                validRange: {
                    start: new Date(),
                    end: new Date(new Date().setMonth(new Date().getMonth() + 3))
                },
                datesSet: function(info) {
                    // Ini akan dipanggil setiap kali tampilan kalender diubah
                    const today = new Date();
                    const year = today.getFullYear();
                    const month = String(today.getMonth() + 1).padStart(2, '0'); // Months are zero-based
                    const day = String(today.getDate()).padStart(2, '0');
                    const localToday = `${year}-${month}-${day}`;

                    const todayEl = document.querySelector(`.fc-daygrid-day[data-date="${today}"]`);


                    if (!$(todayEl).hasClass('fc-day-selected')) {
                        $('.fc-daygrid-day.fc-day-selected').removeClass('fc-day-selected');
                        $(todayEl).addClass('fc-day-selected');

                        selectedDate = localToday;
                        calendarDisplay(selectedDate);
                    }

                },
                dateClick: function(info) {
                    console.log('clicked on ' + info.dateStr);
                    $('.fc-daygrid-day.fc-day-selected').removeClass('fc-day-selected');
                    $(info.dayEl).addClass('fc-day-selected');

                    selectedDate = info.dateStr;
                    calendarDisplay(selectedDate);
                }
            });

            calendar.render();

        });
    </script>



</body>

</html>