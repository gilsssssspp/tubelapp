<?php
session_start();
include 'koneksi.php';
include 'header.php';

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

  // biar tidak muncul lagi saat refresh/back
  const url = new URL(window.location.href);
  url.searchParams.delete('flash_upload');
  history.replaceState({}, document.title, url.toString());
});
</script>
<?php 
} 



if (!isset($_SESSION['nip'])) {
    echo "<script>alert('Silahkan Login Terlebih Dahulu!'); window.location='login.php';</script>";
    exit;
}

$nip      = $_SESSION['nip'];
$iddaftar = $_GET['iddaftar'] ?? '';
$origin   = $_GET['origin'] ?? '';
  

if ($iddaftar === '') {
    echo "<script>alert('ID daftar kosong'); history.back();</script>";
    exit;
}

// Ambil data pendaftaran
$stmt = mysqli_prepare($db, "SELECT * FROM pendaftaran WHERE id=? AND nip=? LIMIT 1");
mysqli_stmt_bind_param($stmt, "ss", $iddaftar, $nip);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

if (!$res || mysqli_num_rows($res) === 0) {
    echo "<script>alert('Data pendaftaran tidak ditemukan'); history.back();</script>";
    exit;
}

$row = mysqli_fetch_assoc($res);

// Jenis folder berdasar pengajuan (tubel/ibel)
$jenis = strtolower(trim($row['tubel_ibel'] ?? 'tubel'));
if (!in_array($jenis, ['tubel','ibel'], true)) $jenis = 'tubel';

// Folder server untuk VIEW (samakan dengan proses upload)
$folderBaseNip = "uploads/{$jenis}/{$nip}/";

// Daftar dokumen (TUBEL) - sesuaikan dengan kolom DB Anda
$docs = [
  ['label' => 'Surat Pengantar',             'col' => 'surat_pengantar',          'required' => true],
  ['label' => 'Bukti penerimaan perguruan tinggi (LOA)',            'col' => 'surat_loa',                'required' => true],
  ['label' => 'Surat Penerimaan Beasiswa',   'col' => 'surat_penerimabeasiswa',   'required' => true],
  ['label' => 'Kalender Akademik',           'col' => 'kalender_akademik',        'required' => true],
  ['label' => 'Surat Akreditasi',            'col' => 'surat_akreditasi',         'required' => true],
  ['label' => 'SKP N-2',                     'col' => 'skp_1',                    'required' => true],
  ['label' => 'SKP N-1',                     'col' => 'skp_2',                    'required' => true],
  ['label' => 'Surat Sehat Jasmani',         'col' => 'surat_jasmani',            'required' => true],
  ['label' => 'Surat Sehat Rohani',          'col' => 'surat_rohani',             'required' => true],
  ['label' => 'Ijazah Terakhir',             'col' => 'ijazah_terakhir',          'required' => true],
  ['label' => 'Transkrip Nilai',             'col' => 'transkrip_nilai',          'required' => true],
  ['label' => 'Surat Bebas Hukdis',          'col' => 'surat_hukdis',             'required' => true],
  ['label' => 'Surat Pernyataan Bersedia ditempatkan dimana saja',        'col' => 'surat_dimanasaja',         'required' => true],
];

function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
?>

<style>
  .remark { color:#d00; font-weight:600; }
  .table td { vertical-align: middle; }
</style>

<div class="container">
  <h2 class="mb-3">Upload Dokumen Pendukung (<?=strtoupper(h($jenis))?>)</h2>
  
  <form class="card m-4 p-4 o-hidden border-0 shadow-lg"
        method="post"
        id="uploadForm"
        action="proses/proses_upload.php?iddaftar=<?=h($iddaftar)?>"
        enctype="multipart/form-data">

    <p style="color: red">
					Jenis file upload yang diperbolehkan .pdf atau .jpg<br/>
					Ukuran setiap file Maksimal 2 Mb<br/>
				</p>

				<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

				<p><b>Format Surat :</b></p>
				<ul>
					<li>Surat Usul Izin Belajar & Tugas Belajar :
						<strong>
							<a href="dokumen/TEMPLATE_Surat_Usul_Izin_Belajar&Tugas_Belajar.docx" download>
								<i class="bi bi-download"></i> Download
							</a>
						</strong>
					</li>

					<li>Surat Rekomendasi Mengikuti Seleksi Izin Belajar :
						<strong>
							<a href="dokumen/TEMPLATE_Surat_Rekomendasi_Mengikuti_Seleksi_mahasiswa_baru_dan_menjadi_peserta_program_gelar_Izin_Belajar.docx" download>
								<i class="bi bi-download"></i> Download
							</a>
						</strong>
					</li>

          <li>Surat Pernyataan Siap di Berhentikan :
						<strong>
							<a href="dokumen/TEMPLATE_Surat_Pernyataan_Bersedia_Diberhentikan_dari_Jabatan_Pimpinan_Tinggi_Pratama_Jabatan_Administrator_dan_Jabatan_Pengawas_Jabatan_Fungsional_Bagi_Pegawai_Tugas_Belajar.docx" download>
								<i class="bi bi-download"></i> Download
							</a>
						</strong>
					</li>

					<li>Surat Bebas Hukuman Disiplin :
						<strong>
							<a href="dokumen/TEMPLATE_Surat_Bebas_Hukuman_Disiplin.docx" download>
								<i class="bi bi-download"></i> Download
							</a>
						</strong>
					</li>

					<li>Surat Pernyataan Bersedia di Tempatkan Dimana Saja :
						<strong>
							<a href="dokumen/TEMPLATE_Surat_Pernyataan_Bersedia_Ditempatkan_DiMana_Saja_Pada_Unit_Kerja_Bagi_Pegawai_Tugas_Belajar.docx" download>
								<i class="bi bi-download"></i> Download
							</a>
						</strong>
					</li>
				</ul>
        
<?php
function cekTMSOtomatis($row){
    $hasil = [
        'status' => 'MS',
        'detail' => [] // simpan field + alasan
    ];

    foreach ($row as $field => $value) {

        // cek field berakhiran _hasil
        if (substr($field, -6) === '_hasil' && strtoupper($value) === 'TMS') {

            $hasil['status'] = 'TMS';

            // field alasan pasangan
            $fieldAlasan = str_replace('_hasil', '_alasan', $field);

            // nama dokumen (tanpa _hasil)
            $namaField = str_replace('_hasil', '', $field);
            $namaField = ucwords(str_replace('_', ' ', $namaField));

            $hasil['detail'][] = [
                'field'  => $namaField,
                'alasan' => $row[$fieldAlasan] ?? 'Tidak ada alasan'
            ];
        }
    }

    return $hasil;
}
?>


<?php
$query = mysqli_query($db, "SELECT * FROM pendaftaran WHERE id='$iddaftar'");
$row   = mysqli_fetch_assoc($query);

$cekTMS = cekTMSOtomatis($row);

$status = $cekTMS['status'];
?>



<?php if ($status === 'TMS') { ?>
<div style="
    padding:12px;
    border-left:5px solid #dc3545;
    background:#fdeaea;
    color:#842029;
">
    <strong>⚠ Dokumen Tidak Memenuhi Syarat (TMS)</strong>
    <ul style="margin-top:8px;padding-left:20px;">
        <?php foreach ($cekTMS['detail'] as $tms) { ?>
            <li>
                <strong><?= htmlspecialchars($tms['field']); ?></strong><br>
                <small><?= htmlspecialchars($tms['alasan']); ?></small>
            </li>
        <?php } ?>
    </ul>
</div>
<?php } ?>



	
    <div class="table-responsive">
      <table class="table table-bordered">
        <thead class="table-light">
          <tr>
            <th style="width:45%">Dokumen</th>
            <th style="width:35%">Pilih File</th>
            <th style="width:20%">Status</th>
          </tr>
        </thead>
        <tbody>

        <?php foreach ($docs as $i => $d):
          $col   = $d['col'];           // nama kolom DB = nama input file
          $label = $d['label'];
          $val   = trim($row[$col] ?? '');

          $hasServerFile = ($val !== '');
          $serverUrl = $hasServerFile ? ($folderBaseNip . rawurlencode(basename($val))) : '#';

          $idx = $col; // biar unik & stabil

          $statusId  = "status_"  . $idx;
          $previewId = "preview_" . $idx;
          $viewId    = "view_"    . $idx;
          $fileId    = "file_"    . $idx;
        ?>
          <tr>
            <td><?=h($label)?></td>

            	<td>
                <input
                id="<?=h($fileId)?>"
                type="file"
                name="<?=h($col)?>"
                class="form-control"
                accept=".pdf,.jpg,.jpeg,.png"
                data-status-id="<?=h($statusId)?>"
                data-preview-id="<?=h($previewId)?>"
                data-view-id="<?=h($viewId)?>"
                >
              </td>


            <td>
				<?php if ($hasServerFile): ?>
				<span id="<?=h($statusId)?>">Sudah upload</span>
				<?php else: ?>
				<span id="<?=h($statusId)?>" class="remark">Belum upload file</span>
				<?php endif; ?>

				<!-- Preview lokal (muncul saat pilih file) -->
				<a id="<?=h($previewId)?>" class="btn btn-sm btn-outline-primary ms-2" href="#" target="_blank" rel="noopener" style="display:none;">
				Preview
				</a>

				<!-- View server (muncul kalau file sudah tersimpan) -->
				<?php if ($hasServerFile): ?>
				<a id="<?=h($viewId)?>" class="btn btn-sm btn-primary ms-2" href="<?=h($serverUrl)?>" target="_blank" rel="noopener">
					View
				</a>
				<?php else: ?>
				<a id="<?=h($viewId)?>" class="btn btn-sm btn-primary ms-2" href="#" target="_blank" rel="noopener" style="display:none;">
					View
				</a>
				<?php endif; ?>
			</td>
          </tr>
        <?php endforeach; ?>

        </tbody>
      </table>
    </div>

    <!-- Hidden input untuk tracking dari mana form disubmit -->
    <input type="hidden" name="dari_submit" id="dari_submit" value="0">
    
    <?php if ($origin === 'detail'): ?>
    <input type="hidden" name="origin" value="detail">
    <?php endif; ?>

    <div class="text-center" style="margin-top:16px;">
        <a href="javascript:history.back()" class="btn btn-secondary">Kembali</a>
        <button type="submit" name="simpan" class="btn btn-success">Simpan</button>
        <button type="button" class="btn btn-primary" id="btnSubmitPendaftaran">Submit</button>
    </div>

  </form>
</div>

<script>
(function(){
  const MAX_SIZE = 2 * 1024 * 1024; 
  
  const inputs = document.querySelectorAll('input[type="file"][data-status-id][data-preview-id][data-view-id]');
  inputs.forEach(inp => {
    inp.addEventListener('change', function(){
      const statusEl  = document.getElementById(this.dataset.statusId);
      const previewEl = document.getElementById(this.dataset.previewId);

      // reset jika kosong
      if (!this.files || !this.files[0]) {
        if (statusEl) {
          statusEl.textContent = 'Belum upload file';
          statusEl.classList.add('remark');
        }
        if (previewEl) {
          previewEl.style.display = 'none';
          previewEl.href = '#';
        }
        return;
      }

      const file = this.files[0];

      if (file.size > MAX_SIZE) {
        // RESET SEBELUM popup tampil
        this.value = '';  // Reset file input

        // PAKSA Swal tampil segera
        Swal.fire({
          icon: 'warning',
          title: 'Ukuran File Terlalu Besar',
          text: 'Ukuran file maksimal 2 MB. Silakan kompres atau pilih file lain.',
          confirmButtonText: 'Oke',
          allowOutsideClick: false,
          allowEscapeKey: false
        });

        // Reset status & preview
        if (statusEl) {
          statusEl.textContent = 'Belum upload file';
          statusEl.classList.add('remark');
        }
        if (previewEl) {
          previewEl.style.display = 'none';
          previewEl.href = '#';
        }
        return;
      }

      // lanjut preview jika valid
      const blobUrl = URL.createObjectURL(file);

      if (previewEl) {
        previewEl.href = blobUrl;
        previewEl.style.display = 'inline-block';
        previewEl.textContent = 'Preview';
      }

      if (statusEl) {
        statusEl.textContent = '';
        statusEl.classList.remove('remark');
      }
    });
  });
})();
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const btn = document.getElementById('btnSubmitPendaftaran');
  const form = document.getElementById('uploadForm');
  const dariSubmitInput = document.getElementById('dari_submit');
  
  if (!btn || !form || !dariSubmitInput) {
    console.error('Button, form, atau hidden input tidak ditemukan');
    return;
  }

  btn.addEventListener('click', function (e) {
    e.preventDefault();

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
        // Set flag bahwa ini dari tombol submit
        dariSubmitInput.value = '1';
        
        // Submit form (akan simpan file dulu, lalu otomatis redirect ke submit.php)
        form.submit();
      }
    });
  });
});
</script>

<?php include 'footer.php'; ?>