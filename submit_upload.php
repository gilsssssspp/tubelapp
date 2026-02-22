<?php
session_start();
include 'koneksi.php';

// Cek login
if (!isset($_SESSION['nip'])) {
    header("Location: login.php");
    exit;
}

// validasi iddaftar
if (!isset($_GET['iddaftar'])) {
    $_SESSION['flash_status']  = 'error';
    $_SESSION['flash_message'] = 'ID tidak ditemukan!';
    header("Location: daftar_tubel.php");
    exit;
}

$id  = (int) $_GET['iddaftar'];
$nip = mysqli_real_escape_string($db, $_SESSION['nip']);

// ambil data: WAJIB cek kepemilikan nip biar aman (jangan hapus!)
$q = mysqli_query($db, "SELECT * FROM pendaftaran WHERE id=$id AND nip='$nip' LIMIT 1");
if (!$q || mysqli_num_rows($q) == 0) {
    $_SESSION['flash_status']  = 'error';
    $_SESSION['flash_message'] = 'Data pendaftaran tidak ditemukan / bukan milik Anda.';
    header("Location: daftar_tubel.php");
    exit;
}

$row  = mysqli_fetch_assoc($q);
$jenis = strtolower(trim($row['tubel_ibel']));

// field wajib
$requiredFields = [
    'surat_pengantar'   => 'Surat Pengantar',
    'surat_loa'         => 'Bukti penerimaan perguruan tinggi (LOA)',
    'skp_1'             => 'SKP N-2',
    'skp_2'             => 'SKP N-1',
    'kalender_akademik' => 'Kalender Akademik',
    'surat_akreditasi'  => 'Surat Akreditasi',
    'surat_hukdis'      => 'Surat Bebas Hukdis',
];

if ($jenis === 'tubel') {
    $requiredFields += [
        'surat_penerimabeasiswa' => 'Surat Penerimaan Beasiswa',
        'surat_jasmani'          => 'Surat Sehat Jasmani',
        'surat_rohani'           => 'Surat Sehat Rohani',
        'ijazah_terakhir'        => 'Ijazah Terakhir',
        'transkrip_nilai'        => 'Transkrip Nilai',
        'surat_dimanasaja'       => 'Surat Pernyataan Bersedia ditempatkan dimana saja',
    ];
} elseif ($jenis === 'ibel') {
    $requiredFields += [
        'surat_rekomibel'      => 'Surat Rekomendasi',
        'surat_pernyataanibel' => 'Surat Pernyataan Izin Belajar',
    ];
}

// cek yang belum ada
$missing = [];
foreach ($requiredFields as $fieldName => $label) {
    if (empty($row[$fieldName])) {
        $missing[] = $label;
    }
}

$redirectUpload = ($jenis === 'ibel')
    ? "ibel_upload.php?iddaftar=" . urlencode($id)
    : "daftar_upload.php?iddaftar=" . urlencode($id);

// kalau kurang -> kirim flash HTML agar muncul SweetAlert di daftar_tubel.php
if (!empty($missing)) {
    $htmlList = "<div style='text-align:left;line-height:1.6'>"
              . "<b>Harap lengkapi upload dokumen terlebih dahulu:</b><br>"
              . "- " . implode("<br>- ", array_map('htmlspecialchars', $missing))
              . "</div>";

    $_SESSION['flash_html_icon']  = 'warning';
    $_SESSION['flash_html_title'] = 'Dokumen Belum Lengkap';
    $_SESSION['flash_html_body']  = $htmlList;
    $_SESSION['flash_html_next']  = $redirectUpload;

    header("Location: daftar_tubel.php");
    exit;
}

// lanjut submit (lengkap)
$upd = mysqli_query($db, "UPDATE pendaftaran
                          SET
                              status_usul='Proses verifikasi',
                              tgl_usul=NOW(),
                              tgl_submit=NOW(),
                              tgl_verifikasi=NULL,
                              hasil_verifikasi=NULL
                          WHERE id=$id AND nip='$nip'");

if ($upd) {
//    window.location.href = "proses/proses_upload.php?iddaftar=<?=$id?>"

   $_SESSION['flash_status']='success';
    $_SESSION['flash_message']='Data berhasil disubmit!';
    header("Location: daftar_tubel.php");
    exit;

} else {
  //  window.location.href = "proses/proses_upload.php?iddaftar=<?=$id?>"
    $_SESSION['flash_status']='success';
    $_SESSION['flash_message']='Data berhasil disubmit!';
    header("Location: daftar_tubel.php");
    exit;

}
