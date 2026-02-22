<?php
session_start();
include_once 'koneksi.php';

// Cek login
if (!isset($_SESSION['nip'])) {
    echo "<script>
        alert('Silahkan Login Terlebih Dahulu!');
        window.location = 'login.php';
    </script>";
    exit;
}

// Panggil header
$header = "";
include_once 'header.php';
?>

<!-- =============================== -->
<!--  HALAMAN INDEX2/internal page   -->
<!-- =============================== -->
    <div class="page-index2">
        <div class="container my-4">
        <!-- Sapaan -->
            <h3 class="text-center"> Halo, 
            <?php echo htmlspecialchars($_SESSION['nama']); ?>
            </h3> <hr class="mx-2">

        <!-- Card utama -->
        <div class="card shadow mb-4">

        <!-- Header card -->
        <div class="card-header py-3 text-center">
            <h4 class="m-0 text-dark font-weight-bold"> LAYANAN PENGAJUAN TUGAS / IZIN BELAJAR ONLINE
            </h4>
        </div>

        <!-- Body card -->
        <div class="card-body p-0"> 
            <img src="images/alur1.svg"alt="Alur Pendaftaran" 
            class="img-fluid w-100 alur-img">       
        </div>
        </div>
      </div>
    </div>

<?php
// Panggil footer
include_once 'footer.php';
?>
