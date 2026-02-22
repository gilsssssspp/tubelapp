<?php
ob_start();
session_start();

require_once __DIR__ . '/../koneksi.php';

/* VALIDASI SESSION ADMIN */
if (!isset($_SESSION['nama']) || trim($_SESSION['nama']) === '') {
    $_SESSION['flash_status']  = 'error';
    $_SESSION['flash_message'] = 'Session admin tidak ditemukan';
    header("Location: /tubelapp/login.php");
    exit;
}

$id   = $_GET['iddaftar'] ?? $_POST['iddaftar'] ?? '';
$user = trim($_SESSION['nama']);

if ($id === '' || !ctype_digit((string)$id)) {
    $_SESSION['flash_status']  = 'error';
    $_SESSION['flash_message'] = 'ID daftar tidak valid';
    header("Location: /tubelapp/verifikasi.php");
    exit;
}
$idInt = (int)$id;

/* AMBIL INPUT */
$fields = [
    'surat_rpkasn'           => ['hasil' => $_POST['surat_rpkasn_hasil'] ?? '',            'alasan' => $_POST['surat_rpkasn_alasan'] ?? ''],
    'surat_pengantar'        => ['hasil' => $_POST['surat_pengantar_hasil'] ?? '',         'alasan' => $_POST['surat_pengantar_alasan'] ?? ''],
    'surat_loa'              => ['hasil' => $_POST['surat_loa_hasil'] ?? '',               'alasan' => $_POST['surat_loa_alasan'] ?? ''],
    'kalender_akademik'      => ['hasil' => $_POST['kalender_akademik_hasil'] ?? '',       'alasan' => $_POST['kalender_akademik_alasan'] ?? ''],
    'surat_akreditasi'       => ['hasil' => $_POST['surat_akreditasi_hasil'] ?? '',        'alasan' => $_POST['surat_akreditasi_alasan'] ?? ''],
    'skp_1'                  => ['hasil' => $_POST['skp_1_hasil'] ?? '',                   'alasan' => $_POST['skp_1_alasan'] ?? ''],
    'skp_2'                  => ['hasil' => $_POST['skp_2_hasil'] ?? '',                   'alasan' => $_POST['skp_2_alasan'] ?? ''],
    'surat_hukdis'           => ['hasil' => $_POST['surat_hukdis_hasil'] ?? '',            'alasan' => $_POST['surat_hukdis_alasan'] ?? ''],
    'surat_rekomibel'        => ['hasil' => $_POST['surat_rekomibel_hasil'] ?? '',         'alasan' => $_POST['surat_rekomibel_alasan'] ?? ''],
    'surat_pernyataanibel'   => ['hasil' => $_POST['surat_pernyataanibel_hasil'] ?? '',    'alasan' => $_POST['surat_pernyataanibel_alasan'] ?? ''],
];

/* 1) WAJIB PILIH MS/TMS SEMUA */
foreach ($fields as $k => $v) {
    if ($v['hasil'] === '') {
        $_SESSION['flash_status']  = 'error';
        $_SESSION['flash_message'] = 'Berkas ada yang belum dilakukan verifikasi (MS/TMS), silahkan cek kembali';
        header("Location: /tubelapp/verifikasi_upload_ibel.php?iddaftar=" . urlencode($idInt));
        exit;
    }
    if (!in_array($v['hasil'], ['MS', 'TMS'], true)) {
        $_SESSION['flash_status']  = 'error';
        $_SESSION['flash_message'] = "Nilai verifikasi tidak valid pada: {$k}";
        header("Location: /tubelapp/verifikasi_upload_ibel.php?iddaftar=" . urlencode($idInt));
        exit;
    }
}

/* 2) JIKA TMS → ALASAN WAJIB */
foreach ($fields as $k => $v) {
    if ($v['hasil'] === 'TMS' && trim($v['alasan']) === '') {
        $_SESSION['flash_status']  = 'error';
        $_SESSION['flash_message'] = 'Ada berkas TMS tetapi alasan belum diisi.';
        header("Location: /tubelapp/verifikasi_upload_ibel.php?iddaftar=" . urlencode($idInt));
        exit;
    }
}

/* 3) TENTUKAN HASIL & STATUS */
$adaTMS = false;
foreach ($fields as $v) {
    if ($v['hasil'] === 'TMS') { $adaTMS = true; break; }
}
$hasil_verifikasi = $adaTMS ? 'Tidak Lulus Verifikasi' : 'Lulus Verifikasi';
$status_usul      = $adaTMS ? 'TMS' : 'Sudah diverifikasi, Proses Penerbitan SK';

/* 4) UPDATE DB (PREPARED) */
$sql = "UPDATE pendaftaran SET
    surat_rpkasn_hasil=?, surat_rpkasn_alasan=?,
    surat_pengantar_hasil=?, surat_pengantar_alasan=?,
    surat_loa_hasil=?, surat_loa_alasan=?,
    kalender_akademik_hasil=?, kalender_akademik_alasan=?,
    surat_akreditasi_hasil=?, surat_akreditasi_alasan=?,
    skp_1_hasil=?, skp_1_alasan=?,
    skp_2_hasil=?, skp_2_alasan=?,
    surat_hukdis_hasil=?, surat_hukdis_alasan=?,
    surat_rekomibel_hasil=?, surat_rekomibel_alasan=?,
    surat_pernyataanibel_hasil=?, surat_pernyataanibel_alasan=?,
    tgl_verifikasi=NOW(), pic_verifikasi=?, hasil_verifikasi=?, status_usul=?
WHERE id=?";

$stmt = mysqli_prepare($db, $sql);
if (!$stmt) {
    $_SESSION['flash_status']  = 'error';
    $_SESSION['flash_message'] = 'Prepare failed: ' . mysqli_error($db);
    header("Location: /tubelapp/verifikasi_upload_ibel.php?iddaftar=" . urlencode($idInt));
    exit;
}

/* BIND PARAM */
$rpkasn_hasil   = $fields['surat_rpkasn']['hasil'];
$rpkasn_alasan  = $fields['surat_rpkasn']['alasan'];

$pengantar_hasil  = $fields['surat_pengantar']['hasil'];
$pengantar_alasan = $fields['surat_pengantar']['alasan'];

$loa_hasil  = $fields['surat_loa']['hasil'];
$loa_alasan = $fields['surat_loa']['alasan'];

$kal_hasil  = $fields['kalender_akademik']['hasil'];
$kal_alasan = $fields['kalender_akademik']['alasan'];

$akre_hasil  = $fields['surat_akreditasi']['hasil'];
$akre_alasan = $fields['surat_akreditasi']['alasan'];

$skp1_hasil  = $fields['skp_1']['hasil'];
$skp1_alasan = $fields['skp_1']['alasan'];

$skp2_hasil  = $fields['skp_2']['hasil'];
$skp2_alasan = $fields['skp_2']['alasan'];

$huk_hasil  = $fields['surat_hukdis']['hasil'];
$huk_alasan = $fields['surat_hukdis']['alasan'];

$rekom_hasil  = $fields['surat_rekomibel']['hasil'];
$rekom_alasan = $fields['surat_rekomibel']['alasan'];

$perny_hasil  = $fields['surat_pernyataanibel']['hasil'];
$perny_alasan = $fields['surat_pernyataanibel']['alasan'];

$pic      = $user;
$hasilVer = $hasil_verifikasi;
$statusUs = $status_usul;

/* 23 's' + 1 'i' */
$types = str_repeat('s', 23) . 'i';

mysqli_stmt_bind_param(
    $stmt,
    $types,
    $rpkasn_hasil, $rpkasn_alasan,
    $pengantar_hasil, $pengantar_alasan,
    $loa_hasil, $loa_alasan,
    $kal_hasil, $kal_alasan,
    $akre_hasil, $akre_alasan,
    $skp1_hasil, $skp1_alasan,
    $skp2_hasil, $skp2_alasan,
    $huk_hasil, $huk_alasan,
    $rekom_hasil, $rekom_alasan,
    $perny_hasil, $perny_alasan,
    $pic, $hasilVer, $statusUs,
    $idInt
);

if (!mysqli_stmt_execute($stmt)) {
    $_SESSION['flash_status']  = 'error';
    $_SESSION['flash_message'] = 'Execute failed: ' . mysqli_stmt_error($stmt);
    mysqli_stmt_close($stmt);
    header("Location: /tubelapp/verifikasi_upload_ibel.php?iddaftar=" . urlencode($idInt));
    exit;
}

mysqli_stmt_close($stmt);

/* FLASH + REDIRECT */
$_SESSION['flash_status']  = 'success';
$_SESSION['flash_message'] = 'Simpan Verifikasi Berhasil';
header("Location: /tubelapp/verifikasi.php");
exit;
