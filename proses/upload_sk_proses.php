<?php
session_start();
include '../koneksi.php';

/* ===============================
   VALIDASI SESSION (ADMIN)
   (Sesuaikan: kalau Anda punya role/level admin, cek di sini)
================================ */
if (!isset($_SESSION['nip'])) {
    $_SESSION['flash_status'] = 'error';
    $_SESSION['flash_message'] = 'Akses ditolak';
    header("Location: /tubelapp/login.php");
    exit;
}

// OPTIONAL: jika ada role admin
// if (($_SESSION['role'] ?? '') !== 'admin') { die('Akses ditolak'); }

$iddaftar = $_POST['iddaftar'] ?? '';
$file     = $_FILES['file_sk'] ?? null;

if ($iddaftar === '' || !$file) {
    $_SESSION['flash_status'] = 'error';
    $_SESSION['flash_message'] = 'Data tidak lengkap.';
    header("Location: /tubelapp/verifikasi.php?flash=1");   
    exit;
}

/* ===============================
   CEK ERROR UPLOAD PHP
================================ */
if ($file['error'] !== UPLOAD_ERR_OK) {
    $_SESSION['flash_status'] = 'error';
    $_SESSION['flash_message'] = 'Upload error code: ' . $file['error'];
    header("Location: /tubelapp/verifikasi.php?flash=1");
    exit;
}

/* ===============================
   AMBIL DATA PENGAJUAN:
   nip pemohon, jenis, status_usul, sk
================================ */
$stmt = mysqli_prepare(
    $db,
    "SELECT nip, tubel_ibel, status_usul, sk
     FROM pendaftaran
     WHERE id = ?
     LIMIT 1"
);
mysqli_stmt_bind_param($stmt, "s", $iddaftar);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

if (!$res || mysqli_num_rows($res) === 0) {
    $_SESSION['flash_status'] = 'error';
    $_SESSION['flash_message'] = 'Data pengajuan tidak ditemukan.';
    header("Location: /tubelapp/verifikasi.php?flash=1");
    exit;
}

$row        = mysqli_fetch_assoc($res);
$nipPemohon = trim($row['nip'] ?? '');
$jenis      = strtolower(trim($row['tubel_ibel'] ?? ''));
$statusUsul = trim($row['status_usul'] ?? '');
$skLama     = trim($row['sk'] ?? '');

/* ===============================
   VALIDASI DATA DASAR
================================ */
if ($nipPemohon === '') {
    $_SESSION['flash_status'] = 'error';
    $_SESSION['flash_message'] = 'NIP pemohon kosong di database.';
    header("Location: /tubelapp/verifikasi.php?flash=1");   
    exit;
}

if (!in_array($jenis, ['ibel', 'tubel'], true)) {
    $_SESSION['flash_status'] = 'error';
    $_SESSION['flash_message'] = 'Jenis pengajuan tidak valid.';
    header("Location: /tubelapp/verifikasi.php?flash=1");
    exit;
}

/* ===============================
   KUNCI PROSES:
   - SK hanya boleh jika status_usul = Menunggu SK
   - Jika TMS / lainnya => tolak
================================ */
$allowedStatus = [
    'Sudah diverifikasi, Proses Penerbitan SK',
    'Selesai'
];

if (!in_array($statusUsul, $allowedStatus, true)) {
     $_SESSION['flash_status'] = 'error';
     $_SESSION['flash_message'] = 'Tidak bisa upload SK. Status usul saat ini: ' . $statusUsul;
     header("Location: /tubelapp/verifikasi.php?flash=1");  
    exit;
}


/* mencegah overwrite SK */
// if ($skLama !== '') {
//     echo "<script>alert('SK sudah pernah diupload.'); history.back();</script>";
//     exit;
// }

/* ===============================
   VALIDASI EKSTENSI FILE
================================ */
$allowed = ['pdf', 'jpg', 'jpeg', 'png'];
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if (!in_array($ext, $allowed, true)) {
     $_SESSION['flash_status'] = 'error';
     $_SESSION['flash_message'] = 'Format file tidak diizinkan.';
     header("Location: /tubelapp/verifikasi.php?flash=1");  
    exit;
}

/* ===============================
   FOLDER:
   uploads/sk/{nipPemohon}/{jenis}/
================================ */
$folderRel = "uploads/sk/{$nipPemohon}/{$jenis}/";
$folderAbs = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . "/tubelapp/" . $folderRel;

if (!is_dir($folderAbs)) {
    if (!mkdir($folderAbs, 0755, true)) {
        $_SESSION['flash_status'] = 'error';
        $_SESSION['flash_message'] = 'Gagal membuat folder upload';
        header("Location: /tubelapp/verifikasi.php?flash=1");   
        exit;
    }
}

/* ===============================
   NAMA FILE (unik)
   supaya tidak bentrok bila upload ulang di masa depan:
   NIP_JENIS_SK_YYYYmmdd_His.ext
================================ */
$timestamp = date('Ymd_His');
$namaFile  = "{$nipPemohon}_{$jenis}_SK_{$timestamp}.{$ext}";
$pathFile  = $folderAbs . $namaFile;

/* ===============================
   HAPUS FILE SK LAMA (JIKA ADA)
================================ */
if ($skLama !== '') {
    $pathSkLama = $folderAbs . $skLama;
    if (file_exists($pathSkLama)) {
        @unlink($pathSkLama);
    }
}

/* ===============================
   PINDAHKAN FILE
================================ */
if (!move_uploaded_file($file['tmp_name'], $pathFile)) {
    $_SESSION['flash_status'] = 'error';
    $_SESSION['flash_message'] = 'Gagal menyimpan file';
    header("Location: /tubelapp/verifikasi.php?flash=1");
    exit;
}

/* ===============================
   UPDATE DB:
   - simpan nama file
   - (opsional) set status Selesai jika Anda mau status langsung berubah
================================ */
$stmt2 = mysqli_prepare(
    $db,
    "UPDATE pendaftaran
     SET sk = ?, status_usul = 'Selesai', tgl_sk_terbit=now()
     WHERE id = ?"
);
mysqli_stmt_bind_param($stmt2, "ss", $namaFile, $iddaftar);

if (!mysqli_stmt_execute($stmt2)) {
    // rollback file kalau DB gagal
    @unlink($pathFile);
    $_SESSION['flash_status'] = 'error';
    $_SESSION['flash_message'] = 'Gagal update database';
    header("Location: /tubelapp/verifikasi.php?flash=1");   
    exit;
}

/* ===============================
   REDIRECT SUKSES
================================ */
$_SESSION['flash_status'] = 'success';
$_SESSION['flash_message'] = 'Upload SK berhasil';
header("Location: /tubelapp/verifikasi.php?flash=1");
exit;