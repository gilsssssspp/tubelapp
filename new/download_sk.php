<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['nip'])) {
    die('Akses ditolak');
}

$nip = $_SESSION['nip'];
$id  = $_GET['iddaftar'] ?? '';

if ($id === '') die('ID tidak valid');

// Ambil SK + jenis berdasarkan id + nip
$stmt = mysqli_prepare($db, "SELECT sk, tubel_ibel FROM pendaftaran WHERE id=? AND nip=? LIMIT 1");
mysqli_stmt_bind_param($stmt, "ss", $id, $nip);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

if (!$res || mysqli_num_rows($res) === 0) die('Data tidak ditemukan');

$row   = mysqli_fetch_assoc($res);
$skRaw = trim($row['sk'] ?? '');
$jenis = strtolower(trim($row['tubel_ibel'] ?? ''));

if ($skRaw === '') die('SK belum tersedia');

// normalisasi jenis folder
if (in_array($jenis, ['tugas belajar', 'tugas_belajar', 'tugasbelajar'], true)) $jenis = 'tubel';
if (in_array($jenis, ['izin belajar', 'izin_belajar', 'izinbelajar'], true)) $jenis = 'ibel';

if (!in_array($jenis, ['tubel','ibel'], true)) die('Jenis pengajuan tidak valid');

// SK bisa berupa "nama.pdf" atau "tubel/nama.pdf" atau "ibel/nama.pdf"
$skRaw = str_replace('\\', '/', $skRaw);
$skFile = basename($skRaw); // tetap download nama file saja (aman)

// Kandidat base folder uploads (sesuaikan yang cocok dengan struktur project kamu)
// Kandidat base folder uploads
$candidateBases = [
    __DIR__ . "/uploads",
    __DIR__ . "/../uploads",
    dirname(__DIR__) . "/uploads"
];

// Kandidat lokasi file (sesuai struktur kamu: uploads/sk/{jenis}/{nip}/{file})
$paths = [];
foreach ($candidateBases as $base) {
    $paths[] = $base . "/sk/{$jenis}/{$nip}/{$skFile}"; // ✅ ini yang benar utk struktur kamu
    $paths[] = $base . "/sk/{$nip}/{$jenis}/{$skFile}"; // fallback (kalau ada yg lama)
    $paths[] = $base . "/sk/{$nip}/{$skFile}";          // fallback
}


$filePath = null;
foreach ($paths as $p) {
    if (file_exists($p)) {
        $filePath = $p;
        break;
    }
}

if ($filePath === null) {
    // mode debug singkat (boleh hapus setelah beres)
    echo "File SK tidak ditemukan di server<br><br>";
    echo "SK(DB) = " . htmlspecialchars($skRaw) . "<br>";
    echo "NIP    = " . htmlspecialchars($nip) . "<br>";
    echo "JENIS  = " . htmlspecialchars($jenis) . "<br><br>";
    echo "Cek lokasi berikut:<br>";
    foreach ($paths as $p) {
        echo htmlspecialchars($p) . "<br>";
    }
    exit;
}

// Content-type
$ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
$mime = 'application/octet-stream';
if ($ext === 'pdf') $mime = 'application/pdf';
if ($ext === 'jpg' || $ext === 'jpeg') $mime = 'image/jpeg';
if ($ext === 'png') $mime = 'image/png';

header('Content-Description: File Transfer');
header('Content-Type: ' . $mime);
header('Content-Disposition: attachment; filename="' . $skFile . '"');
header('Content-Length: ' . filesize($filePath));
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
readfile($filePath);
exit;
