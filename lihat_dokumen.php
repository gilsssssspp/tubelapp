<?php
session_start();
include 'koneksi.php';

// pastikan admin
if (!isset($_SESSION['type']) || $_SESSION['type'] !== 'admin') {
    die('Akses ditolak');
}

$iddaftar = $_GET['iddaftar'] ?? '';
if (!ctype_digit($iddaftar)) {
    die('ID tidak valid');
}

// ambil data pendaftaran + dokumen
$q = mysqli_query($db, "
    SELECT
        a.id, a.nip, a.tubel_ibel,
        a.surat_pengantar,
        a.surat_loa,
        a.surat_penerimabeasiswa,
        a.kalender_akademik,
        a.surat_akreditasi,
        a.surat_jasmani,
        a.surat_rohani,
        a.ijazah_terakhir,
        a.transkrip_nilai,
        a.skp_1,
        a.skp_2,
        a.surat_hukdis,
        a.surat_rekomibel,
        a.surat_pernyataanibel,
        a.surat_dimanasaja,
        p.nama
    FROM pendaftaran a
    JOIN mr_pegawai p ON p.nip = a.nip
    WHERE a.id = '$iddaftar'
    LIMIT 1
");

if (!$q || mysqli_num_rows($q) === 0) {
    die('Data tidak ditemukan');
}

$data = mysqli_fetch_assoc($q);

$jenis = isset($data['tubel_ibel']) ? strtolower(trim($data['tubel_ibel'])) : 'tubel';
if ($jenis !== 'ibel' && $jenis !== 'tubel') {
    $jenis = 'tubel';
}

$nip = $data['nip'];

// WAJIB: URL absolut dari root project
$APP_URL = '/tubelapp'; // sesuaikan kalau nama folder project beda
$folderBaseNip = $APP_URL . "/uploads/" . $jenis . "/" . $nip . "/";



$docs = [
    'Surat Pengantar'        => $data['surat_pengantar'],
    'Surat LOA'              => $data['surat_loa'],
    'Surat Penerimaan Beasiswa' => $data['surat_penerimabeasiswa'],
    'Kalender Akademik'      => $data['kalender_akademik'],
    'Surat Akreditasi'       => $data['surat_akreditasi'],
    'Surat Jasmani'          => $data['surat_jasmani'],
    'Surat Rohani'           => $data['surat_rohani'],
    'Ijazah Terakhir'        => $data['ijazah_terakhir'],
    'Transkrip Nilai'        => $data['transkrip_nilai'],
    'SKP N-1'                => $data['skp_1'],
    'SKP N-2'                => $data['skp_2'],
    'Surat Hukdis'           => $data['surat_hukdis'],
    'Rekom Ibel'             => $data['surat_rekomibel'],
    'Pernyataan Ibel'        => $data['surat_pernyataanibel'],
    'Surat Dimana Saja'      => $data['surat_dimanasaja'],
];
?>

<?php $header = "- Lihat Dokumen"; include 'header.php'; ?>

<div class="container">
    <div class="card my-4">
        <div class="card-header text-center">
            <strong>Dokumen Persyaratan</strong><br>
            <?= htmlspecialchars($data['nama']); ?> (<?= htmlspecialchars($data['nip']); ?>)
        </div>

        <div class="card-body">
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>Dokumen</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($docs as $label => $file): ?>
                    <tr>
                        <td><?= htmlspecialchars($label); ?></td>
                        <td>
                            <?php if ($file): ?>
                            <?php
                                $namaFile = basename($file);
                                $urlFile  = $folderBaseNip . rawurlencode($namaFile);
                            ?>
                            <a href="<?= $urlFile ?>" target="_blank" class="btn btn-sm btn-success">View</a>
                        <?php else: ?>
                            <span class="text-muted">Tidak ada</span>
                        <?php endif; ?>

                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <div class="text-center mt-3">
                <a href="verifikasi.php" class="btn btn-primary">
                    Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
