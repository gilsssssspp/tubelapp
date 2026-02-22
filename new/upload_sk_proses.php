<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['nip'])) {
    echo "<script>alert('Akses ditolak'); window.location='/tubelapp/login.php';</script>";
    exit;
}

$iddaftar = $_POST['iddaftar'] ?? '';
$file     = $_FILES['file_sk'] ?? null;

if ($iddaftar === '' || !$file) {
    echo "<script>alert('Data tidak lengkap'); history.back();</script>";
    exit;
}

if ($file['error'] !== UPLOAD_ERR_OK) {
    echo "<script>alert('Upload error code: {$file['error']}'); history.back();</script>";
    exit;
}

/* Ambil NIP pemohon + jenis pengajuan dari DB berdasarkan ID */
$stmt = mysqli_prepare($db, "SELECT nip, tubel_ibel FROM pendaftaran WHERE id=? LIMIT 1");
mysqli_stmt_bind_param($stmt, "s", $iddaftar);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

if (!$res || mysqli_num_rows($res) === 0) {
    echo "<script>alert('Data pengajuan tidak ditemukan'); history.back();</script>";
    exit;
}

$row = mysqli_fetch_assoc($res);
$nipPemohon = trim($row['nip'] ?? '');
$jenis      = strtolower(trim($row['tubel_ibel'] ?? ''));

if ($nipPemohon === '') {
    echo "<script>alert('NIP pemohon kosong di database'); history.back();</script>";
    exit;
}

if (!in_array($jenis, ['ibel', 'tubel'], true)) {
    echo "<script>alert('Jenis pengajuan tidak valid'); history.back();</script>";
    exit;
}

/* Validasi ekstensi */
$allowed = ['pdf', 'jpg', 'jpeg', 'png'];
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
if (!in_array($ext, $allowed, true)) {
    echo "<script>alert('Format file tidak diizinkan'); history.back();</script>";
    exit;
}

/* Folder: uploads/sk/{nip}/{jenis}/ */
$folderRel = "uploads/sk/{$nipPemohon}/{$jenis}/";
$folderAbs = $_SERVER['DOCUMENT_ROOT'] . "/tubelapp/" . $folderRel;

if (!is_dir($folderAbs)) {
    mkdir($folderAbs, 0755, true);
}

/* Nama file */
$namaFile = "{$nipPemohon}_{$jenis}_SK.{$ext}";
$pathFile = $folderAbs . $namaFile;

/* Pindahkan file */
if (!move_uploaded_file($file['tmp_name'], $pathFile)) {
    echo "<script>alert('Gagal menyimpan file'); history.back();</script>";
    exit;
}

/* Update DB */
$stmt2 = mysqli_prepare(
    $db,
    "UPDATE pendaftaran 
     SET sk = ?, status_usul = 'Selesai'
     WHERE id = ? AND nip = ?"
);

mysqli_stmt_bind_param(
    $stmt2,
    "sss",
    $namaFile,
    $iddaftar,
    $nipPemohon
);

mysqli_stmt_execute($stmt2);


echo "<script>
        alert('Upload SK berhasil');
        window.location='/tubelapp/verifikasi.php';
      </script>";
exit;
