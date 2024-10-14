<?php
session_start();
require "../session.php";
require "../functions.php";

if ($role !== 'Admin') {
  header("location:../login.php");
}

$lapangan = query("SELECT * FROM lapangan");
$jadwal = query("SELECT * FROM available_times");

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>


  <title>Home</title>
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
      <div class="container">
        <!-- Konten -->
        <h3 class="mt-4">Data Lapangan</h3>
        <hr>
        <!-- Modal Tambah -->
        <div class="modal fade" id="tambahModal1" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="tambahModalLabel">Tambah Lapangan</h5>
              </div>
              <form id="lapanganForm">
                <div class="modal-body">
                  <!-- konten form modal -->
                  <div class="row justify-content-center align-items-center">
                    <div class="col">
                      <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Nama Lapangan</label>
                        <input type="text" name="name" class="form-control" id="name">
                      </div>
                    </div>
                    <div class="col">
                      <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Foto</label>
                        <input type="file" name="photo" class="form-control" id="photo">
                      </div>
                    </div>

                    <label for="total" class="form-label">Diskon member</label>
                    <div class="input-group mb-3">
                      <span class="input-group-text">IDR</span>
                      <input type="text" name="diskon_member" class="form-control" id="diskon_member">
                    </div>

                    <div class="mb-3">
                      <label for="description" class="form-label">Keterangan</label>
                      <textarea name="description" class="form-control" id="description" rows="4"></textarea>
                    </div>

                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-primary tambah-lpg" name="simpan" id="simpan">Simpan</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <!-- End Modal Tambah -->
        <div class="table-responsive mb-5">

          <table id="lapanganTable" class="table table-hover">
            <thead class="table-inti">
              <tr>
                <th scope="col">No</th>
                <th scope="col">Lapangan</th>
                <th scope="col">Keterangan</th>
                <th scope="col">Diskon</th>
                <th scope="col">Foto</th>
                <th scope="col">Jadwal</th>
                <th scope="col">Aksi</th>
              </tr>
            </thead>
            <tbody>

            </tbody>

          </table>

        </div>

        <?php foreach ($lapangan as $row): ?>

          <!-- Edit Modal -->
          <div class="modal fade" id="editModal<?= $row["id"]; ?>" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="tambahModalLabel">Edit Lapangan <?= $row["name"]; ?></h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editLapangan<?= $row['id']; ?>">
                  <input type="hidden" name="id" class="form-control" id="exampleInputPassword1" value="<?= $row["id"]; ?>">
                  <input type="hidden" name="fotoLama" class="form-control" id="exampleInputPassword1" value="<?= $row["photo"]; ?>">
                  <div class="modal-body">
                    <!-- konten form modal -->
                    <div class="row justify-content-center align-items-center">
                      <div class="mb-3 ">
                        <img src="../img/<?= $row["photo"]; ?>" alt="gambar lapangan" class="img-fluid">
                      </div>
                      <div class="col">
                        <div class="mb-3">
                          <label for="exampleInputPassword1" class="form-label">Nama Lapangan</label>
                          <input type="text" name="name" class="form-control" id="exampleInputPassword1" value="<?= $row["name"]; ?>">
                        </div>
                      </div>
                      <div class="col">
                        <div class="mb-3">
                          <label for="exampleInputPassword1" class="form-label">Foto : </label>
                          <input type="file" name="photo" class="form-control" id="exampleInputPassword1" value="../img/<?= $row["photo"]; ?>">
                        </div>
                      </div>

                      <label for="total" class="form-label">Diskon member</label>
                    <div class="input-group mb-3">
                      <span class="input-group-text">IDR</span>
                      <input type="text" name="diskon_edit" class="form-control" id="diskon_edit" value="<?= $row['diskon'];?>">
                    </div>

                      <div class="mb-3">
                        <label for="description" class="form-label">Keterangan</label>
                        <textarea name="description" class="form-control" id="description" rows="4"><?= $row['description']; ?></textarea>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">

                    <button type="button" class="btn btn-primary btn-edit" data-id="<?= $row['id']; ?>" name="edit" id="edit">Simpan</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <!-- End Modal Edit -->


          <!-- Modal jadwal -->
          <div class="modal modal-lg fade" id="jadwalModal<?= $row['id']; ?>" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="tambahModalLabel">Jadwal</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Example form with dynamic ID -->
                <form id="jadwalform<?= $row['id']; ?>">
                  <input type="hidden" name="id" id="id" value="<?= $row['id']; ?>">
                  <!-- Other form fields -->
                  <div class="modal-body">
                    <div class="row justify-content-center align-items-center">
                      <div class="mb-3 d-flex">
                        <input type="file" id="fileInput<?= $row['id']; ?>" name="filexls" />
                        <button id="uploadExcel<?= $row['id']; ?>" data-id="<?= $row["id"]; ?>" name="uploadExcel" type="button" class="btn btn-primary">Upload</button>

                      </div>
                      <div class="col">
                        <div class="mb-3">
                          <label for="dateInput<?= $row['id']; ?>" class="form-label">Tanggal</label>
                          <input type="date" name="date" class="form-control" id="dateInput<?= $row['id']; ?>">
                        </div>
                      </div>
                      <div class="col">
                        <div class="mb-3">
                          <label for="hargaInput<?= $row['id']; ?>" class="form-label">Harga</label>
                          <input type="number" name="harga" class="form-control" id="hargaInput<?= $row['id']; ?>">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col">
                        <div class="mb-3">
                          <label for="hargaInput<?= $row['id']; ?>" class="form-label">Diskon</label>
                          <input type="number" name="diskon" class="form-control" id="diskonInput<?= $row['id']; ?>">
                        </div>
                      </div>
                      <div class="col">
                        <div class="mb-3">
                          <label for="hargaInput<?= $row['id']; ?>" class="form-label">Total</label>
                          <input type="number" name="total" class="form-control" id="totalInput<?= $row['id']; ?>" readonly>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col">
                        <div class="mb-3">
                          <label for="startTimeInput<?= $row['id']; ?>" class="form-label">Mulai</label>
                          <input type="time" name="start_time" class="form-control" id="startTimeInput<?= $row['id']; ?>">
                        </div>
                      </div>
                      <div class="col">
                        <div class="mb-3">
                          <label for="endTimeInput<?= $row['id']; ?>" class="form-label">Habis</label>
                          <input type="time" name="end_time" class="form-control" id="endTimeInput<?= $row['id']; ?>">
                        </div>
                      </div>
                      <div class="d-flex justify-content-center">

                        <button type="button" class="btn btn-primary px-5 btn-tambah" data-id="<?= $row['id']; ?>">Tambah</button>
                      </div>
                    </div>
                    <div class="table-responsive">
                      <table id="jadwalTable<?= $row['id']; ?>" class="table table-striped table-hover mt-3">
                        <thead class="table-primary">
                          <tr>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Jam</th>
                            <th scope="col">Harga</th>
                            <th scope="col">Diskon</th>
                            <th scope="col">Total</th>
                            <th scope="col">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <!-- Dynamic rows will be appended here -->
                        </tbody>
                      </table>
                    </div>
                  </div>
                </form>

              </div>
            </div>
          </div>
          <!-- End Modal jadwal -->
        <?php endforeach; ?>


      </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Gambar Lapangan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <img id="modalImage" src="" alt="Gambar" class="img-fluid">
          </div>
        </div>
      </div>
    </div>


    <!-- DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <!-- DataTables JS -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <!-- DataTables Buttons JS -->
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <!-- DataTables Buttons - Bootstrap -->
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>




    <script>
      const hamBurger = document.querySelector(".toggle-btn");

      hamBurger.addEventListener("click", function() {
        document.querySelector("#sidebar").classList.toggle("expand");
      });
    </script>

    <script>
      $(document).ready(function() {
        fetchTableLapangan()

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
        document.getElementById('diskon_member').addEventListener('input', function(e) {
          // Hilangkan semua karakter selain angka sebelum memformat
          let value = e.target.value.replace(/[^0-9]/g, '');

          // Format nilai input ke dalam format IDR
          e.target.value = formatRupiah(value);
        });

        document.getElementById('diskon_edit').addEventListener('input', function(e) {
          // Hilangkan semua karakter selain angka sebelum memformat
          let value = e.target.value.replace(/[^0-9]/g, '');

          // Format nilai input ke dalam format IDR
          e.target.value = formatRupiah(value);
        });

        function fetchTableLapangan() {
          $.ajax({
            url: 'controller/getLapangan.php', // Your PHP script URL
            type: 'GET',
            dataType: 'json',
            success: function(response) {
              let tableContent = '';
              const tableBody = $('#lapanganTable tbody');
              const table = $('#lapanganTable');

              if (response.success) {
                const jadwal = response.data || [];

                jadwal.forEach((row, index) => {
                  const newRow = `
                    <tr>
                      <th scope="row">${index + 1}</th>
                      <td>${row.name}</td>
                      <td>${row.description}</td>
                      <td>${formatRupiah(row.diskon)}</td>
                      <td><img src="../img/${row.photo}" width="50" height="50" class="image-thumbnail"></td>
                      <td>
                        <button class="btn btn-primary lihat btn-sm" data-bs-toggle="modal" data-id="${row.id}" data-bs-target="#jadwalModal${row.id}">Lihat</button>
                      </td>
                      <td>
                        <button class="btn btn-inti btn-sm" data-bs-toggle="modal" data-bs-target="#editModal${row.id}">Edit</button>
<button class="btn btn-danger btn-sm hapus-button" data-id="${row.id}">Hapus</button>
                      </td>
                    </tr>
                    `;
                  tableContent += newRow;
                });

                // Destroy previous DataTable instance (if any)
                table.DataTable().clear().destroy();

                // Populate the available times table
                tableBody.html(tableContent);

                // Reinitialize DataTable with your settings
                table.DataTable({
                  paging: true,
                  pageLength: 4,
                  searching: true,
                  ordering: true,
                  info: false,
                  lengthChange: false,
                  autoWidth: false, // Make sure the table fills the container
                  columnDefs: [{
                      targets: 0,
                      width: "10%"
                    }, // Name column
                    {
                      targets: 1,
                      width: "20%"
                    }, // Name column

                  ],
                  dom: 'Bfrtip', // Include Buttons in the DataTable
                  buttons: [{
                    text: 'Tambah',
                    action: function(e, dt, node, config) {
                      $('#tambahModal1').modal('show');
                    },
                    className: 'btn btn-success' // Add CSS class for button
                  }]
                });
              }
            },
            error: function(xhr, status, error) {
              console.error('AJAX Error:', status, error);
            }
          });
        }

        $(document).on('click', '.btn-edit', function() {
          // Get the row id from the clicked button (assumed that the button contains the row id)
          var rowId = $(this).data('id'); // Assuming you have data-id on the button

          // Construct the form id dynamically
          var formId = '#editLapangan' + rowId;

          // Get the form data for the specific form
          var formData = new FormData($(formId)[0]);

          var diskonValue = $('#diskon_edit').val();
          var cleanedDiskon = cleanRupiah(diskonValue);

          formData.set('diskon_edit', cleanedDiskon);


          // To view the contents of formData
          for (var pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
          }

          $.ajax({
            url: 'editLpg.php', // PHP script to handle the form submission
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json', // Expect JSON response from server
            success: function(response) {
              console.log(response); //
              if (response.success) { // Check the 'success' property in the JSON response
                Swal.fire({
                  icon: 'success',
                  title: 'Berhasil',
                  text: 'Berhasil Diubah!',
                }).then(function() {
                  fetchTableLapangan()
                });
              } else {
                // Show error with specific message from the server
                Swal.fire({
                  icon: 'error',
                  title: 'Gagal',
                  text: response.message || 'Gagal Ditambahkan',
                });
              }
            },
            error: function(xhr, status, error) {
              console.error('AJAX Error:', status, error);
              console.error('Response Text:', xhr.responseText);
              alert('Failed to process request.');
            }
          });
        });

        // Fungsi untuk menghapus simbol 'Rp' dan pemisah ribuan
        function cleanRupiah(input) {
            return input.replace(/[^,\d]/g, '').replace(/,/g, '');
          }

        $(document).on('click', '.tambah-lpg', function() {
          var formData = new FormData($('#lapanganForm')[0]); // Get the form data

          // Ambil nilai nominal dari input dan bersihkan dari format Rupiah
          var diskonValue = $('#diskon_member').val();
          var cleanedDiskon = cleanRupiah(diskonValue);

          formData.set('diskon_member', cleanedDiskon);

          $.ajax({
            url: 'tambahLpg.php', // PHP script to handle the form submission
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json', // Expect JSON response from server
            success: function(response) {
              console.log(response); //
              if (response.success) { // Check the 'success' property in the JSON response
                Swal.fire({
                  icon: 'success',
                  title: 'Berhasil',
                  text: 'Berhasil Ditambahkan',
                }).then(function() {
                  fetchTableLapangan()
                });
              } else {
                // Show error with specific message from the server
                Swal.fire({
                  icon: 'error',
                  title: 'Gagal',
                  text: response.message || 'Gagal Ditambahkan',
                });
              }
            },
            error: function(xhr, status, error) {
              console.error('AJAX Error:', status, error);
              console.error('Response Text:', xhr.responseText);
              alert('Failed to process request.');
            }
          });
        });

        $(document).on('click', '.hapus-button', function(e) {
          e.preventDefault(); // Prevent the default anchor behavior

          var rowId = $(this).data('id'); // Get the ID of the item to delete
          console.log(rowId);
          // Show SweetAlert confirmation dialog
          Swal.fire({
            title: 'Peringatan!',
            text: "Yakin ingin menghapus?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya!'
          }).then((result) => {
            if (result.isConfirmed) {
              // If confirmed, send AJAX request to delete the item
              $.ajax({
                url: './controller/hapusLpg.php', // The PHP file handling the deletion
                type: 'POST', // Or 'GET' depending on your setup
                data: {
                  id: rowId
                }, // Pass the row ID to the server
                dataType: 'json', // Expect JSON response
                success: function(response) {

                  if (response.success) {
                    Swal.fire({
                      icon: 'success',
                      title: 'Success',
                      text: response.message
                    }).then(() => {
                      fetchTableLapangan();
                    });

                  } else {
                    Swal.fire(
                      'Error!',
                      response.message || 'Failed to delete the record.',
                      'error'
                    );
                  }
                },
                error: function(xhr, status, error) {
                  Swal.fire(
                    'Error!',
                    'An error occurred: ' + error,
                    'error'
                  );
                }
              });
            }
          });
        });


        $(document).on('click', '[id^=uploadExcel]', function(event) {
          event.preventDefault(); // Mencegah pengiriman form default
          // Mendapatkan file dari input
          const lapangan_id = $(this).data('id');
          // Get the ID of the clicked button
          var buttonId = $(this).attr('id');
          var formId = buttonId.replace('uploadExcel', 'jadwalform');
          var fileInputId = buttonId.replace('uploadExcel', 'fileInput');

          // Get the form, file input, and hidden input
          var form = $('#' + formId)[0];
          var fileInput = $('#' + fileInputId)[0];


          var file = fileInput.files[0]; // Get the selected file

          var formData = new FormData();
          formData.append('filexls', file);
          formData.append('lapangan_id', lapangan_id); // Add lapangan_id to formData


          $.ajax({
            url: 'controller/uploadExcel.php', // Ubah URL ini sesuai dengan lokasi script PHP Anda
            type: 'POST',
            data: formData,
            processData: false, // Prevent jQuery from automatically transforming the data into a query string
            contentType: false, // Set content type to false as FormData will set the correct content type
            dataType: 'json',
            success: function(response) {
              if (response.status === 'success') {
                Swal.fire({
                  icon: 'success',
                  title: 'Success',
                  text: response.message
                }).then(() => {
                  fetchTableData(lapangan_id);
                });
              } else if (response.status === 'warning') {
                Swal.fire({
                  icon: 'warning',
                  title: 'Warning',
                  html: response.message // Ensure HTML content is rendered
                });
              } else {
                Swal.fire({
                  icon: 'error',
                  title: 'Error',
                  text: response.message
                });
              }
            },
            error: function(xhr, status, error) {
              console.error('AJAX Error:', status, error);
              console.error('Response Text:', xhr.responseText);
              alert('Failed to process request.');
            }


          });
        });

        // Use event delegation with 'on'
        $(document).on('click', '.image-thumbnail', function() {
          var imageSrc = $(this).attr('src');
          $('#modalImage').attr('src', imageSrc);
          $('#imageModal').modal('show');
        });



        $(document).on('click', '.lihat', function() {
          // Get the id from the data attribute of the clicked button
          const lapangan_id = $(this).data('id');
          const formId = `#jadwalform${lapangan_id}`;
          console.log('Lihat:', lapangan_id, formId);

          // Fungsi untuk menghitung total harga setelah diskon
          function calculateTotal() {

            // Ambil elemen berdasarkan lapangan_id untuk menghitung total
            const harga = $(`${formId} #hargaInput${lapangan_id}`).val() || 0;
            const diskon = $(`${formId} #diskonInput${lapangan_id}`).val() || 0;

            // Kalkulasi total dengan diskon
            const total = harga - (harga * (diskon / 100));

            // Set hasil total ke input total
            $(`${formId} #totalInput${lapangan_id}`).val(total);
          }

          // Bind event 'input' pada input harga dan diskon untuk form yang sesuai
          $(`#hargaInput${lapangan_id}, #diskonInput${lapangan_id}`).on('input', calculateTotal);


          // Fetch and populate table data based on lapangan_id
          fetchTableData(lapangan_id);
        });

        // Handle the 'Tambah' button click
        $(document).on('click', '.btn-tambah', function() {
          const lapangan_id = $(this).data('id');
          console.log('Tambah clicked for lapangan_id:', lapangan_id);

          // Find the form and fields associated with the clicked button
          const formId = `#jadwalform${lapangan_id}`;
          const dateInput = $(`${formId} #dateInput${lapangan_id}`).val();
          const startTimeInput = $(`${formId} #startTimeInput${lapangan_id}`).val();
          const endTimeInput = $(`${formId} #endTimeInput${lapangan_id}`).val();
          const hargaInput = $(`${formId} #hargaInput${lapangan_id}`).val();
          const diskonInput = $(`${formId} #diskonInput${lapangan_id}`).val();
          const totalInput = $(`${formId} #totalInput${lapangan_id}`).val();

          const date = new Date(dateInput);
          const formattedDate = date.toISOString().split('T')[0];

          // Serialize form data
          const formData = {
            id: lapangan_id,
            date: formattedDate,
            harga: hargaInput, // Assuming harga is a number; no need to format
            start_time: startTimeInput,
            end_time: endTimeInput,
            diskon: diskonInput,
            total: totalInput // Assuming total is a number; no need to format
          };

          $.ajax({
            url: 'controller/getJadwal.php', // Replace with your server endpoint
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(formData),
            dataType: 'json',
            success: function(response) {
              console.log(response);
              console.log(response.message);

              if (response.success) {
                // Success alert using SweetAlert2
                Swal.fire({
                  title: 'Success!',
                  text: 'Data berhsil ditambahkan!',
                  icon: 'success',
                  confirmButtonText: 'OK'
                }).then((result) => {
                  if (result.isConfirmed) {
                    // Perform actions after confirming the success alert
                    fetchTableData(lapangan_id); // Reload table data for the current ID
                    $(formId)[0].reset(); // Reset form
                  }
                });
              } else {
                // Error alert using SweetAlert2
                Swal.fire({
                  title: 'Error!',
                  text: 'Failed to add data: ' + response.message,
                  icon: 'error',
                  confirmButtonText: 'OK'
                });
              }
            },

            error: function(xhr, status, error) {
              console.log('AJAX Error:', status, error);
              console.log('Response Text:', xhr.responseText);
              alert('An error occurred while processing the request.');
            }
          });
        });

        // Function to fetch and populate table data
        function fetchTableData(lapangan_id) {
          $.ajax({
            url: 'controller/getJadwal.php', // Your PHP script URL
            type: 'GET',
            data: {
              id: lapangan_id
            },
            dataType: 'json',
            success: function(response) {
              let tableContent = '';
              const tableBody = $(`#jadwalTable${lapangan_id} tbody`);

              const table = $(`#jadwalTable${lapangan_id}`);
              if (response.success) {
                const jadwal = response.data || [];

                jadwal.forEach(row => {
                  const newRow = `
              <tr>
                <td>${formatDate(row.tanggal)}</td>
                <td>${row.start_time} - ${row.end_time}</td>
                <td>${formatRupiah(row.harga)}</td>
                <td>${row.diskon} %</td>
                <td>${formatRupiah(row.total)}</td>
<td><button class="btn btn-danger btn-sm" data-lapangan="${row.lapangan_id}" data-id="${row.id}" onclick="removeRow(this)">Hapus</button></td>
              </tr>
            `;
                  tableContent += newRow;
                });

                // Destroy previous DataTable instance (if any)
                table.DataTable().clear().destroy();

                // Populate the available times table
                tableBody.html(tableContent);

                table.DataTable({
                  paging: true,
                  pageLength: 5,
                  searching: true,
                  ordering: true,
                  info: false,
                  lengthChange: false,
                  autoWidth: false, // Agar tabel memenuhi container

                  columnDefs: [{
                      targets: 1,
                      width: "25%"
                    }, // Kolom jam
                    {
                      targets: [0, 2, 3, 4, 5],
                      width: "15%"
                    } // Kolom lainnya, totalnya 55%
                  ],

                });

              }
            },
            error: function(xhr, status, error) {
              console.error('AJAX Error:', status, error);
            }
          });
        }

        window.removeRow = function(button) {
          event.preventDefault();
          // Get the id of the record to be removed
          const recordId = $(button).data('id');
          const lapangan_id = $(button).data('lapangan');

          // Show SweetAlert2 confirmation dialog
          Swal.fire({
            title: 'Peringatan!',
            text: "Yakin ingin menghapus?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya!'
          }).then((result) => {
            if (result.isConfirmed) {
              // If confirmed, perform the AJAX request to delete the record
              $.ajax({
                url: 'controller/deleteJadwal.php', // Replace with your PHP delete script URL
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                  id: recordId
                }), // Send the record ID to delete
                dataType: 'json',
                success: function(response) {
                  console.log(response);
                  if (response.success) {
                    // Show success message using SweetAlert2
                    Swal.fire(
                      'Yess!',
                      response.message,
                      'success'
                    ).then(() => {
                      fetchTableData(lapangan_id);
                      $(button).closest('tr').remove();

                    });
                  } else {
                    // Show error message using SweetAlert2
                    Swal.fire(
                      'Error!',
                      'Failed to delete record: ' + response.message,
                      'error'
                    );
                  }
                },
                error: function(xhr, status, error) {
                  console.log('AJAX Error:', status, error);
                  Swal.fire(
                    'Error!',
                    'An error occurred while processing the request.',
                    'error'
                  );
                }
              });
            }
          });
        };

      });
    </script>

</body>

</html>