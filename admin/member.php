<?php
session_start();
require "../session.php";
require "../functions.php";

if ($role !== 'Admin') {
  header("location:/login.php");
}

$member = query("SELECT available_times.*, lapangan.name AS lapangan_name,
 normal_price.harga AS harga_normal,
 normal_price.diskon AS diskon_normal,
 normal_price.total AS total_normal,
 member_price.harga AS harga_member,
 member_price.diskon AS diskon_member,
 member_price.total AS total_member
FROM available_times
JOIN lapangan ON available_times.lapangan_id = lapangan.id
LEFT JOIN normal_price normal_price ON available_times.id = normal_price.times_id
LEFT JOIN member_price ON available_times.id = member_price.times_id;
");

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
  <!-- Sertakan CSS DataTables dan Buttons -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  <!-- DataTables CSS -->

  <style>
    .dataTables_filter {
      margin-bottom: 15px;
      /* Adjust the margin as needed */
    }
  </style>

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
        <h3 class="mt-4">Data Jadwal</h3>
        <hr>
        <div class="table-responsive">
          <table id="lapanganTable" class="table table-hover ">
            <thead class="table-inti">
              <tr>
                <th scope="col">Tanggal</th>
                <th scope="col">Lapangan</th>
                <th scope="col">Waktu</th>
                <th scope="col">Harga</th>
                <th scope="col">Diskon</th>
                <th scope="col">Total</th>
                <th scope="col">Harga Member</th>
                <th scope="col">Diskon Member</th>
                <th scope="col">Total Member</th>
                <th scope="col">Status </th>
              </tr>
            </thead>
            <form action="" method="post">
              <tbody>
                <?php foreach ($member as $row) : ?>
                  <tr>
                    <td><?= date('d-m-Y', strtotime($row["tanggal"])); ?></td> <!-- Format tanggal -->
                    <td><?= htmlspecialchars($row["lapangan_name"]); ?></td>
                    <td><?= htmlspecialchars($row["start_time"]); ?> - <?= htmlspecialchars($row["end_time"]); ?></td>
                    <td><?= 'Rp ' . number_format($row["harga_normal"], 0, ',', '.'); ?></td> <!-- Format harga -->
                    <td><?= htmlspecialchars($row["diskon_normal"]); ?> %</td>
                    <td><?= 'Rp ' . number_format($row["total_normal"], 0, ',', '.'); ?></td> <!-- Format harga -->
                    <td><?= 'Rp ' . number_format($row["harga_member"], 0, ',', '.'); ?></td> <!-- Format harga -->
                    <td><?= htmlspecialchars($row["diskon_member"]); ?> %</td>
                    <td><?= 'Rp ' . number_format($row["total_member"], 0, ',', '.'); ?></td> <!-- Format harga -->
                    <td><?= htmlspecialchars($row["status"]); ?></td>
                  </tr>
                <?php endforeach; ?>

              </tbody>
            </form>
          </table>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>


    <script>
      $(document).ready(function() {

        $('#lapanganTable').DataTable({
          "dom": 'Bfrtip', // Include Buttons in the DataTable
          "buttons": [{
            extend: 'excel',
            text: 'Download Excel',
            title: 'Daftar Jadwal',
            exportOptions: {
              columns: [0, 1, 2, 3, 4, 5, 6]
            },
          }],
          "pageLength": 7, // Set the number of entries per page to 5
          "paging": true, // Enables pagination
          "searching": true, // Enables search box
          "ordering": true, // Enables sorting
          "info": true, // Displays table information
          "lengthChange": false // Disables changing the number of records per page
        });
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