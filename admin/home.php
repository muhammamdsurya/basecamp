<?php
session_start();
require "../functions.php";
require "../session.php";
if ($role !== 'Admin') {
  header("location:../login.php");
}

$lapangan = query("SELECT COUNT(id) AS jml_lapangan FROM lapangan")[0];
$user = query("SELECT COUNT(id_user) AS jml_user FROM admin")[0];
$jml_sales = query("SELECT SUM(total) AS total_sales FROM bookings WHERE DATE(booking_date) = CURDATE()")[0];
$sales = query("
    SELECT DATE(booking_date) AS tanggal, SUM(total) AS total
FROM bookings
WHERE booking_date BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE()
GROUP BY tanggal
ORDER BY tanggal;

");

$labels = [];
$data = [];

if (!empty($sales)) {
  foreach ($sales as $row) {
    $labels[] = date('d-m-Y', strtotime($row['tanggal'])); // Tanggal untuk sumbu X
    $data[] = (int) $row['total']; // Total untuk sumbu Y
  }
}

$jml_pesan = query("SELECT COUNT(id) AS total_sewa FROM bookings WHERE DATE(booking_date) = CURDATE()")[0];
$pesanan = query("SELECT DATE(booking_date) AS tanggal, COUNT(id) AS jml_sewa
FROM bookings
WHERE booking_date BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE()
GROUP BY tanggal
ORDER BY tanggal");

$labels1 = [];
$data1 = [];

if (!empty($pesanan)) {
  foreach ($pesanan as $row) {
    $labels1[] = date('d-m-Y', strtotime($row['tanggal'])); // Tanggal untuk sumbu X
    $data1[] = (int) $row['jml_sewa']; // Total untuk sumbu Y
  }
}

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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

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

      <h3 class="container mt-4">Beranda</h3>
      <hr>
      <div class="container mb-5">
        <div class="row">
          <div class="col-6 col-lg-2">
            <div class="card bg-c-blue order-card">
              <div class="card-body">
                <h5 class="card-title">Admin</h5>
                <h5 class="card-text text-right">
                  <i class="fa fa-user me-3 fs-6"></i>
                  <span><?= $user["jml_user"]; ?></span>
                </h5>
              </div>
            </div>
          </div>

          <div class="col-6 col-lg-2">
            <div class="card bg-c-yellow order-card">
              <div class="card-body">
                <h5 class="card-title">Lapangan</h5>
                <h5 class="card-text text-right">
                  <i class="fa fa-dumbbell me-3 fs-6"></i>
                  <span><?= $lapangan["jml_lapangan"]; ?></span>
                </h5>
              </div>
            </div>
          </div>

          <div class="col-6 col-lg-4">
            <div class="card bg-c-green order-card">
              <div class="card-body">
                <h5 class="card-title"> Pesanan</h5>
                <h5 class="card-text text-right">
                  <i class="fa fa-shopping-cart me-3 fs-6"></i>
                  <span><?= $jml_pesan["total_sewa"]; ?></span>
                </h5>
              </div>
            </div>
          </div>

          <div class="col-6 col-lg-4">
            <div class="card bg-c-pink order-card">
              <div class="card-body">
                <h5 class="card-title">Penjualan</h5>
                <h5 class="card-text text-right">
                  <i class="fa fa-money-bills me-3 fs-6"></i>
                  <span class="text-nowrap">Rp <?= number_format($jml_sales["total_sales"], 0, ',', '.'); ?></span>
                </h5>

              </div>
            </div>
          </div>
        </div>

        <h3 class="mt-3">Statistik</h3>
        <hr>
        <div class="row">
          <div class="col-md-6">
            <div class="card mb-3">
              <div class="card-header">
                <h5>Grafik Penjualan</h5>
              </div>
              <div class="card-body">
                <form id="dateRangeForm">
                  <label for="dateRange">Tanggal:</label>
                  <div class="input-daterange input-group" id="datepicker">
                    <input type="date" class="form-control" name="start" required />
                    <div class="input-group-prepend">
                      <span class="input-group-text"> - </span>
                    </div>
                    <input type="date" class="form-control" name="end" required />
                    <button type="submit" class="btn btn-primary">Submit</button>
                  </div>
                </form>
                <canvas id="salesChart" width="400" height="200"></canvas>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card mb-3">
              <div class="card-header">
                <h5>Grafik Jumlah Sewa</h5>
              </div>
              <div class="card-body">
                <form id="dateRangeBook">
                  <label for="dateRange">Tanggal:</label>
                  <div class="input-daterange input-group" id="datepicker">
                    <input type="date" class="form-control" name="startBook" required />
                    <div class="input-group-prepend">
                      <span class="input-group-text"> - </span>
                    </div>
                    <input type="date" class="form-control" name="endBook" required />
                    <button type="submit" class="btn btn-primary">Submit</button>
                  </div>
                </form>
                <canvas id="bookChart" width="400" height="200"></canvas>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
      let salesChartInstance; // Global variable to hold the chart instance
      let salesChartCanvas = document.getElementById('salesChart');
      let bookChartInstance; // Global variable to hold the chart instance
      let bookChartCanvas = document.getElementById('bookChart');

      const today = new Date();
      const lastWeek = new Date();
      today.setDate(today.getDate() + 1); // Calculate 7 days ago
      lastWeek.setDate(today.getDate() - 7); // Calculate 7 days ago

      const startDate = lastWeek.toISOString().split('T')[0]; // Format as YYYY-MM-DD
      const endDate = today.toISOString().split('T')[0]; // Format as YYYY-MM-DD

      fetchSalesData(startDate, endDate); // Fetch data for the last 7 days
      fetchBookData(startDate, endDate); // Fetch data for the last 7 days
      // Function to calculate date range (last 7 days)

      // Function to fetch sales data for a date range
      function fetchSalesData(startDate, endDate) {
        $.ajax({
          url: 'controller/getSalesData.php',
          type: 'GET',
          data: {
            startDate: startDate,
            endDate: endDate
          },
          dataType: 'json',
          success: function(response) {
            let labels = [];
            let total = [];

            // Extract 'tanggal' and 'total' from the response data
            response.data.forEach(function(item) {
              labels.push(item.tanggal); // Add 'tanggal' to labels array
              total.push(item.total); // Add 'total' to total array
            });

            // Update the chart with new data
            salesChart(labels, total);
          },
          error: function(xhr, status, error) {
            console.error('Error fetching sales data:', error);
          }
        });
      }

      function fetchBookData(startDate, endDate) {
        $.ajax({
          url: 'controller/getBookingData.php',
          type: 'GET',
          data: {
            startDate: startDate,
            endDate: endDate
          },
          dataType: 'json',
          success: function(response) {
            console.log(response);
            let labels = [];
            let total = [];

            // Extract 'tanggal' and 'total' from the response data
            response.data.forEach(function(item) {
              labels.push(item.tanggal); // Add 'tanggal' to labels array
              total.push(item.jml_sewa); // Add 'total' to total array
            });

            // Update the chart with new data
            bookChart(labels, total);
          },
          error: function(xhr, status, error) {
            console.error('Error fetching sales data:', error);
          }
        });
      }


      function salesChart(labels, total) {
        // Destroy the previous chart instance if it exists
        // Destroy the existing chart if it exists
        if (salesChartInstance) {
          salesChartInstance.destroy();
        }
        salesChartInstance = new Chart(salesChartCanvas.getContext('2d'), {
          type: 'bar', // Jenis grafik (line, bar, pie, dll.)
          data: {
            labels: labels, // Tanggal
            datasets: [{
              label: 'Total Penjualan',
              data: total, // Total penjualan
              borderColor: 'rgba(75, 192, 192, 1)',
              backgroundColor: 'rgba(75, 192, 192, 0.2)',
              borderWidth: 1
            }]
          },
          options: {
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        });
      }
      $('#dateRangeForm').on('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        const startDate = $('input[name="start"]').val();
        const endDate = $('input[name="end"]').val();

        // Validate the date range
        if (!startDate || !endDate) {
          alert('Please select both start and end dates.');
          return;
        }

        // Send AJAX request to fetch data
        $.ajax({
          url: 'controller/getSalesData.php',
          type: 'GET',
          data: {
            startDate: startDate,
            endDate: endDate
          },
          dataType: 'json',
          success: function(response) {
            // Initialize arrays to hold labels (dates) and total sales data
            let labels = [];
            let total = [];

            // Loop through the response data array to extract 'tanggal' and 'total'
            response.data.forEach(function(item) {
              labels.push(item.tanggal); // Push the 'tanggal' to the labels array
              total.push(item.total); // Push the 'total' to the total array
            });

            // Call the function to update the chart with the extracted data
            salesChart(labels, total); // Function to update the chart with the new data
            // updateSalesChart(response.data); // Function to update the chart with response data
          },
          error: function(xhr, status, error) {
            console.error('Error fetching sales data:', error);
          }
        });
      });

      $('#dateRangeBook').on('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        const startDate = $('input[name="startBook"]').val();
        const endDate = $('input[name="endBook"]').val();

        // Validate the date range
        if (!startDate || !endDate) {
          alert('Pilih kedua tanggal terlebih dahulu');
          return;
        }

        // Send AJAX request to fetch data
        $.ajax({
          url: 'controller/getBookingData.php',
          type: 'GET',
          data: {
            startDate: startDate,
            endDate: endDate
          },
          dataType: 'json',
          success: function(response) {
            // Initialize arrays to hold labels (dates) and total sales data
            let labels = [];
            let total = [];

            // Loop through the response data array to extract 'tanggal' and 'total'
            response.data.forEach(function(item) {
              labels.push(item.tanggal); // Push the 'tanggal' to the labels array
              total.push(item.jml_sewa); // Push the 'total' to the total array
            });

            // Call the function to update the chart with the extracted data
            bookChart(labels, total); // Function to update the chart with the new data
            // updateSalesChart(response.data); // Function to update the chart with response data
          },
          error: function(xhr, status, error) {
            console.error('Error fetching sales data:', error);
          }
        });
      });


      function bookChart(labels, total) {
        if (bookChartInstance) {
          bookChartInstance.destroy();
        }
        bookChartInstance = new Chart(bookChartCanvas.getContext('2d'), {
          type: 'bar', // Jenis grafik (line, bar, pie, dll.)
          data: {
            labels: labels, // Tanggal
            datasets: [{
              label: 'Total Booking',
              data: total, // Total penjualan
              borderColor: 'rgba(54, 162, 235, 1)', // Blue border color
              backgroundColor: 'rgba(54, 162, 235, 0.2)', // Light blue background color
              borderWidth: 1
            }]
          },
          options: {
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        });
      }
    </script>

    <script>
      const hamBurger = document.querySelector(".toggle-btn");

      hamBurger.addEventListener("click", function() {
        document.querySelector("#sidebar").classList.toggle("expand");
      });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

</body>

</html>