<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['nip'])) {
    header("Location: login.php");
    exit;
}


$header = "- Tahap Pendaftaran";
include 'header.php';

?>
<?php
$flash_status  = $_SESSION['flash_status'] ?? null;
$flash_message = $_SESSION['flash_message'] ?? null;

if ($flash_status && $flash_message):
  unset($_SESSION['flash_status'], $_SESSION['flash_message']);
?>
<script>
document.addEventListener('DOMContentLoaded', function () {
  Swal.fire({
    icon: <?= json_encode($flash_status === 'success' ? 'success' : 'error') ?>,
    title: <?= json_encode($flash_status === 'success' ? 'Berhasil' : 'Gagal') ?>,
    text: <?= json_encode($flash_message) ?>,
    timer: 1800,
    showConfirmButton: false,
    allowOutsideClick: false,
    allowEscapeKey: false
  });
});
</script>
<?php endif; ?>

<div class="container">
    <div class="card my-2 o-hidden border-0 shadow-lg">
        <h3 class="card-header text-center">Daftar Pengajuan Tugas/Izin Belajar</h3>

        <div class="card-body container">
            <table class="table table-bordered compact table-fixed text-center" id="dataTable">
                <colgroup>
                    <col style="width: 10%">
                    <col style="width: 15%">
                    <col style="width: 20%">
                    <col style="width: 15%">
                    <col style="width: 10%">
                    <col style="width: 10%">
                    <col style="width: 25%">
                </colgroup>

                <thead class="thead-light">
                    <tr>
                        <th>NO</th>
                        <th>TANGGAL SUBMIT</th>
                        <th>NIP/NAMA</th>
                        <th>PENGAJUAN</th>
                        <th>STATUS</th> 
                        <th>ACTION</th>
                        <th>UPLOAD SK</th>
                    </tr>
                </thead>

                <tbody>
                <?php
                $sql = "SELECT a.*, p.* 
                        FROM pendaftaran a 
                        INNER JOIN mr_pegawai p ON p.nip = a.nip 
                        WHERE a.tgl_submit IS NOT NULL 
                        ORDER BY a.id DESC";
                $query = mysqli_query($db, $sql);

                if ($query && mysqli_num_rows($query) > 0) {
                    $no = 1;
                    while ($data = mysqli_fetch_assoc($query)) {

                        $skFile     = trim($data['sk'] ?? '');
                        $tglSubmit  = $data['tgl_submit'] ?? null;
                        $tglVerif   = $data['tgl_verifikasi'] ?? null;
                        $hasilVerif = trim($data['hasil_verifikasi'] ?? '');
                        $statusUsul = trim($data['status_usul'] ?? '');
                        $jenis      = (($data['tubel_ibel'] ?? '') === 'tubel') ? 'Tugas Belajar' : 'Izin Belajar';
                        $stNow = trim($data['status_tampil'] ?? '');
                            if ($stNow === '') {
                            $stNow = trim($data['status_usul'] ?? '');
                            }
                        // ===== STATUS Usul (ADMIN) =====
                        // treat legacy like "TMS Ditolak/TMS Diperbaiki" as TMS
                        $isTMS = (strpos($statusUsul, 'TMS') === 0);

                        if ($isTMS) {

                            // daftar semua poin yang mungkin diverifikasi
                            $poin = [
                                'RPKASN'            => ['status' => 'surat_rpkasn_hasil',          'alasan' => 'surat_rpkasn_alasan'],
                                'Surat Pengantar'   => ['status' => 'surat_pengantar_hasil',       'alasan' => 'surat_pengantar_alasan'],
                                'LOA'               => ['status' => 'surat_loa_hasil',             'alasan' => 'surat_loa_alasan'],
                                'Penerima Beasiswa' => ['status' => 'surat_penerimabeasiswa_hasil','alasan' => 'surat_penerimabeasiswa_alasan'],
                                'Kalender Akademik' => ['status' => 'kalender_akademik_hasil',     'alasan' => 'kalender_akademik_alasan'],
                                'Akreditasi'        => ['status' => 'surat_akreditasi_hasil',      'alasan' => 'surat_akreditasi_alasan'],
                                'Sehat Jasmani'     => ['status' => 'surat_jasmani_hasil',         'alasan' => 'surat_jasmani_alasan'],
                                'Sehat Rohani'      => ['status' => 'surat_rohani_hasil',          'alasan' => 'surat_rohani_alasan'],
                                'Ijazah Terakhir'   => ['status' => 'ijazah_terakhir_hasil',       'alasan' => 'ijazah_terakhir_alasan'],
                                'Transkrip Nilai'   => ['status' => 'transkrip_nilai_hasil',       'alasan' => 'transkrip_nilai_alasan'],
                                'SKP 1'             => ['status' => 'skp_1_hasil',                 'alasan' => 'skp_1_alasan'],
                                'SKP 2'             => ['status' => 'skp_2_hasil',                 'alasan' => 'skp_2_alasan'],
                                'Hukdis'            => ['status' => 'surat_hukdis_hasil',          'alasan' => 'surat_hukdis_alasan'],
                                'Berhenti Struktural'=>['status'=> 'surat_berhentistruktural_hasil','alasan'=>'surat_berhentistruktural_alasan'],
                                'Berhenti JF'       => ['status' => 'surat_berhentiJF_hasil',      'alasan' => 'surat_berhentiJF_alasan'],
                                'Surat Pernyataan Bersedia ditempatkan dimana saja' => ['status' => 'surat_dimanasaja_hasil', 'alasan' => 'surat_dimanasaja_alasan'],
                                'Rekom Ibel'        => ['status' => 'surat_rekomibel_hasil',       'alasan' => 'surat_rekomibel_alasan'],
                                'Pernyataan Ibel'   => ['status' => 'surat_pernyataanibel_hasil',  'alasan' => 'surat_pernyataanibel_alasan'],
                            ];

                            // [DIUBAH] Hapus total konsep kritikal & ditolak
                            $alasanArray = [];

                            foreach ($poin as $label => $f) {
                                $st = strtoupper(trim($data[$f['status']] ?? ''));
                                if ($st === 'TMS') {
                                    $als = trim($data[$f['alasan']] ?? '');
                                    $alasanArray[] = ($als !== '') ? "{$label}: {$als}" : "{$label}: -";
                                }
                            }

                            // [DIUBAH] TMS cukup "TMS" + alasan (tanpa Ditolak/Diperbaiki)
                            if (empty($alasanArray)) {
                                $statusUsul = "TMS";
                            } else {
                                $statusUsul = "TMS\n- " . implode("\n- ", $alasanArray);
                            }

                        } elseif (!empty($tglSubmit) && empty($tglVerif)) {
                            $statusUsul = 'Proses Verifikasi';
                        } elseif ($hasilVerif === 'Lulus Verifikasi' && $skFile === '') {
                            $statusUsul = 'Sudah diverifikasi, Proses Penerbitan SK';
                        }

                        // ===== STYLE BADGE STATUS (BKG DI BADGE SAJA, BUKAN TD) =====
                        $badgeStyle = "";
                        if ($statusUsul === 'Proses Verifikasi') {
                            $badgeStyle = "background:#FF2E93; color:#fff;";
                        } elseif ($statusUsul === 'Sudah diverifikasi, Proses Penerbitan SK') {
                            $badgeStyle = "background:#81D4FA; color:#000;";
                        } elseif (strpos($statusUsul, 'TMS') === 0) {
                            $badgeStyle = "background:#EF5350; color:#fff;";
                        } elseif ($statusUsul === 'Selesai') {
                            $badgeStyle = "background:#2E7D32; color:#fff;";
                        } else {
                            $badgeStyle = "background:#e9ecef; color:#000;";
                        }

                        $canUpload = ($statusUsul === 'Sudah diverifikasi, Proses Penerbitan SK');
                ?>
                    <tr>
                        <td><?= $no++; ?></td>

                        <td <?php if ($statusUsul=='Proses Verifikasi' || $statusUsul=='Sudah diverifikasi, Proses Penerbitan SK') { echo 'class="countdown"'; } ?> data-tgl="<?= htmlspecialchars($data['tgl_submit'] ?? ''); ?>">
                            <?= htmlspecialchars($data['tgl_submit'] ?? '-'); ?>
                        </td>

                        <td><?= htmlspecialchars($data['nip']); ?><br/><?= htmlspecialchars($data['nama']); ?></td>
                        <td><?= htmlspecialchars($jenis); ?></td>

                        <!-- STATUS (TMS termasuk alasannya) -->
                        <td>
                            <?php
                                $lines  = preg_split("/\r\n|\n|\r/", (string)$statusUsul);
                                $label  = $lines[0] ?? '';
                                $detail = array_slice($lines, 1);
                            ?>

                            <span style="<?= $badgeStyle; ?> padding:4px 10px; border-radius:8px; font-weight:700; display:inline-block;">
                                <?= htmlspecialchars($label); ?>
                            </span>

                            <?php if (!empty($detail)): ?>
                                <div style="margin-top:6px; font-size:0.9rem; text-align:left; line-height:1.4;">
                                    <?= nl2br(htmlspecialchars(implode("\n", $detail))); ?>
                                </div>
                            <?php endif; ?>
                        </td>

                       <!-- ACTION -->
                            <?php $id_daftar = (int)($data['id'] ?? 0); ?>
                            <td>
                            <?php
                                // label status yang tampil (baris pertama)
                                $lines  = preg_split("/\r\n|\n|\r/", (string)$statusUsul);
                                $labelStatus = trim($lines[0] ?? '');

                                $isTMSRow = (stripos($labelStatus, 'TMS') === 0);
                            ?>

                            <?php if ($labelStatus === 'Selesai'): ?>
                                <a href="lihat_dokumen.php?iddaftar=<?= urlencode($data['id']); ?>"
                                class="btn btn-sm btn-success">
                                    Lihat Dokumen
                                </a>
                            <?php else: ?>
                                <a href="verifikasi_detail.php?iddaftar=<?= urlencode($data['id']); ?>"
                                class="btn btn-sm <?= $isTMSRow ? 'btn-warning' : 'btn-primary'; ?>">
                                    <?= $isTMSRow ? 'Verifikasi Ulang' : 'Verifikasi'; ?>
                                </a>
                            <?php endif; ?>
                            </td>



                        <!-- UPLOAD SK -->
                        <td>
                        <?php if ($skFile !== ''): ?>
                            <div class="mb-1">
                                <span class="text-success font-weight-bold">SK sudah diupload</span>
                            </div>

                            <a class="btn btn-sm btn-success" target="_blank"
                               href="/tubelapp/view_sk.php?iddaftar=<?= urlencode($data['id']); ?>">
                                View
                            </a>

                           <form class="mt-2 js-confirm-upload-sk"
                                data-confirm-text="Upload ulang akan mengganti SK yang lama. Lanjutkan?"
                                action="/tubelapp/proses/upload_sk_proses.php"
                                method="post"
                                enctype="multipart/form-data">
                                <input type="hidden" name="iddaftar" value="<?= htmlspecialchars($data['id']); ?>">
                                <input type="file" name="file_sk" accept=".pdf,.jpg,.jpeg,.png" required
                                       class="form-control form-control-sm"
                                       onchange="setLocalPreview(this, 'prev_<?= $data['id']; ?>', 'btnprev_<?= $data['id']; ?>')">

                                <div class="mt-1">
                                    <button type="button" id="btnprev_<?= $data['id']; ?>" class="btn btn-sm btn-info d-none"
                                            onclick="openLocalPreview('prev_<?= $data['id']; ?>')">Preview (Lokal)</button>
                                    <button type="submit" class="btn btn-sm btn-warning">Upload Ulang</button>
                                </div>
                                <input type="hidden" id="prev_<?= $data['id']; ?>" value="">
                            </form>

                        <?php elseif ($canUpload): ?>
                            <form action="/tubelapp/proses/upload_sk_proses.php" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="iddaftar" value="<?= htmlspecialchars($data['id']); ?>">
                                <input type="file" name="file_sk" accept=".pdf,.jpg,.jpeg,.png" required
                                       class="form-control form-control-sm"
                                       onchange="setLocalPreview(this, 'prev_<?= $data['id']; ?>', 'btnprev_<?= $data['id']; ?>')">

                                <div class="mt-1">
                                    <button type="button" id="btnprev_<?= $data['id']; ?>" class="btn btn-sm btn-info d-none"
                                            onclick="openLocalPreview('prev_<?= $data['id']; ?>')">Preview</button>
                                    <button type="submit" class="btn btn-sm btn-primary">Upload SK</button>
                                </div>
                                <input type="hidden" id="prev_<?= $data['id']; ?>" value="">
                            </form>

                        <?php else: ?>
                            <span class="text-danger font-weight-bold">-</span>
                        <?php endif; ?>
                        </td>
                    </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='8' class='text-center'>Belum ada data!</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function setLocalPreview(fileInput, hiddenId, buttonId) {
    const f = fileInput.files && fileInput.files[0];
    const hidden = document.getElementById(hiddenId);
    const btn = document.getElementById(buttonId);

    if (!f) {
        hidden.value = '';
        btn.classList.add('d-none');
        return;
    }

    const allowed = ['application/pdf','image/jpeg','image/png'];
    if (!allowed.includes(f.type)) {
        alert('Format tidak didukung untuk preview. Gunakan PDF/JPG/PNG.');
        fileInput.value = '';
        hidden.value = '';
        btn.classList.add('d-none');
        return;
    }

    if (hidden.value) {
        URL.revokeObjectURL(hidden.value);
    }

    const url = URL.createObjectURL(f);
    hidden.value = url;
    btn.classList.remove('d-none');
}

function openLocalPreview(hiddenId) {
    const hidden = document.getElementById(hiddenId);
    const url = hidden && hidden.value ? hidden.value : '';
    if (!url) {
        alert('File belum dipilih.');
        return;
    }
    window.open(url, '_blank');
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  document.addEventListener('submit', function (e) {
    const form = e.target;
    if (!form.classList.contains('js-confirm-upload-sk')) return;

    e.preventDefault();

    const msg = form.getAttribute('data-confirm-text') || 'Lanjutkan?';

    Swal.fire({
      icon: 'question',
      title: 'Konfirmasi',
      text: msg,
      showCancelButton: true,
      confirmButtonText: 'Ya, Upload',
      cancelButtonText: 'Batal',
      allowOutsideClick: false,
      allowEscapeKey: false
    }).then((res) => {
      if (res.isConfirmed) form.submit();
    });
  });
});
</script>

<?php include 'footer.php'; ?>

<script>
function updateCountdowns() {
    const countdownElements = document.querySelectorAll('.countdown');

    countdownElements.forEach(el => {
        const tglUsul = el.getAttribute('data-tgl');
        if (!tglUsul) return;

        const startDate = new Date(tglUsul).getTime();
        const targetDate = startDate + (3 * 24 * 60 * 60 * 1000);
        const now = new Date().getTime();
        let distance = targetDate - now;

        if (distance < 0) {
            el.innerHTML = `${tglUsul}<br><small>Waktu Habis</small>`;
            return;
        }

        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        el.innerHTML = `${tglUsul}<br><small>${days}d ${hours}h ${minutes}m ${seconds}s</small>`;
    });
}

setInterval(updateCountdowns, 1000);
updateCountdowns();
</script>
