<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['nip'])) {
    $_SESSION['upload_status']  = 'error';
    $_SESSION['upload_message'] = 'Akses ditolak';
    header("Location: ../login.php");
    exit;
}

$iddaftar = $_GET['iddaftar'] ?? '';
$nip      = $_SESSION['nip'];

if ($iddaftar === '' || !ctype_digit((string)$iddaftar)) {
    $_SESSION['upload_status']  = 'error';
    $_SESSION['upload_message'] = 'ID pendaftaran tidak valid';
    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '../daftar_tubel.php'));
    exit;
}

$stmt = mysqli_prepare($db, "SELECT id, nip, tubel_ibel,
    surat_pengantar, surat_loa, kalender_akademik, surat_akreditasi,
    skp_1, skp_2, surat_rekomibel, surat_hukdis, surat_pernyataanibel
  FROM pendaftaran
  WHERE id=? AND nip=?
  LIMIT 1");
mysqli_stmt_bind_param($stmt, "is", $iddaftar, $nip);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

if (!$res || mysqli_num_rows($res) === 0) {
    $_SESSION['upload_status']  = 'error';
    $_SESSION['upload_message'] = 'Data tidak ditemukan / bukan milik Anda';
    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '../daftar_tubel.php'));
    exit;
}

$row = mysqli_fetch_assoc($res);
$rawJenis = strtolower(trim($row['tubel_ibel'] ?? ''));

$jenis = (strpos($rawJenis, 'ibel') !== false) ? 'ibel' : 'tubel';

$backPage = ($jenis === 'ibel') ? '../ibel_upload.php' : '../daftar_upload.php';

$allowedExt = ['pdf','jpg','jpeg','png'];
$maxSize    = 1 * 1024 * 1024; // 1MB per file

$folderRel = "uploads/{$jenis}/{$nip}/";
$folderAbs = realpath(__DIR__ . "/..") . "/{$folderRel}";

if (!is_dir($folderAbs)) {
    if (!mkdir($folderAbs, 0777, true)) {
        $_SESSION['upload_status']  = 'error';
        $_SESSION['upload_message'] = 'Gagal membuat folder upload';
        header("Location: {$backPage}?iddaftar=" . urlencode($iddaftar));
        exit;
    }
}

$map = [
    'surat_pengantar'       => 'surat_pengantar',
    'surat_loa'             => 'surat_loa',
    'surat_penerimabeasiswa'=> 'surat_penerimabeasiswa',
    'kalender_akademik'     => 'kalender_akademik',
    'surat_akreditasi'      => 'surat_akreditasi',
    'surat_jasmani'         => 'surat_jasmani',
    'surat_rohani'          => 'surat_rohani',
    'ijazah_terakhir'       => 'ijazah_terakhir',
    'transkrip_nilai'       => 'transkrip_nilai',
    'skp_1'                 => 'skp_1',
    'skp_2'                 => 'skp_2',
    'surat_hukdis'          => 'surat_hukdis',
    'surat_rekomibel'       => 'surat_rekomibel',
    'surat_pernyataanibel'  => 'surat_pernyataanibel',
    'surat_dimanasaja'      => 'surat_dimanasaja',
];

$labelMap = [
    'surat_pengantar'        => 'Surat Pengantar',
    'surat_loa'              => 'Surat LOA',
    'surat_penerimabeasiswa' => 'Surat Penerimaan Beasiswa',
    'kalender_akademik'      => 'Kalender Akademik / Mulai Kuliah',
    'surat_akreditasi'       => 'Surat Akreditasi',
    'surat_jasmani'          => 'Surat Keterangan Jasmani',
    'surat_rohani'           => 'Surat Keterangan Rohani',
    'ijazah_terakhir'        => 'Ijazah Terakhir',
    'transkrip_nilai'        => 'Transkrip Nilai',
    'skp_1'                  => 'SKP 2025',
    'skp_2'                  => 'SKP 2024',
    'surat_hukdis'           => 'Surat Bebas Hukuman Disiplin',
    'surat_rekomibel'        => 'Surat Rekomendasi Izin Belajar',
    'surat_pernyataanibel'   => 'Surat Pernyataan Izin Belajar',
    'surat_dimanasaja'       => 'Surat Dimana Saja',
];

function labelDoc($key, $labelMap) {
    return $labelMap[$key] ?? ucwords(str_replace('_', ' ', $key));
}

$updates = [];
$errors  = [];




foreach ($map as $inputName => $dbCol) {

    if (!isset($_FILES[$inputName])) continue;

    $f     = $_FILES[$inputName];
    $label = labelDoc($inputName, $labelMap);

    if ($f['error'] === UPLOAD_ERR_NO_FILE) {
        continue;
    }

    if ($f['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "$label gagal diupload (error code: {$f['error']})";
        continue;
    }

    if ($f['size'] > $maxSize) {
        $sizeMb   = round($f['size'] / 1024 / 1024, 2);
        $errors[] = "$label terlalu besar ({$sizeMb} MB). Maksimal 1 MB.";
        continue;
    }

    $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExt, true)) {
        $errors[] = "$label ekstensi tidak diizinkan";
        continue;
    }

    $safeKey = preg_replace('/[^a-z0-9_]+/i', '_', $inputName);
    $newName = $nip . "_" . $safeKey . "_" . time() . "." . $ext;
    $destAbs = rtrim($folderAbs, "/\\") . "/" . $newName;



    if (!move_uploaded_file($f['tmp_name'], $destAbs)) {
        $errors[] = "$label gagal disimpan ke folder";
        continue;
    }

    $updates[$dbCol] = $newName;
}

if (!empty($updates)) {
    $setParts = [];
    $types    = '';
    $vals     = [];

    foreach ($updates as $col => $val) {
        $setParts[] = "{$col}=?";
        $types     .= 's';
        $vals[]     = $val;
    }

    $sql = "UPDATE pendaftaran SET " . implode(', ', $setParts) . " WHERE id=? AND nip=?";
    $types .= 'is';
    $vals[] = (int)$iddaftar;
    $vals[] = $nip;

    $stmtUp = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmtUp, $types, ...$vals);
    mysqli_stmt_execute($stmtUp);
}

/* ==========================
   OUTPUT AKHIR (FLASH + REDIRECT)
   ========================== */

if (!empty($errors)) {
    $_SESSION['upload_status']  = 'error';
    $_SESSION['upload_message'] = "Sebagian upload gagal:\n- " . implode("\n- ", $errors);

    header("Location: {$backPage}?iddaftar=" . urlencode($iddaftar) . "&flash_upload=1");
    exit;
}

$_SESSION['upload_status']  = 'success';
$_SESSION['upload_message'] = 'Upload berhasil';

//header("Location: ../daftar_tubel.php?iddaftar=" . urlencode($iddaftar) . "&flash_upload=1");

header("Location: ../submit.php?iddaftar=" . urlencode($iddaftar));

exit;
