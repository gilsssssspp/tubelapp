<!DOCTYPE html>
<html lang="id">

<!-- heading -->
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- import file css bootstrap -->
    <link rel="stylesheet" href="styles/bootstrap/css/bootstrap.min.css">
    
    <!-- import file css DataTables -->
    <link rel="stylesheet" href="styles/DataTables/datatables.min.css">
    <!-- CSS khusus halaman custom.css -->
    <link rel="stylesheet" href="css/custom.css">

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <!-- Font Inter (khusus navbar) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Title -->
<title>TUBEL-IBEL Online <?= htmlspecialchars(strip_tags($header ?? ''), ENT_QUOTES, 'UTF-8') ?></title>
    <!-- Letakkan script SweetAlert2 di bagian bawah sebelum penutupan </body> -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
@media print {

    @page {
        size: A4 portrait; /* boleh ganti landscape */
        margin: 15mm;
    }

    body {
        font-size: 17px; /* ⬅️ diperbesar */
        color: #000;
        background: #fff;
    }

    /* Hilangkan tombol print & elemen web */
    .btn,
    button,
    a[onclick],
    nav,
    footer {
        display: none !important;
    }

    .container,
    .card,
    .card-body {
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        box-shadow: none !important;
        border: none !important;
    }

    /* Judul */
    h3 {
        text-align: center;
        font-size: 25px; /* ⬅️ judul lebih besar */
        margin-bottom: 15px;
    }

    /* Tabel */
    table {
        width: 100% !important;
        border-collapse: collapse !important;
    }

    thead th {
        background: #f2f2f2 !important;
        font-weight: bold;
        text-align: center;
    }

    th, td {
        border: 1px solid #000 !important;
        padding: 8px !important; /* ⬅️ spasi diperbesar */
        font-size: 17px;        /* ⬅️ isi tabel lebih besar */
        vertical-align: middle;
    }


}
</style>

    
</head>
    <!-- body -->
    <body class="page-index2">
    
    <!-- header -->
    <nav class="navbar navbar-expand-lg custom-navbar px-5">
        <a class="navbar-brand" href="index2.php"><i class="fas fa-swatchbook"></i> TUBEL-IBEL Online</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
    </button>

        <!-- menu navigasi -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <?php
// fungsi pengecekan level akun login
if (isset($_SESSION['type']) && $_SESSION['type'] === 'admin') {
    echo '
        <li class="nav-item">
            <a class="nav-link" href="verifikasi.php">Verifikasi <span class="sr-only">(current)</span></a>
            <a class="nav-link" href="rekap.php">Rekapitulasi <span class="sr-only">(current)</span></a>
        </li>';
} else {
    echo '<li class="nav-item">
            <a class="nav-link" href="daftar_tubel.php">Pendaftaran Tugas/Izin Belajar <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="contact.php">Contact</a>
          </li>';
}
?>           
            </ul>

            <!-- tombol logout -->
            <form class="form-inline my-2 my-lg-0">
                 <a href="logout.php" class="btn-logout">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
            </form>
        </div>
    </nav>

    