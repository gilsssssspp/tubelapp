<?php
session_start();
include '../koneksi.php';

if (!isset($_POST['simpan'])) {
    // kalau file ini diakses langsung, lempar balik
    header("Location: ../pendaftaran.php?iddaftar=0");
    exit;
}

// Mengambil data dari form
$iddaftar = $_GET['iddaftar'] ?? 0;
$iddaftar = (int)$iddaftar;

$alasantms = $_GET['alasantms'] ?? '';
$alasantms = trim($alasantms);

// Ambil input
$tubelibel     = $_POST['tubelibel'] ?? '';
$nip           = $_SESSION['nip'] ?? '';
$kampus        = $_POST['kampus'] ?? ''; // kampus wajib (TB & IB)
$prodi         = $_POST['prodi'] ?? '';
$rencanakuliah = $_POST['rencana_kuliah'] ?? '';
$akhir_studi   = $_POST['akhir_studi'] ?? '';
$status        = "Pendaftaran";

// normalisasi
$tubelibel     = strtolower(trim($tubelibel));
$nip           = trim($nip);
$kampus        = trim($kampus);
$prodi         = trim($prodi);
$rencanakuliah = trim($rencanakuliah);
$akhir_studi   = trim($akhir_studi);

// Cek validasi dan simpan data
// Tidak kosongkan akhir_studi untuk IBEL, karena sekarang wajib untuk keduanya

// Cek error input
$err = [];
if ($tubelibel === '') $err[] = "Jenis Pengajuan belum dipilih.";
if ($kampus === '') $err[] = "Perguruan Tinggi belum dipilih."; // [DIUBAH]
if ($prodi === '0' || $prodi === '' || $prodi === 'Pilih Program Studi') $err[] = "Program Studi belum dipilih.";
if ($rencanakuliah === '') $err[] = "Rencana kuliah belum diisi.";

// Akhir studi wajib untuk TUBEL dan IBEL
if ($akhir_studi === '') {
    $err[] = "Akhir studi wajib diisi.";
}

// Jika ada error → STOP proses
if (!empty($err)) {
    $msg = implode("\n", $err);
    $_SESSION['pendaftaran_error'] = $msg;
    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '../pendaftaran.php?iddaftar=0'));
    exit;
}
// =========================================
// VALIDASI: akhir_studi harus lebih besar dari rencana_kuliah
// =========================================
if ($rencanakuliah !== '' && $akhir_studi !== '') {

    $tglRencana = strtotime($rencanakuliah);
    $tglAkhir   = strtotime($akhir_studi);

    if ($tglAkhir <= $tglRencana) {
        $_SESSION['pendaftaran_error'] = 
            "Tanggal akhir studi harus lebih besar dari rencana kuliah.";
        header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '../pendaftaran.php?iddaftar=0'));
        exit;
    }
}


/* =========================================
   Validasi jenjang prodi (backend safeguard)
   Catatan: Anda pakai $tingkat_pendidikan, tapi
   di kode Anda tidak pernah di-set.
   Saya ambil dari session jika ada, kalau tidak ya skip.
========================================= */
$tingkat_pendidikan = $_SESSION['tingkat_pendidikan'] ?? ''; // kalau memang ada di session Anda

$qJenjang = mysqli_query($db, "SELECT nama_prodi FROM mr_prodi WHERE id='$prodi' LIMIT 1");
if ($qJenjang && mysqli_num_rows($qJenjang) > 0) {
    $rJ = mysqli_fetch_assoc($qJenjang);
    $namaProdi = strtoupper($rJ['nama_prodi'] ?? '');

    if ($tingkat_pendidikan === 'SARJANA' && strpos($namaProdi, 'S3-') === 0) {
        $_SESSION['pendaftaran_error'] = "Pegawai SARJANA tidak diperkenankan memilih Program S3";
        header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '../pendaftaran.php?iddaftar=0'));
        exit;
    }
}

// INSERT / UPDATE data
if ($iddaftar === 0) {
    $query = mysqli_query($db, "
        INSERT INTO pendaftaran
        (nip, id_prodi, tubel_ibel, rencana_kuliah, akhir_studi, status_usul, tgl_usul)
        VALUES
        ('$nip', '$prodi', '$tubelibel', '$rencanakuliah', '$akhir_studi', '$status', NOW())
    ");

    if ($query) {
        $iddaftar = mysqli_insert_id($db);
    }

} else {
    $query = mysqli_query($db, "
        UPDATE pendaftaran SET
            nip = '$nip',
            id_prodi = '$prodi',
            tubel_ibel = '$tubelibel',
            rencana_kuliah = '$rencanakuliah',
            akhir_studi = '$akhir_studi'
        WHERE id = '$iddaftar'
    ");
}

// Cek apakah query berhasil
if ($query) {
    $_SESSION['pendaftaran_status']  = 'success';
    $_SESSION['pendaftaran_message'] = 'Data berhasil disimpan & silahkan upload kelangkapan dokumen persyaratan';

    // Redirect SELALU ke upload sesuai jenis
    if ($tubelibel === 'tubel') {
        header("Location: ../daftar_upload.php?iddaftar=" . urlencode($iddaftar) . "&alasantms=" . urlencode($alasantms));
        exit;
    } elseif ($tubelibel === 'ibel') {
        header("Location: ../ibel_upload.php?iddaftar=" . urlencode($iddaftar) . "&alasantms=" . urlencode($alasantms));
        exit;
    }

    // fallback kalau tubelibel kosong/aneh
    header("Location: ../daftar_tubel.php?flash=1");
    exit;

} else {
    $_SESSION['pendaftaran_status']  = 'error';
    $_SESSION['pendaftaran_message'] = 'Gagal menyimpan data';
    header("Location: ../pendaftaran.php?iddaftar=" . urlencode($iddaftar));
    exit;
}
?>
