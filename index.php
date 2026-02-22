<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Aplikasi Pengajuan Tugas/Izin Belajar</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Bootstrap Icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Google fonts-->
        
        <!-- SimpleLightbox plugin CSS-->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.css" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <link href="css/styles.css" rel="stylesheet" />
        <link href="css/custom.css" rel="stylesheet">
       </head>
<!-- =============================== -->
<!--  HALAMAN INDEX / Landing page   -->
<!-- =============================== -->
    <body id="page-top" class="page-landing">
<!-- Navigation-->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav">
            <div class="container px-0">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive">
                <span class="navbar-toggler-icon"></span>
                </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ms-auto my-2 my-lg-0">
                   <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle nav-cta" href="#" id="panduanDropdown" role="button" 
           data-bs-toggle="dropdown" aria-expanded="false">
            Panduan
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="panduanDropdown">
            <li><a class="dropdown-item" href="dokumen/manual_user.pdf" target="_blank">Manual Book User</a></li>
            <li><a class="dropdown-item" href="dokumen/manual_admin.pdf" target="_blank">Manual Book Admin</a></li>
        </ul>
                    <li class="nav-item"><a class="nav-link nav-cta" href="dokumen/Kuesioner_UAT.docx" target="_blank">Kuisioner</a></li>
		    <li class="nav-item"><a class="nav-link nav-cta" href="login.php">Login</a></li>

                    <li class="nav-item"><a class="nav-link" href="#kontak">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>

 <!-- Masthead-->
<header class="masthead">
    <div class="container-fluid px-5 h-100">
        <div class="row h-100 align-items-start justify-content-center text-center">
        <div class="col-lg-12">
        <div class="logo-wrapper mb-3">
            <img src="assets/img/logo-kemendagri.png"
             alt="Logo Kementerian Dalam Negeri"
             class="logo-masthead">
        </div>
<!-- PANEL JUDUL -->
    <div class="judul-box">
        <h1 class="judul-utama">
            Selamat Datang<br>
            di Aplikasi Pengajuan Tugas Belajar & Izin Belajar<br>
            Kementerian Dalam Negeri
            </h1>
            </div>
        </div>
      </div>
    </div>
</header>
    
<!-- =============================== -->
<!--            INFORMASI            -->
<!-- =============================== -->
<section class="informasi-section" id="informasi">
    <div class="container-fluid px-0">
        <div class="row gx-4 gx-lg-5 justify-content-center">

        <!-- Card 1 -->
            <div class="col-lg-6 col-md-12">
                <div class="info-card p-4" data-bs-toggle="modal" data-bs-target="#alurModal">
                    <img src="assets/img/informasi_alur_pendaftaran.svg" class="info-img" alt="Alur Pendaftaran">
                    <h3 class="h4 mt-3">Alur Pendaftaran</h3>
                    <p class="text-muted">
                        Pengajuan dilakukan melalui aplikasi dengan mengisi formulir dan mengunggah dokumen persyaratan, kemudian diverifikasi sesuai ketentuan hingga penerbitan persetujuan.
                    </p>
                </div>
            </div>

        <!-- Card 2 -->
            <div class="col-lg-6 col-md-12">
                <div class="info-card p-4" data-bs-toggle="modal" data-bs-target="#layananModal">
                    <img src="assets/img/informasi_layanan.svg" class="info-img" alt="Layanan">
                    <h3 class="h4 mt-3">Layanan</h3>
                    <p class="text-muted">
                        Pelayanan pengajuan Tugas Belajar dan Izin Belajar melalui aplikasi dengan jangka waktu pelayanan paling lama 3 hari kerja sejak permohonan disubmit dan diverifikasi sesuai persyaratan.
                    </p>
                </div>
            </div>
</section>

<!-- =============================== -->
<!--        About / Kontak           -->
<!-- =============================== -->
        <section class="kontak-section" id="kontak">
            <h2 class="kontak-title">Kontak</h2>
            <div class="kontak-wrapper">
                <p class="kontak-row">
                    <span class="label"><i class="fas fa-user"></i> Nama:</span>
                    <span class="value">Biro Sumber Daya Manusia</span>
                </p>
                <p class="kontak-row">
                    <span class="label">
                        <i class="fas fa-phone"></i> Telepon:
                    </span>
                    <span class="value">
                        <a href="tel:+622112345678">021 - 12345678</a>
                    </span>
                </p>
                <p class="kontak-row">
                    <span class="label">
                        <i class="fas fa-envelope"></i> Email:</span>
                    <span class="value">
                        <a href="mailto:birosdm@kemendagri.go.id">
                            birosdm@kemendagri.go.id
                        </a>
                    </span>
                </p>
            </div>
        </section>

<!-- =============================== -->
<!--      MODAL FULLSCREEN IMAGE    -->
<!-- =============================== -->

        <!-- Modal Alur -->
        <div class="modal fade" id="alurModal" tabindex="-1">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content position-relative">
                    <!-- Tombol Close -->
                    <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3"
                     data-bs-dismiss="modal"></button>
                    <!-- Klik gambar = close -->
                    <img src="assets/img/informasi_alur_pendaftaran.svg" class="w-100" style="cursor:pointer;" data-bs-dismiss="modal" alt="">
                </div>
            </div>
        </div>
        <!-- Modal Layanan -->
        <div class="modal fade" id="layananModal" tabindex="-1">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content position-relative">
                    <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3"
                            data-bs-dismiss="modal"></button>
                    <img src="assets/img/informasi_layanan.svg" class="w-100" style="cursor:pointer;" data-bs-dismiss="modal" alt="">
                </div>
            </div>
        </div>
        <!-- Modal Manfaat -->
        <div class="modal fade" id="manfaatModal" tabindex="-1">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content position-relative">
                    <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3"
                    data-bs-dismiss="modal"></button>
                    <img src="assets/img/informasi_manfaat.svg" class="w-100" style="cursor:pointer;" data-bs-dismiss="modal" alt="">
                </div>
            </div>
        </div>

                        <!-- * * * * * * * * * * * * * * *-->
                        <!-- * * SB Forms Contact Form * *-->
                        <!-- * * * * * * * * * * * * * * *-->
                        <!-- This form is pre-integrated with SB Forms.-->
                        <!-- To make this form functional, sign up at-->
                        <!-- https://startbootstrap.com/solution/contact-forms-->
                        <!-- to get an API token!-->
                        
        <!-- Footer-->
        <footer class="bg-light py-5">
            <div class="container px-4 px-lg-5"><div class="small text-center text-muted">Biro Sumber Daya Manusia Sekretariat Jenderal 
                <br> Kementerian Dalam Negeri</div></div>
        </footer>
        
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- SimpleLightbox plugin JS-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
        <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
        <!-- * *                               SB Forms JS                               * *-->
        <!-- * * Activate your form at https://startbootstrap.com/solution/contact-forms * *-->
        <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
        <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>
    </body>
</html>