<?php
session_start();
require_once __DIR__ . '/../koneksi.php';

/* ===============================
   VALIDASI SESSION ADMIN
================================ */
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
    header("Location: /tubelapp/verifikasi_upload_tubel.php?iddaftar={$id}");
    exit;
}
$idInt = (int)$id;

/* ===============================
   AMBIL INPUT (FIELD TUBEL TETAP)
   Catatan: berhenti struktural & berhenti JF dinonaktifkan
================================ */
$fields = [
    'surat_rpkasn'          => ['hasil' => $_POST['surat_rpkasn_hasil'] ?? '',          'alasan' => $_POST['surat_rpkasn_alasan'] ?? ''],
    'surat_pengantar'       => ['hasil' => $_POST['surat_pengantar_hasil'] ?? '',       'alasan' => $_POST['surat_pengantar_alasan'] ?? ''],
    'surat_loa'             => ['hasil' => $_POST['surat_loa_hasil'] ?? '',             'alasan' => $_POST['surat_loa_alasan'] ?? ''],
    'surat_penerimabeasiswa'=> ['hasil' => $_POST['surat_penerimabeasiswa_hasil'] ?? '', 'alasan' => $_POST['surat_penerimabeasiswa_alasan'] ?? ''],
    'kalender_akademik'     => ['hasil' => $_POST['kalender_akademik_hasil'] ?? '',    'alasan' => $_POST['kalender_akademik_alasan'] ?? ''],
    'surat_akreditasi'      => ['hasil' => $_POST['surat_akreditasi_hasil'] ?? '',     'alasan' => $_POST['surat_akreditasi_alasan'] ?? ''],
    'surat_jasmani'         => ['hasil' => $_POST['surat_jasmani_hasil'] ?? '',        'alasan' => $_POST['surat_jasmani_alasan'] ?? ''],
    'surat_rohani'          => ['hasil' => $_POST['surat_rohani_hasil'] ?? '',         'alasan' => $_POST['surat_rohani_alasan'] ?? ''],
    'ijazah_terakhir'       => ['hasil' => $_POST['ijazah_terakhir_hasil'] ?? '',      'alasan' => $_POST['ijazah_terakhir_alasan'] ?? ''],
    'transkrip_nilai'       => ['hasil' => $_POST['transkrip_nilai_hasil'] ?? '',      'alasan' => $_POST['transkrip_nilai_alasan'] ?? ''],
    'skp_1'                 => ['hasil' => $_POST['skp_1_hasil'] ?? '',                'alasan' => $_POST['skp_1_alasan'] ?? ''],
    'skp_2'                 => ['hasil' => $_POST['skp_2_hasil'] ?? '',                'alasan' => $_POST['skp_2_alasan'] ?? ''],
    'surat_hukdis'          => ['hasil' => $_POST['surat_hukdis_hasil'] ?? '',         'alasan' => $_POST['surat_hukdis_alasan'] ?? ''],
    // DINONAKTIFKAN:
    // 'surat_berhentistruktural' => ['hasil' => $_POST['surat_berhentistruktural_hasil'] ?? '', 'alasan' => $_POST['surat_berhentistruktural_alasan'] ?? ''],
    // 'surat_berhentiJF'         => ['hasil' => $_POST['surat_berhentiJF_hasil'] ?? '',         'alasan' => $_POST['surat_berhentiJF_alasan'] ?? ''],
    'surat_dimanasaja'      => ['hasil' => $_POST['surat_dimanasaja_hasil'] ?? '',     'alasan' => $_POST['surat_dimanasaja_alasan'] ?? ''],
];

/* ===============================
   1) WAJIB PILIH MS/TMS SEMUA
================================ */
foreach ($fields as $k => $v) {
    if ($v['hasil'] === '') {
        $_SESSION['flash_status']  = 'error';
        $_SESSION['flash_message'] = 'Berkas ada yang belum dilakukan verifikasi (MS/TMS), silahkan cek kembali';
        header("Location: /tubelapp/verifikasi_upload_tubel.php?iddaftar={$idInt}");
        exit;
    }
    if (!in_array($v['hasil'], ['MS', 'TMS'], true)) {
        $_SESSION['flash_status']  = 'error';
        $_SESSION['flash_message'] = "Nilai verifikasi tidak valid pada: {$k}";
        header("Location: /tubelapp/verifikasi_upload_tubel.php?iddaftar={$idInt}");
        exit;
        exit;
    }
}

/* ===============================
   2) JIKA TMS → ALASAN WAJIB
================================ */
foreach ($fields as $k => $v) {
    if ($v['hasil'] === 'TMS' && trim($v['alasan']) === '') {
        $_SESSION['flash_status']  = 'error';
        $_SESSION['flash_message'] = 'Ada berkas TMS tetapi alasan belum diisi.';
        header("Location: /tubelapp/verifikasi_upload_tubel.php?iddaftar={$idInt}");
        exit;
    }
}

/* ===============================
   3) TENTUKAN HASIL & STATUS
================================ */
$adaTMS = false;
foreach ($fields as $v) {
    if ($v['hasil'] === 'TMS') { $adaTMS = true; break; }
}
$hasil_verifikasi = $adaTMS ? 'Tidak Lulus Verifikasi' : 'Lulus Verifikasi';
$status_usul      = $adaTMS ? 'TMS' : 'Sudah diverifikasi, Proses Penerbitan SK';

/* ===============================
   4) UPDATE DB (PREPARED)
   Penting: TIDAK pakai komentar SQL "--" di dalam string query
================================ */
$sql = "UPDATE pendaftaran SET
    surat_rpkasn_hasil=?, surat_rpkasn_alasan=?,
    surat_pengantar_hasil=?, surat_pengantar_alasan=?,
    surat_loa_hasil=?, surat_loa_alasan=?,
    surat_penerimabeasiswa_hasil=?, surat_penerimabeasiswa_alasan=?,
    kalender_akademik_hasil=?, kalender_akademik_alasan=?,
    surat_akreditasi_hasil=?, surat_akreditasi_alasan=?,
    surat_jasmani_hasil=?, surat_jasmani_alasan=?,
    surat_rohani_hasil=?, surat_rohani_alasan=?,
    ijazah_terakhir_hasil=?, ijazah_terakhir_alasan=?,
    transkrip_nilai_hasil=?, transkrip_nilai_alasan=?,
    skp_1_hasil=?, skp_1_alasan=?,
    skp_2_hasil=?, skp_2_alasan=?,
    surat_hukdis_hasil=?, surat_hukdis_alasan=?,
    surat_dimanasaja_hasil=?, surat_dimanasaja_alasan=?,
    tgl_verifikasi=NOW(), pic_verifikasi=?, hasil_verifikasi=?, status_usul=?
WHERE id=?";

$stmt = mysqli_prepare($db, $sql);
if (!$stmt) {
    die("Prepare failed: " . mysqli_error($db));
}

/* ===============================
   BIND PARAM (harus variabel, bukan array langsung)
================================ */
$rpkasn_hasil   = $fields['surat_rpkasn']['hasil'];
$rpkasn_alasan  = $fields['surat_rpkasn']['alasan'];

$pengantar_hasil  = $fields['surat_pengantar']['hasil'];
$pengantar_alasan = $fields['surat_pengantar']['alasan'];

$loa_hasil  = $fields['surat_loa']['hasil'];
$loa_alasan = $fields['surat_loa']['alasan'];

$penerima_hasil  = $fields['surat_penerimabeasiswa']['hasil'];
$penerima_alasan = $fields['surat_penerimabeasiswa']['alasan'];

$kal_hasil  = $fields['kalender_akademik']['hasil'];
$kal_alasan = $fields['kalender_akademik']['alasan'];

$akre_hasil  = $fields['surat_akreditasi']['hasil'];
$akre_alasan = $fields['surat_akreditasi']['alasan'];

$jas_hasil  = $fields['surat_jasmani']['hasil'];
$jas_alasan = $fields['surat_jasmani']['alasan'];

$roh_hasil  = $fields['surat_rohani']['hasil'];
$roh_alasan = $fields['surat_rohani']['alasan'];

$ijz_hasil  = $fields['ijazah_terakhir']['hasil'];
$ijz_alasan = $fields['ijazah_terakhir']['alasan'];

$tr_hasil  = $fields['transkrip_nilai']['hasil'];
$tr_alasan = $fields['transkrip_nilai']['alasan'];

$skp1_hasil  = $fields['skp_1']['hasil'];
$skp1_alasan = $fields['skp_1']['alasan'];

$skp2_hasil  = $fields['skp_2']['hasil'];
$skp2_alasan = $fields['skp_2']['alasan'];

$huk_hasil  = $fields['surat_hukdis']['hasil'];
$huk_alasan = $fields['surat_hukdis']['alasan'];

$dm_hasil  = $fields['surat_dimanasaja']['hasil'];
$dm_alasan = $fields['surat_dimanasaja']['alasan'];

$pic       = $user;
$hasilVer  = $hasil_verifikasi;
$statusUs  = $status_usul;

/* total placeholder:
   - 14 dokumen x (hasil+alasan) = 28 string
   - pic, hasilVer, statusUs = 3 string
   - id = 1 int
   => 31 string + 1 int => "ssss...(31x)...i"
*/
$types = str_repeat('s', 31) . 'i';

mysqli_stmt_bind_param(
    $stmt,
    $types,
    $rpkasn_hasil, $rpkasn_alasan,
    $pengantar_hasil, $pengantar_alasan,
    $loa_hasil, $loa_alasan,
    $penerima_hasil, $penerima_alasan,
    $kal_hasil, $kal_alasan,
    $akre_hasil, $akre_alasan,
    $jas_hasil, $jas_alasan,
    $roh_hasil, $roh_alasan,
    $ijz_hasil, $ijz_alasan,
    $tr_hasil, $tr_alasan,
    $skp1_hasil, $skp1_alasan,
    $skp2_hasil, $skp2_alasan,
    $huk_hasil, $huk_alasan,
    $dm_hasil, $dm_alasan,
    $pic, $hasilVer, $statusUs,
    $idInt
);

if (!mysqli_stmt_execute($stmt)) {
    die("Execute failed: " . mysqli_stmt_error($stmt));
}

mysqli_stmt_close($stmt);

$_SESSION['flash_status']  = 'success';
$_SESSION['flash_message'] = 'Simpan Verifikasi Berhasil';
header("Location: /tubelapp/verifikasi.php");
exit;
?>
