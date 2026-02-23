<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['nip'])) {
    header("Location: login.php");
    exit;
}

$header = "- Data Usul Tubel";
include 'header.php';

// ===== FLASH UPLOAD (opsional, kalau memang dipakai) =====
if (
  isset($_GET['flash_upload']) && $_GET['flash_upload'] === '1' &&
  isset($_SESSION['upload_status'], $_SESSION['upload_message'])
) {
  $st  = $_SESSION['upload_status'];
  $msg = $_SESSION['upload_message'];
  unset($_SESSION['upload_status'], $_SESSION['upload_message']);
?>
<script>
document.addEventListener('DOMContentLoaded', function () {
  Swal.fire({
    icon: <?= json_encode($st === 'success' ? 'success' : 'error') ?>,
    title: <?= json_encode($st === 'success' ? 'Berhasil' : 'Gagal') ?>,
    text: <?= json_encode($msg) ?>,
    timer: 1800,
    showConfirmButton: false,
    allowOutsideClick: false
  });

  const url = new URL(window.location.href);
  url.searchParams.delete('flash_upload');
  history.replaceState({}, document.title, url.toString());
});
</script>
<?php }




$nip = $_SESSION['nip'];

// Status tampil user: berdasarkan fase (tgl_submit, tgl_verifikasi, hasil_verifikasi, sk)
$nip_safe = mysqli_real_escape_string($db, $nip);

$sql = "
    SELECT 
        a.*,
        mp.nama_prodi AS nama_prodi,
        mk.nama_kampus AS nama_kampus,
        CASE
            WHEN a.tgl_submit IS NULL THEN 'Proses pendaftaran'
            WHEN a.tgl_submit IS NOT NULL AND a.tgl_verifikasi IS NULL THEN 'Proses verifikasi'
            WHEN (a.status_usul LIKE 'TMS%' OR a.hasil_verifikasi = 'Tidak Lulus Verifikasi') THEN 'TMS'
            WHEN (a.hasil_verifikasi = 'Lulus Verifikasi' AND (a.sk IS NULL OR a.sk = '')) THEN 'Sudah diverifikasi, Proses Penerbitan SK'
            WHEN (a.sk IS NOT NULL AND a.sk <> '') THEN 'Selesai'
            ELSE COALESCE(a.status_usul, 'Proses Verifikasi')
        END AS status_tampil
    FROM pendaftaran a
    LEFT JOIN mr_prodi  mp ON mp.id = a.id_prodi
    LEFT JOIN mr_kampus mk ON mk.id = mp.id_kampus
    WHERE a.nip = '$nip_safe'
    ORDER BY a.id DESC
";

$query = mysqli_query($db, $sql);
?>

<div class="container">
    <div class="card my-2 card-table-wide">
        <h3 class="card-header text-center">Data Usul Tugas Belajar/Izin Belajar</h3>
        <div class="card-body">

            <table class="table table-bordered compact table-fixed text-center" id="dataTable">
                <colgroup>
                    <col style="width: 60px">
                    <col style="width: 15%">
                    <col style="width: 15%">
                    <col style="width: 15%">
                    <col style="width: 15%">
                    <col style="width: 10%">
                    <col style="width: 25%">
                </colgroup>

                <thead class="thead-light">
                    <tr>
                        <th class="col-no">NO</th>
                        <th>TANGGAL PENGAJUAN</th>
                        <th>JENIS PENGAJUAN</th>
                        <th>KAMPUS & PRODI</th>
                        <th>STATUS</th>
                        <th>SK</th>
                        <th>ACTION</th>
                    </tr>
                </thead>

                <tbody>
<?php
if ($query && mysqli_num_rows($query) > 0) {
    $no = 1;
    while ($data = mysqli_fetch_assoc($query)) {

        $jenis = (($data['tubel_ibel'] ?? '') === 'tubel') ? 'Tugas Belajar' : 'Izin Belajar';
        $urlUpload = (($data['tubel_ibel'] ?? '') === 'tubel') ? 'daftar_upload.php' : 'ibel_upload.php';

        // Gabung Kampus - Prodi (di PHP)
        $kampus = trim($data['nama_kampus'] ?? '');
        $prodi  = trim($data['nama_prodi'] ?? '');

        if ($kampus !== '' && $prodi !== '') {
            $jenisprodi = $kampus . ' - ' . $prodi;
        } elseif ($prodi !== '') {
            $jenisprodi = $prodi;
        } elseif ($kampus !== '') {
            $jenisprodi = $kampus;
        } else {
            $jenisprodi = '-';
        }

        $skFile       = trim($data['sk'] ?? '');
        $statusTampil = trim($data['status_tampil'] ?? '');

        // ===== ALASAN TMS (USER) =====
        $alasanTMSUser = '';

        // ===== STATUS SEKARANG (UTAMA UNTUK TAMPIL) =====
        $stNow = $statusTampil;
        if ($stNow === '') {
            $stNow = trim($data['status_usul'] ?? '');
        }

        // Jika status (termasuk legacy "TMS Ditolak/Diperbaiki") => paksa label jadi "TMS"
        if (strpos($stNow, 'TMS') === 0) {
            $stNow = 'TMS';

            // Bangun alasan TMS berdasarkan poin yang TMS
            $poinTMS = [
                'RPKASN'            => ['status' => 'surat_rpkasn_hasil',            'alasan' => 'surat_rpkasn_alasan'],
                'Surat Pengantar'   => ['status' => 'surat_pengantar_hasil',         'alasan' => 'surat_pengantar_alasan'],
                'LOA'               => ['status' => 'surat_loa_hasil',               'alasan' => 'surat_loa_alasan'],
                'Penerima Beasiswa' => ['status' => 'surat_penerimabeasiswa_hasil',  'alasan' => 'surat_penerimabeasiswa_alasan'],
                'Kalender Akademik' => ['status' => 'kalender_akademik_hasil',       'alasan' => 'kalender_akademik_alasan'],
                'Akreditasi'        => ['status' => 'surat_akreditasi_hasil',        'alasan' => 'surat_akreditasi_alasan'],
                'Sehat Jasmani'     => ['status' => 'surat_jasmani_hasil',           'alasan' => 'surat_jasmani_alasan'],
                'Sehat Rohani'      => ['status' => 'surat_rohani_hasil',            'alasan' => 'surat_rohani_alasan'],
                'Ijazah Terakhir'   => ['status' => 'ijazah_terakhir_hasil',         'alasan' => 'ijazah_terakhir_alasan'],
                'Transkrip Nilai'   => ['status' => 'transkrip_nilai_hasil',         'alasan' => 'transkrip_nilai_alasan'],
                'SKP N-1'           => ['status' => 'skp_1_hasil',                   'alasan' => 'skp_1_alasan'],
                'SKP N-2'           => ['status' => 'skp_2_hasil',                   'alasan' => 'skp_2_alasan'],
                'Hukdis'            => ['status' => 'surat_hukdis_hasil',            'alasan' => 'surat_hukdis_alasan'],
                'Berhenti Struktural' => ['status' => 'surat_berhentistruktural_hasil', 'alasan' => 'surat_berhentistruktural_alasan'],
                'Berhenti JF'       => ['status' => 'surat_berhentiJF_hasil',        'alasan' => 'surat_berhentiJF_alasan'],
                'Surat Pernyataan Bersedia ditempatkan dimana saja' => ['status' => 'surat_dimanasaja_hasil', 'alasan' => 'surat_dimanasaja_alasan'],
                'Rekom Ibel'        => ['status' => 'surat_rekomibel_hasil',         'alasan' => 'surat_rekomibel_alasan'],
                'Pernyataan Ibel'   => ['status' => 'surat_pernyataanibel_hasil',    'alasan' => 'surat_pernyataanibel_alasan'],
            ];

            $arr = [];
            foreach ($poinTMS as $label => $f) {
                $st = strtoupper(trim($data[$f['status']] ?? ''));
                if ($st === 'TMS') {
                    $als = trim($data[$f['alasan']] ?? '');
                    $arr[] = ($als !== '') ? "{$label}: {$als}" : "{$label}: -";
                }
            }

            $alasanTMSUser = !empty($arr) ? "- " . implode("\n- ", $arr) : '';
        }

        // ===== STYLE BADGE STATUS (USER) =====
        $badgeStyleUser = "";
        $stBadge = $stNow;

       if (strpos($stBadge, 'TMS') === 0) {
                $badgeStyleUser = "background:#EF5350; color:#fff;";

            } elseif ($stBadge === 'Proses verifikasi') {
                $badgeStyleUser = "background:#FF2E93; color:#fff;";
            } elseif ($stBadge === 'Pendaftaran' || $stBadge === 'Proses pendaftaran') {
                $badgeStyleUser = "background:#FFA726; color:#000;";
            } elseif ($stBadge === 'Sudah diverifikasi, Proses Penerbitan SK') {
                $badgeStyleUser = "background:#81D4FA; color:#000;";
            } elseif ($stBadge === 'Selesai') {
                $badgeStyleUser = "background:#2E7D32; color:#fff;";
            } else {
                $badgeStyleUser = "background:#e9ecef; color:#000;";
            }



        // ===== ATURAN TOMBOL: BOLEH EDIT/UPLOAD/SUBMIT/DELETE jika belum submit ATAU TMS =====
        $belumSubmit = (($data['tgl_submit'] ?? null) === null);
        $isTMS       = ($stNow === 'TMS');

?>
    <tr>
        <td class="col-no"><?= $no++; ?></td>
        <td><?= htmlspecialchars($data['tgl_usul'] ?? '-'); ?></td>
        <td><?= htmlspecialchars($jenis); ?></td>
        <td><?= htmlspecialchars($jenisprodi); ?></td>

        <td>
            <span style="<?= $badgeStyleUser; ?> padding:4px 10px; border-radius:8px; font-weight:700; display:inline-block;">
                <?= htmlspecialchars($stNow); ?>
            </span>

            <?php if (strpos($stNow, 'TMS') === 0 && $alasanTMSUser !== ''): ?>
                <div style="margin-top:6px; font-size:0.9rem; text-align:left; line-height:1.4;">
                    <?= nl2br(htmlspecialchars($alasanTMSUser)); ?>
                </div>
            <?php endif; ?>
        </td>

        <td>
            <?php if ($skFile === '') { ?>
                Belum ada
            <?php } else { ?>
                <a class="btn btn-sm btn-success"
                   href="download_sk.php?iddaftar=<?= urlencode($data['id']); ?>"
                   target="_blank">
                    Download SK
                </a>
            <?php } ?>
        </td>

       <td>
    <?php if ($belumSubmit) { ?>
        <a href="detail.php?iddaftar=<?= urlencode($data['id']); ?>" class="btn btn-sm btn-warning">Edit</a>
        <a href="<?= htmlspecialchars($urlUpload); ?>?iddaftar=<?= urlencode($data['id']); ?>" class="btn btn-sm btn-info">Upload</a>

<!--        <a href="submit.php?iddaftar=<?= urlencode($iddaftar); ?>" -->
        <a href="submit.php?iddaftar=<?= urlencode($data['id']); ?>"
        class="btn btn-primary"
        id="btnSubmitPendaftaran">
        Submit
        </a>


        <a href="delete.php?iddaftar=<?= urlencode($data['id']); ?>"
        class="btn btn-sm btn-danger"
        onclick="return confirmDelete(event, this.href);">
            Delete
        </a>


    <?php } elseif ($isTMS) { ?>
        <a href="detail.php?iddaftar=<?= urlencode($data['id']); ?>&alasantms=<?=$alasanTMSUser; ?>" class="btn btn-sm btn-warning">Edit</a>
        <a href="delete.php?iddaftar=<?= urlencode($data['id']); ?>"
        class="btn btn-sm btn-danger"
        onclick="return confirmDelete(event, this.href);">
            Delete
        </a>

    <?php } else { ?>
        <a href="cetak_status.php?iddaftar=<?= urlencode($data['id']); ?>" class="btn btn-sm btn-success">
            Bukti Pengajuan
        </a>
    <?php } ?>
</td>

    </tr>
<?php
    }
} else {
    echo "<tr><td colspan='7' class='text-center'>Belum ada data!</td></tr>";
}
?>
                </tbody>
            </table>

            <div class="mb-3 text-center d-grid gap-md-2 mx-auto">
                <button class="btn btn-sm btn-primary" onclick="window.location.href='pendaftaran.php';">Daftar Baru</button>
            </div>

        </div>
    </div>
</div>

<?php
// [DITAMBAH] SweetAlert untuk notifikasi hasil aksi (delete dll)
$flash_status  = $_SESSION['flash_status'] ?? null;
$flash_message = $_SESSION['flash_message'] ?? null;

if ($flash_status && $flash_message):
    unset($_SESSION['flash_status'], $_SESSION['flash_message']);
?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const status = <?= json_encode($flash_status) ?>;
    const msg    = <?= json_encode($flash_message) ?>;

    Swal.fire({
        icon: status === 'success' ? 'success' : 'error',
        title: status === 'success' ? 'Berhasil' : 'Gagal',
        text: msg,
        confirmButtonText: 'OK',
        allowOutsideClick: false,
        allowEscapeKey: false
    });
});
</script>
<?php endif; ?>

<script>
function confirmDelete(e, url) { // [DITAMBAH]
    e.preventDefault();

    Swal.fire({
        icon: 'warning',
        title: 'Konfirmasi Hapus',
        text: 'Yakin ingin menghapus data pendaftran ini?',
        showCancelButton: true,      // [DITAMBAH] tombol kedua
        confirmButtonText: 'Ya',     // [DITAMBAH]
        cancelButtonText: 'Tidak',   // [DITAMBAH]
        reverseButtons: true,
        allowOutsideClick: false,
        allowEscapeKey: false
    }).then((result) => {
        if (result.isConfirmed) {    // klik "Ya"
            window.location.href = url;
        }
        // klik "Tidak" -> otomatis tutup, tidak melakukan apa-apa
    });

    return false; // [DITAMBAH] supaya href tidak jalan
}
</script>

<script>
function confirmSubmit(e, url) { // [DITAMBAH]
    e.preventDefault();

    Swal.fire({
        icon: 'info',
        title: 'Submit',
        text: 'Yakin ingin submit data pendaftran ini?',
        showCancelButton: true,      // [DITAMBAH] tombol kedua
        confirmButtonText: 'Ya',     // [DITAMBAH]
        cancelButtonText: 'Tidak',   // [DITAMBAH]
        reverseButtons: true,
        allowOutsideClick: false,
        allowEscapeKey: false
    }).then((result) => {
        if (result.isConfirmed) {    // klik "Ya"
            window.location.href = url;
        }
        // klik "Tidak" -> otomatis tutup, tidak melakukan apa-apa
    });

    return false; // [DITAMBAH] supaya href tidak jalan
}
</script>
<?php
// [DITAMBAH] SweetAlert khusus untuk pesan HTML + tombol "Lengkapi" ke halaman upload
$fh_icon  = $_SESSION['flash_html_icon']  ?? null; // [DITAMBAH]
$fh_title = $_SESSION['flash_html_title'] ?? null; // [DITAMBAH]
$fh_body  = $_SESSION['flash_html_body']  ?? null; // [DITAMBAH]
$fh_next  = $_SESSION['flash_html_next']  ?? null; // [DITAMBAH]

if ($fh_icon && $fh_title && $fh_body && $fh_next): // [DITAMBAH]
    unset($_SESSION['flash_html_icon'], $_SESSION['flash_html_title'], $_SESSION['flash_html_body'], $_SESSION['flash_html_next']); // [DITAMBAH]
?>
<script>
document.addEventListener('DOMContentLoaded', function () { // [DITAMBAH]
    const icon  = <?= json_encode($fh_icon) ?>;  // [DITAMBAH]
    const title = <?= json_encode($fh_title) ?>; // [DITAMBAH]
    const html  = <?= json_encode($fh_body) ?>;  // [DITAMBAH]
    const next  = <?= json_encode($fh_next) ?>;  // [DITAMBAH]

    Swal.fire({ // [DITAMBAH]
        icon: icon, // [DITAMBAH]
        title: title, // [DITAMBAH]
        html: html, // [DITAMBAH]
        showCancelButton: true, // [DITAMBAH]
        confirmButtonText: 'Lengkapi', // [DITAMBAH]
        cancelButtonText: 'Tutup', // [DITAMBAH]
        reverseButtons: true, // [DITAMBAH]
        allowOutsideClick: false, // [DITAMBAH]
        allowEscapeKey: false // [DITAMBAH]
    }).then((result) => { // [DITAMBAH]
        if (result.isConfirmed) { // [DITAMBAH]
            window.location.href = next; // [DITAMBAH]
        } // [DITAMBAH]
    }); // [DITAMBAH]
});
</script>
<?php endif; ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const btn = document.getElementById('btnSubmitPendaftaran');
  if (!btn) return;

  btn.addEventListener('click', function (e) {
    e.preventDefault(); // stop link default

    Swal.fire({
      icon: 'question',
      title: 'Konfirmasi Submit',
      text: 'Yakin ingin submit pendaftaran? Jika sudah disubmit Anda tidak dapat merubah data dan berkas yang sudah di upload',
      showCancelButton: true,
      confirmButtonText: 'Oke',
      cancelButtonText: 'Batal',
      allowOutsideClick: false
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = btn.getAttribute('href'); // lanjut ke submit.php
      }
    });
  });
});
</script>


<?php include 'footer.php'; ?>