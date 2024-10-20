<?php
session_start();
require "../session.php";
require "../functions.php";

if ($role !== 'Admin') {
  header("location:../login.php");
}

$lapangan = query("SELECT id, name FROM lapangan");


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../style.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Sertakan CSS DataTables dan Buttons -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

  <!-- SweetAlert2 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

  <!-- SweetAlert2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


  <title>Home</title>
  <style>
    /* General Table Styling */
    table.dataTable {
      width: 100%;
      border-collapse: collapse;
      font-size: 14px;
    }

    table.dataTable th,
    table.dataTable td {
      padding: 8px 10px;
      text-align: left;
      vertical-align: middle;
    }

    /* Adjust Column Widths */
    table.dataTable th:nth-child(1) {
      /* Tanggal */
      width: 100px;
    }

    table.dataTable th:nth-child(3),
    /* No Hp */
    table.dataTable th:nth-child(5),
    /* Harga */
    table.dataTable th:nth-child(6),
    /* Diskon */
    table.dataTable th:nth-child(7),
    /* SubTotal */
    table.dataTable th:nth-child(8) {
      /* Status */
      width: 80px;
    }

    /* Ellipsis for Long Text */
    td {
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    td:hover {
      overflow: visible;
      white-space: normal;
    }

    /* Responsive Table */
    .table-responsive {
      overflow-x: auto;
    }

    /* Image Styling in Bukti Column */
    td img {
      max-width: 50px;
      max-height: 50px;
      object-fit: cover;
    }

    /* Styling for Action Buttons */
    td .btn {
      font-size: 12px;
    }

    .custom-checkbox .form-check-input {
      width: 24px;
      /* Increase size */
      height: 24px;
      /* Increase size */
      border: 2px solid #007bff;
      /* Add border color */
      border-radius: 4px;
      /* Rounded corners */
      margin-top: 5px;
      /* Adjust margin to align better */
    }

    .custom-checkbox .form-check-input:checked {
      background-color: #007bff;
      /* Change background color when checked */
      border-color: #0056b3;
      /* Darker border when checked */
    }

    .custom-checkbox .form-check-input:hover {
      cursor: pointer;
      /* Change cursor on hover */
      box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
      /* Add hover effect */
    }

    /* Adjust the width and height of the SweetAlert2 modal */
    .my-swal-container .swal2-popup {
      width: 90%;
      /* Adjust width as needed */
      max-width: 1000px;
      /* Adjust maximum width as needed */
      padding: 10px;
      /* Add padding if necessary */
    }

    /* Optional: Adjust font size inside the modal */
    .my-swal-container .swal2-html-container {
      font-size: 16px;
      /* Adjust font size as needed */
    }

    .my-swal-container .swal2-input {
      border-radius: 5px;
      /* Rounded corners */
      padding: 10px;
      /* Padding for inputs */
      width: calc(100% - 20px);
      /* Full width minus padding */
    }

    .my-swal-container .swal2-title {
      font-size: 1.5em;
      /* Larger title font size */
      margin-bottom: 15px;
      /* Spacing below the title */
    }

    .my-swal-container .swal2-label {
      margin-top: 10px;
      /* Add some spacing above labels */
      font-size: 1em;
      /* Slightly larger font for labels */
    }

    .my-swal-container {
      background-color: #ffffff;
      /* Background color for the alert */
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
      /* Subtle shadow for depth */
    }


    @media (max-width: 768px) {
      .popup-with-scroll {
        max-width: 100%;
        overflow-x: auto;
      }

      .popup-with-scroll table {
        width: 100%;
        min-width: 600px;
        /* Lebar minimum tabel di perangkat kecil */
      }
    }
  </style>
</head>

<body>
  <div class="wrapper">
    <aside id="sidebar">
      <div class="d-flex">
        <button class="toggle-btn" type="button">
          <i class="fa-solid fa-bars"></i>
        </button>
        <div class="sidebar-logo">
          <a href="#" class="text-decoration-none"><?= $_SESSION['username']; ?></a>
        </div>
      </div>
      <ul class="sidebar-nav">
        <li class="sidebar-item">
          <a href="home.php" class="sidebar-link">
            <i class="fa-solid fa-house"></i>
            <span>Beranda</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a href="member.php" class="sidebar-link">
            <i class="fa-solid fa-user"></i>
            <span>Data Member</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a href="lapangan.php" class="sidebar-link">
            <i class="fa-solid fa-dumbbell"></i>
            <span>Data Lapangan</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a href="pesan.php" class="sidebar-link">
            <i class="fa-solid fa-money-bills"></i>
            <span>Data Pesanan</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a href="admin.php" class="sidebar-link">
            <i class="fa-solid fa-user-tie"></i>
            <span>Data Admin</span>
          </a>
        </li>
      </ul>
      <div class="sidebar-footer">
        <a href="../logout.php" class="sidebar-link">
          <i class="fa-solid fa-right-from-bracket"></i>
          <span>Logout</span>
        </a>
      </div>
    </aside>
    <div class="main">
      <nav class="navbar bg-light shadow">
        <div class="container-fluid">
          <a class="navbar-brand" href="#">
            <img src="../assets/img/logo.png" alt="Logo" width="30" height="24" class="d-inline-block align-text-top">
            Admin Dashboard
          </a>
        </div>
      </nav>
      <!-- Konten -->
      <div class="container mb-5">
        <!-- Konten -->
        <h3 class="mt-4">Data Pesanan</h3>
        <hr>
        <div class="table-responsive">
          <table id="lapanganTable" class="table table-hover">
            <thead class="table-inti">
              <tr>
                <th scope="col">Tanggal</th>
                <th scope="col">Nama</th>
                <th scope="col">No Hp</th>
                <th scope="col">Catatan</th>
                <th scope="col">DP</th>
                <th scope="col">Harga</th>
                <th scope="col">Diskon</th>
                <th scope="col">DscMbr</th>
                <th scope="col">SubTotal</th>
                <th scope="col">Bukti</th>
                <th scope="col">Status</th>
                <th scope="col">Aksi</th>
              </tr>
            </thead>
            <tbody>

            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Bukti Pembayaran</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <img id="modalImage" src="" alt="Gambar" class="img-fluid">
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Structure -->
    <div class="modal fade" id="tambahBookingModal" tabindex="-1" aria-labelledby="tambahBookingModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg"> <!-- Added modal-lg for larger size -->
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="tambahBookingModalLabel">Tambah Booking</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">

            <!-- Booking Form -->
            <form id="bayar-form">
              <!-- Lapangan and Tanggal -->
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="lapanganSelect" class="form-label">Lapangan</label>
                  <select class="form-select" id="lapanganSelect" name="id" required>
                    <option selected disabled value="0">Pilih Lapangan...</option>
                    <?php if (!empty($lapangan)) : ?>
                      <?php foreach ($lapangan as $row) : ?>
                        <option value="<?= htmlspecialchars($row['id']); ?>"><?= htmlspecialchars($row['name']); ?></option>
                      <?php endforeach; ?>
                    <?php else : ?>
                      <option disabled>Tidak ada lapangan tersedia</option>
                    <?php endif; ?>
                  </select>

                </div>
                <div class="col-md-6">
                  <label for="tanggalBooking" class="form-label">Tanggal Booking</label>
                  <input type="date" class="form-control" id="tgl_book" name="tgl_book" readonly>
                </div>
              </div>

              <!-- Schedule Table for Lapangan -->
              <!-- Table to Display Available Times -->
              <div class="table-responsive mb-3">
                <table id="availableTimesTable" class="table display">
                  <thead>
                    <tr>
                      <th scope="col">Tanggal</th>
                      <th scope="col">Jam</th>
                      <th scope="col">Harga</th>
                      <th scope="col">Diskon</th>
                      <th scope="col">Total</th>
                      <th scope="col">Tambah</th>
                    </tr>
                  </thead>
                  <tbody>
                    <!-- Dynamic rows will be inserted here via AJAX -->
                  </tbody>
                </table>

              </div>

              <!-- Other Input Fields -->
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="namaBooking" class="form-label">Nama</label>
                  <input type="text" name="nama" id="nama" class="form-control" required>
                </div>
                <div class="col-md-6">
                  <label for="noHpBooking" class="form-label">No HP</label>
                  <input type="text" class="form-control" id="no_hp" name="no_hp" required>
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-md-12">
                  <label for="catatanBooking" class="form-label">Catatan</label>
                  <textarea class="form-control" id="catatan" name="catatan" rows="3"></textarea>
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="namaBooking" class="form-label">Total</label>
                  <input type="text" name="total" id="total" class="form-control" required>
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-md-6">
                  <div class="input-group mb-3">
                    <span class="input-group-text">Diskon </span>
                    <input type="text" name="diskon" class="form-control" id="diskon" readonly>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="input-group mb-3">
                    <span class="input-group-text">Sub Total</span>
                    <input type="text" name="subtotal" class="form-control" id="subtotal" readonly>
                  </div>
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="totalBooking" class="form-label">Nominal Bayar</label>
                  <input type="text" class="form-control" id="nominal" name="nominal" required>
                </div>
                <div class="col-md-6">
                  <label for="buktiPembayaran" class="form-label">Bukti Pembayaran</label>
                  <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                </div>
              </div>

              <!-- Submit Button -->

              <div class="d-grid gap-2">
                <button type="button" id="simpanBooking" class="btn btn-primary">Simpan Booking</button>
              </div>

            </form>

          </div>
        </div>
      </div>
    </div>



    <!-- DataTables JS -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>


    <!-- PDFMake for PDF export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

    <script>
      $(document).ready(function() {
        fetchTableData()


        // Array untuk menampung data yang dipilih
        let selectedBookings = [];
        // Initial total price
        let totalPrice = 0;


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

        // Event listener untuk memformat nilai input saat diubah
        document.getElementById('nominal').addEventListener('input', function(e) {
          // Hilangkan semua karakter selain angka sebelum memformat
          let value = e.target.value.replace(/[^0-9]/g, '');

          // Format nilai input ke dalam format IDR
          e.target.value = formatRupiah(value);
        });


        // Function to calculate and update the total 
        function updateTotalPrice() {
          const totalPrice = calculatePrice();
          $('#subtotal').val(formatRupiah(totalPrice)); // Update the total price input field
          $('#total').val(formatRupiah(totalPrice)); // Update the total price input field
        }

        $('#lapanganSelect').on('change', function() {
          fetchAvailableTimes(this.value); // Here 'this' refers to the select element
        });

        function fetchAvailableTimes(lapanganId) {
          if (lapanganId && lapanganId != 0) { // Check if a valid lapangan is selected

            if (lapanganId) {
              // Send an AJAX request to the server
              $.ajax({
                url: 'controller/getAvailableTimes.php', // The server-side script that will handle the query
                type: 'GET',
                data: {
                  id: lapanganId
                },
                dataType: 'json',

                success: function(response) {
                  let tableContent = '';

                  if (response.success) {
                    const availableTimes = response.data || [];
                    // Iterate through the data and create table rows
                    availableTimes.forEach(function(item) {
                      let row = `
              <tr>
                <td>${formatDate(item.tanggal)}</td>
                <td>${item.start_time} - ${item.end_time}</td>
                <td>${formatRupiah(item.harga)}</td>
                <td>${item.diskon}%</td>
                <td>${formatRupiah(item.total)}</td>
    <td>
  <div class="form-check custom-checkbox">
    <input type="checkbox" class="form-check-input order-checkbox" data-id="${item.lapangan_id}" data-tanggal="${item.tanggal}" data-start="${item.start_time}" data-end="${item.end_time}" data-harga="${item.harga}" data-diskon="${item.diskon}" data-total="${item.total}">
  </div>
</td>

              </tr>
              `;

                      tableContent += row;
                    });

                    // Destroy previous DataTable instance (if any)
                    $('#availableTimesTable').DataTable().clear().destroy();

                    // Populate the available times table
                    $('#availableTimesTable tbody').html(tableContent);

                    $('#availableTimesTable').DataTable({
                      dom: 'Bfrtip', // Include Buttons in the DataTable
                      buttons: [{
                        text: 'Cek Pesanan',
                        className: 'btn btn-success', // Bootstrap success button
                        attr: {
                          id: 'pesanan' // Add custom ID attribute
                        },
                      }],
                      paging: true,
                      pageLength: 5,
                      searching: true,
                      ordering: false,
                      info: false,
                      lengthChange: false,
                      columnDefs: [{
                          targets: 1,
                          width: "25%"
                        }, // Kolom jam
                        {
                          targets: [0, 2, 3, 4],
                          width: "18%"
                        } // Kolom lainnya, totalnya 55%
                      ],
                      initComplete: function() {
                        // Bind the click event after DataTable initialization
                        $('#pesanan').on('click', function() {

                          // Clear the table content for SweetAlert
                          let tableContent = `
<div style="overflow-x: auto; max-width: 100%;">
      <table class="table table-hover table-striped table-bordered">
            <thead class="table-inti">
          <tr>
            <th>Tanggal</th>
            <th>Waktu</th>
            <th>Harga</th>
            <th>Diskon</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>`;

                          // Loop through selected bookings and append rows to the tableContent
                          selectedBookings.forEach(function(booking) {
                            tableContent += `
        <tr>
          <td>${formatDate(booking.tanggal)}</td>
                <td>${booking.start} - ${booking.end}</td>
                <td>${formatRupiah(booking.harga)}</td>
          <td>${booking.diskon}%</td>
                <td>${formatRupiah(booking.total)}</td>
        </tr>`;
                          });

                          // Close the table
                          tableContent += `</tbody></table></div>`;

                          // Display SweetAlert with tableContent inside the HTML parameter
                          Swal.fire({
                            title: 'Pesanan Terpilih',
                            html: tableContent, // Insert table content into SweetAlert's HTML parameter
                            width: '100%',
                            customClass: {
                              popup: 'popup-with-scroll'
                            }, // Optional: adjust width
                            showCloseButton: true,
                            focusConfirm: false,
                            confirmButtonText: 'Close'
                          });
                        });
                      }

                    });

                  }
                },
                error: function(xhr, status, error) {
                  console.error('Error:', error);
                  console.error('Text:', xhr.responseText);
                }
              });
            }
          }
        }

        // Ketika checkbox dicentang atau dihilangkan
        $(document).on('change', '.order-checkbox', function() {
          let data = {
            id: $(this).data('id'),
            tanggal: $(this).data('tanggal'),
            start: $(this).data('start'),
            end: $(this).data('end'),
            harga: $(this).data('harga'),
            diskon: $(this).data('diskon'),
            total: $(this).data('total')
          };

          if ($(this).is(':checked')) {
            var lapanganSelect = $('#tambahBookingModal #lapanganSelect').val();
            console.log(lapanganSelect);
            // Tambahkan data ke array jika dicentang
            selectedBookings.push(data);
            $('#total').val(formatRupiah(calculatePrice()));
            $('#subtotal').val(formatRupiah(calculatePrice()));
            // Cek apakah pesanan yang dipilih minimal 4

          } else {
            // Hapus data dari array jika dicentang 
            selectedBookings = selectedBookings.filter(slot => {
              // Return true for items that should remain
              return !(slot.tanggal === data.tanggal &&
                slot.start === data.start && slot.end === data.end &&
                slot.total === data.total);
            });
            totalPrice -= data.total;

            // Ensure total price does not go negative
            if (totalPrice < 0) totalPrice = 0;

            // Update the total price display
            updateTotalPrice();
            if (selectedBookings.length >= 4) {
              let diskon = $('#tambahBookingModal #diskon').val();
              // Pastikan totalPrice tidak negatif
              if (totalPrice < 0) totalPrice = 0;

              // Tampilkan harga total yang sudah didiskon
              $('#tambahBookingModal #subtotal').val(`${formatRupiah(totalPrice)}`);
            }
          }

          if (selectedBookings.length >= 4) {
            // Fetch diskon via AJAX
            const id = lapanganSelect; // Gunakan ID booking untuk request diskon
            $.ajax({
              url: '../controller/getDiskon.php',
              type: 'GET',
              data: {
                id: id
              },
              dataType: 'json',
              success: function(response) {
                console.log(response);
                if (response.success) {
                  const diskon = response.diskon;

                  // Update diskon di input
                  $('#tambahBookingModal #diskon').val(formatRupiah(diskon));

                  // Update harga total setelah diskon
                  totalPrice = calculatePrice() - diskon;

                  // Pastikan totalPrice tidak negatif
                  if (totalPrice < 0) totalPrice = 0;

                  // Tampilkan harga total yang sudah didiskon
                  $('#tambahBookingModal #subtotal').val(`${formatRupiah(totalPrice)}`);
                } else {
                  totalPrice = calculatePrice() - diskon;

                }
              },
              error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
                console.error('Response Text:', xhr.responseText);
              }
            });
          } else {
            // Jika kurang dari 4 pesanan, reset diskon dan tampilkan pesan
            $('#tambahBookingModal #diskon').val(0);
            $('#tambahBookingModal #subtotal').val(formatRupiah(calculatePrice()));

          }
        });




        $(document).on('click', '.image-thumbnail', function() {
          console.log('oke');
          var imageSrc = $(this).attr('src'); // Get the image source
          $('#modalImage').attr('src', imageSrc); // Set the modal image source
          $('#imageModal').modal('show'); // Show the modal
        });

        $('#modalImage').on('mouseenter', function() {
          $(this).addClass('zoomed');
        }).on('mouseleave', function() {
          $(this).removeClass('zoomed');
        });


        function fetchTableData() {
          $.ajax({
            url: 'controller/getPesanan.php', // Your PHP script URL
            type: 'GET',
            dataType: 'json',
            success: function(response) {
              console.log(response);
              
              let tableContent = '';
              const tableBody = $('#lapanganTable tbody'); // Replace with your table's ID

              const table = $('#lapanganTable');
              if (response.success) {
                console.log(response.data);
                const pesanan = response.data || [];

                pesanan.forEach(row => {
                  const newRow = `
            <tr>
              <td>${formatDate(row.booking_date)}</td>
              <td>${row.name}</td>
              <td>${row.no_hp}</td>
              <td>${row.catatan}</td>
              <td>Rp ${row.nominal ? row.nominal.toLocaleString('id-ID', { minimumFractionDigits: 0 }) : '0'}</td>
            <td> ${formatRupiah(row.total_harga) }</td>
            <td> ${formatRupiah(row.total_diskon)}</td>
            <td>Rp ${row.diskon_member ? row.diskon_member.toLocaleString('id-ID', { minimumFractionDigits: 0 }) : '0'}</td>
            <td>Rp ${row.total ? row.total.toLocaleString('id-ID', { minimumFractionDigits: 0 }) : '0'}</td>
              <td><img src="../img/${row.photo}" width="50" height="50" class="image-thumbnail"></td>
              <td>${row.status}</td>
              <td>
              ${row.status === 'Diterima' ? 
                `
                <button class="btn btn-success btn-selesai btn-sm" data-id="${row.booking_id}"><i class="fa fa-check-double"></i></button>
                <button class="btn btn-info btn-invoice btn-sm" data-id="${row.booking_id}"><i class="fa fa-download"></i></button>
                ` : 
                row.status === 'Belum Lunas' ? 
                `<button class="btn btn-warning btn-bayar btn-sm" data-id="${row.booking_id}" data-total="${row.total}" data-nominal="${row.nominal}"><i class="fa fa-money-bill"></i></button>
                ` :
                row.status === 'Menunggu' ?
                `<button class="btn btn-success btn-konfir btn-sm" data-id="${row.booking_id}"><i class="fa fa-check"></i></button>
                ` : 
                row.status === 'Selesai' ?
                `<button class="btn btn-info btn-invoice btn-sm" data-id="${row.booking_id}"><i class="fa fa-download"></i></button>
                `: ``
              }
                <a href="https://wa.me/${row.no_hp.replace(/[^0-9]/g, '')}" class="btn btn-primary btn-sm" target="_blank"><i class="fa fa-phone"></i></a>
                <button class="btn btn-primary lihat btn-sm" data-id="${row.booking_id}"><i class="fa fa-eye"></i></button>
                <button class="btn btn-danger btn-hapus btn-sm" data-id="${row.booking_id}"><i class="fa fa-trash"></i></button>

            </td>
            </tr>
          `;
                  tableContent += newRow;
                });

                // Destroy previous DataTable instance (if any)
                table.DataTable().clear().destroy();

                // Populate the available times table
                tableBody.html(tableContent);

                // Reinitialize DataTable with updated content
                table.DataTable({
                  paging: true,
                  pageLength: 5,
                  searching: true,
                  ordering: true,
                  info: false,
                  lengthChange: false,
                  autoWidth: true,

                  "dom": 'Bfrtip', // Include Buttons in the DataTable
                  "buttons": [{
                      extend: 'excel',
                      text: 'Download Excel',
                      title: 'Daftar Pesanan',
                      exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 10]
                      },
                    },
                    {
                      text: 'Tambah Booking',
                      action: function(e, dt, node, config) {
                        const today = new Date();
                        const yyyy = today.getFullYear();
                        let mm = today.getMonth() + 1; // January is 0!
                        let dd = today.getDate();

                        // Format month and day to ensure two digits
                        if (mm < 10) mm = '0' + mm;
                        if (dd < 10) dd = '0' + dd;

                        const todayFormatted = yyyy + '-' + mm + '-' + dd;

                        $('#tambahBookingModal #tgl_book').val(todayFormatted); // Set today's date in the input
                        $('#tambahBookingModal').modal('show');
                      },
                    }
                  ],

                });


              }
            },
            error: function(xhr, status, error) {
              console.log(xhr);
              console.error('AJAX Error:', status, error);
            }
          });
        }

        $(document).on('click', '.lihat', function() {
          const bookingId = $(this).data('id');

          console.log(bookingId);
          $.ajax({
            url: 'controller/getBookingDetails.php', // PHP script URL
            type: 'get',
            data: {
              id: bookingId
            },
            dataType: 'json',
            success: function(response) {
              console.log(response);
              let tableRows = '';

              response.forEach(booking => {
                const newRow = `
            <tr>
                <td>${booking.tanggal}</td>
                <td>${booking.lapangan_name}</td>
                <td>${booking.start_time} - ${booking.end_time}</td>
                <td>${formatRupiah(booking.harga)}</td>
                <td>${booking.diskon}%</td>
                <td>${formatRupiah(booking.available_total)}</td>
            </tr>
        `;
                tableRows += newRow;


              });

              Swal.fire({
                title: 'Detail Booking',
                html: `
<div style="overflow-x: auto; max-width: 100%;">
            <table class="table table-bordered display">
                <thead class="table-inti">
                    <tr>
                        <th>Tanggal</th>
                        <th>Lapangan</th>
                        <th>Waktu</th>
                        <th>Harga</th>
                        <th>Diskon</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    ${tableRows}
                </tbody>
            </table>
            </div>
        `,

                width: '100%',
                customClass: {
                  popup: 'popup-with-scroll'
                }, // Optional: adjust width
                showCloseButton: true,
                focusConfirm: false,
                confirmButtonText: 'Close'
              });

            },
            error: function(xhr, status, error) {
              console.log(xhr);
              
              console.error('AJAX Error:', status, error);
              Swal.fire({
                title: 'Error',
                text: 'An error occurred while fetching booking details.',
                icon: 'error'
              });
            }
          });
        });

        $(document).on('click', '.btn-konfir', function() {
          konfirRow(this); // Panggil fungsi konfirRow dan kirim elemen tombol yang diklik
        });


        $(document).on('click', '.btn-hapus', function() {

          removeRow(this); // Call removeRow function
        });

        $(document).on('click', '.btn-selesai', function() {

          selesaiRow(this); // Call removeRow function
        });

        function selesaiRow(button) {
          const id = $(button).data('id');
          console.log(id);

          // Confirm deletion using SweetAlert2 (optional)
          Swal.fire({
            title: 'Peringatan!',
            text: "Yakin ingin menyelesaikan pesanan ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yakin!'
          }).then((result) => {
            if (result.isConfirmed) {
              // Send the ID via AJAX
              $.ajax({
                url: 'controller/selesaiPesanan.php', // The PHP file handling the deletion
                type: 'POST',
                data: {
                  id: id
                }, // Send the ID in the data object
                success: function(response) {
                  // Handle the response from the server
                  Swal.fire(
                    'Yess!',
                    'Berhasil di selesaikan.',
                    'success'
                  ).then(() => {
                    // Reload the DataTable after a successful deletion
                    fetchTableData();
                  });
                },
                error: function(xhr, status, error) {
                  // Handle errors
                  Swal.fire(
                    'Error!',
                    'An error occurred while deleting the message.',
                    'error'
                  );
                }
              });
            }
          });
        }

        // Function to remove a row (if needed)
        function removeRow(button) {
          const id = $(button).data('id');
          console.log(id);

          // Confirm deletion using SweetAlert2 (optional)
          Swal.fire({
            title: 'Peringatan!',
            text: "Yakin ingin menghapus?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yakin!'
          }).then((result) => {
            if (result.isConfirmed) {
              // Send the ID via AJAX
              $.ajax({
                url: 'controller/hapusPesan.php', // The PHP file handling the deletion
                type: 'POST',
                data: {
                  id: id
                }, // Send the ID in the data object
                success: function(response) {
                  // Handle the response from the server
                  Swal.fire(
                    'Yess!',
                    'Berhasil dihapus.',
                    'success'
                  ).then(() => {
                    // Reload the DataTable after a successful deletion
                    fetchTableData();
                  });

                  $(button).closest('tr').remove(); // This removes the row from the DOM

                },
                error: function(xhr, status, error) {
                  // Handle errors
                  Swal.fire(
                    'Error!',
                    'An error occurred while deleting the message.',
                    'error'
                  );
                }
              });
            }
          });
        }

        function konfirRow(button) {
          const id = $(button).data('id');
          console.log(id);
          // Confirm deletion using SweetAlert2 (optional)
          Swal.fire({
            title: 'Peringatan!',
            text: "Yakin ingin konfirmasi?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yakin!'
          }).then((result) => {
            if (result.isConfirmed) {
              // Send the ID via AJAX
              $.ajax({
                url: 'controller/konfirmasiPesan.php', // The PHP file handling the deletion
                type: 'POST',
                dataSrc: "",
                data: {
                  id: id
                }, // Send the ID in the data object
                success: function(response) {
                  // Handle the response from the server
                  Swal.fire(
                    'Yess!',
                    'Berhasil di Konfirmasi.',
                    'success'
                  ).then(() => {

                    fetchTableData();
                  });

                },
                error: function(xhr, status, error) {
                  // Handle errors
                  Swal.fire(
                    'Error!',
                    'An error occurred while deleting the message.',
                    'error'
                  );
                }
              });
            }
          });
        }

        // Fungsi untuk menghapus simbol 'Rp' dan pemisah ribuan
        function cleanRupiah(input) {
          return input.replace(/[^,\d]/g, '').replace(/,/g, '');
        }

        $('#simpanBooking').on('click', function() {

          var formData = new FormData($('#bayar-form')[0]); // Ambil data dari form


          // Ambil nilai nominal dari input dan bersihkan dari format Rupiah
          var nominalValue = $('#nominal').val();
          var subtotalValue = $('#subtotal').val();
          var diskonValue = $('#diskon').val();
          var namaValue = $('#nama').val();
          var hpValue = $('#no_hp').val();
          var cleanedDiskon = cleanRupiah(diskonValue);
          var cleanedNominal = cleanRupiah(nominalValue);
          var cleanedTotal = cleanRupiah(subtotalValue);
          var lapanganSelect = $('#lapanganSelect').val();

          if (!lapanganSelect) {
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Masukan lapangan terlebih dahulu.',
            });
            return; // Stop the execution if validation fails
          }

          if (!namaValue) {
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Masukan no hp terlebih dahulu.',
            });
            return; // Stop the execution if validation fails
          }


          if (!hpValue) {
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Masukan no hp terlebih dahulu.',
            });
            return; // Stop the execution if validation fails
          }

          // Additional validation for other form fields (example: name and phone number)
          if (!nominalValue) {
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Masukan nominal terlebih dahulu.',
            });
            return; // Stop the execution if validation fails
          }

          // Tambahkan nominal yang sudah dibersihkan ke dalam FormData
          formData.set('diskon_member', cleanedDiskon);
          formData.set('nominal', cleanedNominal);
          formData.set('subTotal', cleanedTotal);


          selectedBookings.forEach((booking, bookingIndex) => {
            formData.append(`selectedBookings[${bookingIndex}][tanggal]`, booking.tanggal);
            formData.append(`selectedBookings[${bookingIndex}][start]`, booking.start);
            formData.append(`selectedBookings[${bookingIndex}][end]`, booking.end);
          });


          // Log FormData contents
          function logFormData(formData) {
            for (let [key, value] of formData.entries()) {
              console.log(`${key}: ${value}`);
            }
          }

          logFormData(formData);


          $.ajax({
            url: 'saveBooking.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
              Swal.fire(
                'Yess!',
                'Berhasil menambahkan data!.',
                'success'
              ).then(() => {

                const lapanganId = $('#lapanganSelect').val();
                console.log(lapanganId);
                fetchAvailableTimes(lapanganId);
                $('#bayar-form')[0].reset();

                fetchTableData();
              });

            },
            error: function(xhr, status, error) {
              console.error('AJAX Error:', status, error);
              console.error('Response Text:', xhr.responseText);
            }
          });

        });

        $(document).on('click', '.btn-bayar', function() {
          const id = $(this).data('id');
          const total = $(this).data('total');
          const nominal = $(this).data('nominal');
          const sisa = total - nominal;

          Swal.fire({
            title: 'Pembayaran',
            html: `
            <div style="text-align: left;">
            <div class="row mb-3">
                <div class="col">
                    <label class="font-weight-bold mb-3">Total:</label>
                      <div class="input-group flex-nowrap mb-3">
                        <span class="input-group-text" id="addon-wrapping">Rp</span>
                        <input type="text" value="${formatRupiah(total)}" readonly class="form-control" style="background-color: #f8f9fa;">
                      </div>
                </div>
                <div class="col">
                    <label class="font-weight-bold mb-3">Sisa:</label>
                      <div class="input-group flex-nowrap mb-3">
                        <span class="input-group-text" id="addon-wrapping">IDR</span>
                        <input type="text" value="${formatRupiah(sisa)}" readonly class="form-control" style="background-color: #f8f9fa;">
                      </div>
                </div>
            </div>
            <label class="font-weight-bold mb-3">Bayar Sisa:</label>
            <div class="input-group flex-nowrap mb-3">
                <span class="input-group-text" id="addon-wrapping">IDR</span>
                <input type="text" class="form-control" id="bayarInput" name="bayarInput" required>
            </div>
        </div>
        `,
            willOpen: () => {
              // Menambahkan event listener setelah modal muncul
              document.getElementById('bayarInput').addEventListener('input', function(e) {
                // Ambil nilai input dan hapus semua karakter kecuali angka
                let value = e.target.value.replace(/[^0-9]/g, '');
                // Format nilai input ke dalam format IDR
                e.target.value = formatRupiah(value);
              });
            },
            focusConfirm: false,
            preConfirm: () => {
              const bayarValue = document.getElementById('bayarInput').value;
              const bayarClean = cleanRupiah(bayarValue);
              const bayar = bayarClean;
              if (isNaN(bayar) || bayar <= 0 || bayar > sisa) {
                Swal.showValidationMessage('Masukkan nominal yang valid!');
              } else {
                return {
                  bayar
                };
              }
            },
            backdrop: true,
            focusConfirm: false,
            customClass: {
              container: 'my-swal-container', // Custom class for further styling if needed
            },
          }).then((result) => {
            if (result.isConfirmed) {
              const bayar = result.value.bayar;
              // Update the nominal in the database
              $.ajax({
                url: 'controller/updatePayment.php', // Your PHP script to handle the payment
                type: 'POST',
                data: {
                  id: id,
                  bayar: bayar,
                },
                success: function(response) {

                  Swal.fire(
                    'Yess!',
                    'Berhasil bayar!.',
                    'success'
                  ).then(() => {
                    fetchTableData();
                  });
                },
                error: function(xhr, status, error) {
                  console.log('AJAX Error:', status, error);
                  Swal.fire('Error!', 'Terjadi kesalahan saat memproses pembayaran.', 'error');
                }
              });
            }
          });
        });

        $(document).on('click', '.btn-invoice', function() {
          var bookingId = $(this).data('id');

          // Redirect to the invoice generation script
          window.location.href = 'controller/invoice.php?id=' + bookingId;
        });



        function calculatePrice() {
          // Total price is calculated by summing up the price of each selected time slot
          const totalPrice = selectedBookings.reduce((total, slot) => total + slot.total || 0, 0);
          return totalPrice;
        }

      });
    </script>

    <script>
      const hamBurger = document.querySelector(".toggle-btn");

      hamBurger.addEventListener("click", function() {
        document.querySelector("#sidebar").classList.toggle("expand");
      });
    </script>


</body>

</html>