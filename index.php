<?php
session_start();
require "functions.php";

$lapangan = query("SELECT * FROM lapangan");
$jml_lapangan = query("SELECT COUNT(id) AS jml_lapangan FROM lapangan")[0];

function truncateDescription($text, $maxWords = 50)
{
    $words = explode(' ', $text); // Memisahkan teks menjadi array kata
    if (count($words) > $maxWords) {
        // Jika jumlah kata lebih dari batas, ambil hanya 50 kata dan tambahkan '...'
        $text = implode(' ', array_slice($words, 0, $maxWords)) . '...';
    }
    return $text;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Basecamp Sport Center</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">


    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="assets/css/main.css" rel="stylesheet">
    <style>
        .card {
            border: none;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2) !important; /* Stronger shadow with more blur */
        }

        .card img {
            height: 180px;
            object-fit: cover;
            transition: opacity 0.3s ease;
        }

        .card:hover img {
            opacity: 0.9;
        }

        .card-body {
            text-align: center;
            padding: 15px;
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: bold;
            color: #333;
        }

        .card-text {
            font-size: 0.95rem;
            color: #777;
            margin-top: 10px;
        }

        @media (max-width: 768px) {
            .card img {
                height: 150px;
            }

            .card-title {
                font-size: 1rem;
            }

            .card-text {
                font-size: 0.85rem;
            }
        }
    </style>

</head>

<body class="index-page">

    <header id="header" class="header d-flex align-items-center sticky-top">
        <div class="container-fluid container-xl position-relative d-flex align-items-center">

            <a href="index.html" class="logo d-flex align-items-center me-auto">
                <!-- Uncomment the line below if you also wish to use an image logo -->
                <img src="assets/img/logo.png" alt="">
                <h3 class="text-dark mx-3">Basecamp</h3>
            </a>

            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="index.php" class="active">Beranda<br></a></li>
                    <li><a href="lapangan.php">Lapangan</a></li>
                    <li><a href="kontak.php">Kontak</a></li>
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>
        </div>
    </header>

    <main class="main">

        <!-- Hero Section -->
        <section id="hero" class="hero section dark-background">

            <img src="assets/img/hero-bg.jpg" alt="" data-aos="fade-in">

            <div class="container">
                <h2 data-aos="fade-up" data-aos-delay="100">Sehatkan Dirimu Dengan<br>Berolahraga di <span class="text-"> Basecamp </span> <br> Sport Center</h2>
                <div class="d-flex mt-4" data-aos="fade-up" data-aos-delay="300">
                    <a href="lapangan.php" class="btn-get-started">Booking Sekarang <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>

        </section><!-- /Hero Section -->

        <!-- Courses Section -->
        <section id="courses" class="courses section">

            <!-- Section Title -->
            <div class="container d-flex justify-content-between align-items-center" data-aos="fade-up">
                <div class="left section-title">
                    <h2>Lapangan</h2>
                    <p>Lapangan Terbaik</p>
                </div>
            </div><!-- End Section Title -->

            <div class="container">

                <div class="row gy-3 gx-2">
                    <?php foreach ($lapangan as $row) : ?>
                        <div class="col-6 col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="100">
                            <a href="detail.php?id=<?= $row["id"] ?>">
                                <div class="card shadow-sm" style="width: 100%;">
                                    <img src="img/<?= $row["photo"]; ?>" class="card-img-top" alt="...">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= $row["name"]; ?></h5>
                                        <p class="card-text"><?= truncateDescription($row["description"]); ?></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>

            </div>

        </section><!-- /Courses Section -->

        <!-- About Section -->
        <section id="about" class="about section">

            <div class="container">

                <div class="row gy-4">

                    <div class="col-lg-6 order-1 order-lg-2 my-auto" data-aos="fade-up" data-aos-delay="100">
                        <img src="assets/img/about.jpg" class="img-fluid" alt="">
                    </div>

                    <div class="col-lg-6 order-2 order-lg-1 content" data-aos="fade-up" data-aos-delay="200">
                        <h3>Basecamp Sport Center Hadir Untuk Anda</h3>
                        <p class="fst-italic">
                            Temukan pengalaman olahraga yang luar biasa dengan fasilitas premium kami.
                        </p>
                        <ul>
                            <li><i class="bi bi-check-circle"></i> <span>Fasilitas olahraga terbaru dan terlengkap untuk latihan yang lebih efektif.</span></li>
                            <li><i class="bi bi-check-circle"></i> <span>Pelatih profesional yang siap membantu Anda mencapai tujuan dengan strategi yang terbukti.</span></li>
                            <li><i class="bi bi-check-circle"></i> <span>Lingkungan yang mendukung dan nyaman, menciptakan suasana positif untuk setiap latihan.</span></li>
                        </ul>
                        <!-- <a href="#" class="read-more "><span>Pelajari Lebih Lanjut</span><i class="bi bi-arrow-right"></i></a> -->
                    </div>


                </div>

            </div>

        </section><!-- /About Section -->

        <!-- Counts Section -->
        <section id="counts" class="section counts light-background">

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row gy-4">

                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="shadow rounded stats-item text-center w-100 h-100">
                            <span data-purecounter-start="0" data-purecounter-end="1232" data-purecounter-duration="1" class="purecounter"></span>
                            <p>Pelanggan</p>
                        </div>
                    </div><!-- End Stats Item -->

                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="shadow rounded stats-item text-center w-100 h-100">
                            <span data-purecounter-start="0" data-purecounter-end="<?= $jml_lapangan['jml_lapangan']; ?>" data-purecounter-duration="1" class="purecounter"></span>
                            <p>Lapangan</p>
                        </div>
                    </div><!-- End Stats Item -->

                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="shadow rounded stats-item text-center w-100 h-100">
                            <span data-purecounter-start="0" data-purecounter-end="42" data-purecounter-duration="1" class="purecounter"></span>
                            <p>Tournament</p>
                        </div>
                    </div><!-- End Stats Item -->

                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="shadow rounded stats-item text-center w-100 h-100">
                            <span data-purecounter-start="0" data-purecounter-end="24" data-purecounter-duration="1" class="purecounter"></span>
                            <p>Membership</p>
                        </div>
                    </div><!-- End Stats Item -->

                </div>

            </div>

        </section><!-- /Counts Section -->


    </main>

    <footer id="footer" class="footer position-relative light-background">

        <div class="container footer-top">
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

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <div id="preloader"></div>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

    <!-- Main JS File -->
    <script src="assets/js/main.js"></script>

</body>

</html>