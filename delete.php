<?php
session_start();
include 'koneksi.php';

// Cek login
if (!isset($_SESSION['nip'])) {
    header("Location: login.php");
    exit;
}

// Validasi parameter
if (!isset($_GET['iddaftar'])) {
    $_SESSION['flash_status']  = 'error';
    $_SESSION['flash_message'] = 'ID tidak ditemukan!';
    header("Location: daftar_tubel.php");
    exit;
}

$id  = (int) $_GET['iddaftar'];
$nip = mysqli_real_escape_string($db, $_SESSION['nip']);

// Pastikan data ada & milik user yang login
$cek = mysqli_query($db, "SELECT id FROM pendaftaran WHERE id=$id AND nip='$nip' LIMIT 1");
if (!$cek || mysqli_num_rows($cek) == 0) {
    $_SESSION['flash_status']  = 'error';
    $_SESSION['flash_message'] = 'Data tidak ditemukan / bukan milik Anda.';
    header("Location: daftar_tubel.php");
    exit;
}

// Hapus data
$del = mysqli_query($db, "DELETE FROM pendaftaran WHERE id=$id AND nip='$nip'");

if ($del) {
    $_SESSION['flash_status']  = 'success';
    $_SESSION['flash_message'] = 'Data berhasil dihapus!';
} else {
    $_SESSION['flash_status']  = 'error';
    $_SESSION['flash_message'] = 'Gagal menghapus data!';
}

header("Location: daftar_tubel.php");
exit;
