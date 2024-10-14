<?php
session_start();
require "functions.php";

$errorMessage = '';


if (isset($_SESSION["role"])) {
  $role = $_SESSION["role"];
  if ($role == "Admin") {
    header("Location: admin/home.php");
  } else {
    header("Location: index.php");
  }
}

if (isset($_POST["login"])) {
  $username = $_POST["username"];
  $password = $_POST["password"];

  $cariadmin = query("SELECT * FROM admin WHERE username = '$username' AND password = '$password'");

  if ($cariadmin) {
    // set session untuk type admin
    $_SESSION['username'] = $cariadmin[0]['nama'];
    $_SESSION['role'] = "Admin";
    $_SESSION['type'] = $cariadmin[0]['role']; // Tambahkan ini untuk menyimpan type admin

    header("Location: admin/home.php");
    exit();
  } else {
    $errorMessage = "Username atau Password salah"; // Set error message
  }
}



?>

<!DOCTYPE html>

<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <title>Login Sport Center</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <style>
    body {
      background-color: #508bfc;
      /* Background color for the page */
    }

    .card {
      border-radius: 1rem;
    }

    .form-control {
      border-radius: 0.5rem;
      /* Rounded input fields */
      background-color: #495057;
      /* Dark background for input fields */
      color: #ffffff;
      /* White text for inputs */
    }

    .form-control:focus {
      background-color: #495057;
      /* Dark background on focus */
      color: #ffffff;
      /* White text on focus */
      border-color: #007bff;
      /* Bootstrap primary border color */
    }

    .btn-primary {
      background-color: #007bff;
      /* Primary button color */
      border: none;
      border-radius: 0.5rem;
      /* Rounded button */
    }

    .btn-primary:hover {
      background-color: #0056b3;
      /* Darker on hover */
    }

    @media (max-width: 768px) {
      .card {
        height: 100%;
        margin: 0;
        /* Remove any margin */
      }
    }
  </style>
</head>

<body>
  <section class="vh-100" style="background-color: #508bfc;">
    <div class="container py-5 h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-12 col-md-8 col-lg-6 col-xl-5 ">
          <div class="card shadow-2-strong" style="border-radius: 1rem; background-color: #343a40;">
            <div class="card-body p-5 text-center text-white">

              <h3 class="mb-5">Login Admin</h3>

              <?php if ($errorMessage): ?>
                <div class="alert alert-warning" role="alert">
                  <?= $errorMessage; ?>
                </div>
              <?php endif; ?>

              <form method="POST">
                <div class="form-outline mb-4">
                  <input type="text" name="username" class="form-control form-control-lg" id="typeEmailX-2" required />
                  <label class="form-label" for="typeEmailX-2">Username</label>
                </div>

                <div class="form-outline mb-4">
                  <input type="password" name="password" class="form-control form-control-lg" id="typePasswordX-2" required />
                  <label class="form-label" for="typePasswordX-2">Password</label>
                </div>

                <button class="btn btn-primary btn-lg btn-block" name="login" type="submit">Login</button>
              </form>

            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.10.0/mdb.min.js"></script>
</body>

</html>