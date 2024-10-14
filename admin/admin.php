<?php
session_start();
require "../functions.php";
require "../session.php";
if ($role !== 'Admin') {
  header("location:../login.php");
};

$admin = query("SELECT * FROM admin");


if (isset($_POST["simpan"])) {
  if (tambahAdmin($_POST) > 0) {
    echo "<script>
  alert('Berhasil DiTambahkan');
  window.location.href = 'admin.php';
</script>";
  } else {
    echo "<script>
  alert('Gagal DiTambahkan');
</script>";
  }
}

if (isset($_POST["edit"])) {
  if (editAdmin($_POST) > 0) {
    echo "<script>
  alert('Berhasil DiUbah');
  window.location.href = 'admin.php';
</script>";
  } else {
    echo "<script>
  alert('Gagal DiTambahkan');
</script>";
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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  <!-- Link CSS DataTables -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

  <!-- Link jQuery (pastikan jQuery sudah di-load sebelum DataTables) -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Link JS DataTables -->
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">

  <!-- DataTables Buttons JS -->
  <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
  <!-- DataTables Buttons - Bootstrap -->
  <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap5.min.js"></script>

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
        <h3 class="mt-4">Data Admin</h3>
        <hr>

        <!-- Modal Tambah -->
        <div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="tambahModalLabel">Tambah Admin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <form action="" method="post">
                <div class="modal-body">
                  <!-- konten form modal -->
                  <div class="row justify-content-center align-items-center">
                    <div class="col">
                      <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" id="exampleInputPassword1">
                      </div>
                      <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" id="exampleInputPassword1">
                      </div>
                    </div>
                    <div class="col">
                      <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" id="exampleInputPassword1">
                      </div>
                      <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">No Hp</label>
                        <input type="number" name="hp" class="form-control" id="exampleInputPassword1">
                      </div>
                    </div>
                    <div class="mb-3">
                      <label for="userType" class="form-label">Type</label>
                      <select name="type" class="form-select" id="userType" required>
                        <option value="" disabled selected>Pilih Type</option>
                        <option value="reguler">Reguler</option>
                        <option value="super">Super</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary" name="simpan" id="simpan">Simpan</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <!-- End Modal Tambah -->
        <div class="table-responsive">
          <table id="adminTable" class="table table-hover">
            <thead class="table-inti">
              <tr>
                <th scope="col">No</th>
                <th scope="col">Username</th>
                <th scope="col">Type</th>
                <th scope="col">Nama Lengkap</th>
                <th scope="col">No Hp</th>
                <th scope="col">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php $i = 1; ?>
              <?php foreach ($admin as $row) : ?>
                <tr>
                  <th scope="row"><?= $i++; ?></th>
                  <td><?= $row["username"]; ?></td>
                  <td><?= $row["role"]; ?></td>
                  <td><?= $row["nama"]; ?></td>
                  <td><?= $row["no_hp"]; ?> </td>
                  <td>
                    <?php if ($type === 'super') : ?>
                      <button class="btn btn-inti btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $row["id_user"]; ?>">Edit</button>
                      <a href="./controller/hapusAdmin.php?id=<?= $row["id_user"]; ?>" class="btn btn-danger btn-sm">Hapus</a>
                    <?php endif; ?>
                  </td>
                </tr>

                <!-- Edit Modal -->
                <div class="modal fade" id="editModal<?= $row["id_user"]; ?>" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="tambahModalLabel">Edit Admin <?= $row["nama"]; ?></h5>
                      </div>
                      <form action="" method="post">
                        <input type="hidden" name="id" class="form-control" id="exampleInputPassword1" value="<?= $row["id_user"]; ?>>">
                        <div class="modal-body">
                          <!-- konten form modal -->
                          <div class="row justify-content-center align-items-center">

                            <div class="col">
                              <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" id="exampleInputPassword1" value="<?= $row["username"]; ?>">
                              </div>
                              <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" id="exampleInputPassword1" value="<?= $row["password"]; ?>">
                              </div>
                            </div>
                            <div class="col">
                              <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Nama Lengkap</label>
                                <input type="nama" name="nama" class="form-control" id="exampleInputPassword1" value="<?= $row["nama"]; ?>">
                              </div>
                              <div class="mb-3">
                                <label for="typeSelect" class="form-label">Type</label>
                                <select name="type" class="form-select" id="typeSelect">
                                  <option value="super" <?= $row["role"] === 'super' ? 'selected' : ''; ?>>Super</option>
                                  <option value="reguler" <?= $row["role"] === 'reguler' ? 'selected' : ''; ?>>Reguler</option>
                                </select>
                              </div>

                            </div>
                            <div class="mb-3">
                              <label for="exampleInputPassword1" class="form-label">No Hp</label>
                              <input type="number" name="hp" class="form-control" id="exampleInputPassword1" value="<?= $row["no_hp"]; ?>">
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary" name="edit" id="edit">Simpan</button>
                          </div>
                      </form>
                    </div>
                  </div>
                </div>
                <!-- End Modal Tambah -->
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <script>
      const hamBurger = document.querySelector(".toggle-btn");

      hamBurger.addEventListener("click", function() {
        document.querySelector("#sidebar").classList.toggle("expand");
      });

      $(document).ready(function() {
        var buttons = [];

        <?php if ($type === 'super') : ?>
          buttons.push({
            text: 'Tambah',
            action: function(e, dt, node, config) {
              $('#tambahModal').modal('show');
            },
            className: 'btn btn-success' // Add CSS class for button
          });
        <?php endif; ?>
        $('#adminTable').DataTable({
          paging: true,
          searching: true,
          ordering: true,
          pageLength: 5,
          lengthChange: false,
          dom: 'Bfrtip', // Include Buttons in the DataTable
          buttons: buttons // Use the buttons array

        });
      });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>

</html>