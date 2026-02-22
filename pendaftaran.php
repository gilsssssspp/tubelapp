<?php
if(isset($_POST['aksi']) && $_POST['aksi'] == 'filter_kampus'){
    include 'koneksi.php';

    $tipe = $_POST['tipe']; // DN atau LN

    $q = mysqli_query($db, "SELECT * FROM mr_kampus WHERE tipe='$tipe'");

    echo "<option value=''>-- Pilih Kampus --</option>";
    while($r = mysqli_fetch_array($q)){
        echo "<option value='$r[id]'>$r[nama_kampus]</option>";
    }
    exit;
}
?>

<!-- CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>

<!-- jQuery (SATU-SATUNYA) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript">
function loadprodi() {
  var id_kampus = $('#kampus').val();

  $.ajax({
    type: 'GET',
    url: 'ambilpt.php',
    data: { id_kampus: id_kampus },
    cache: false,
    success: function(data){

      // jika select2 sudah aktif, destroy dulu biar tidak dobel
      if ($('#prodi').hasClass("select2-hidden-accessible")) {
        $('#prodi').select2('destroy');
      }

      if (data && data.length > 0) {
        $('#prodi').html(data);
      } else {
        $('#prodi').html('<option value="">Pilih Program Studi</option>');
      }

      // aktifkan lagi select2
      $('#prodi').select2({
        placeholder: "Pilih Program Studi",
        allowClear: true,
        width: '100%'
      });

      // optional: reset nilai prodi biar tidak nyangkut
      $('#prodi').val('').trigger('change');
    }
  });
}

  $(document).ready(function() {
    $('#kampus').select2({
        placeholder: "Pilih Perguruan Tinggi",
        allowClear: true,
        width: '100%'
    });
    $('#prodi').select2({
    placeholder: "Pilih Program Studi",
    allowClear: true,
    width: '100%'
    });
});
</script>

<?php
session_start(); // [DIUBAH] dipertahankan (wajib untuk baca $_SESSION['pendaftaran_error'])

if(isset($_SESSION['nip'])){

$header = "- Pendaftaran";

// import header
include 'header.php';
include 'koneksi.php';

$nip = $_SESSION['nip'];
$prodi = '';
$nama = '';
$jenkel = '';
$tmplahir = '';
//$tgllahir = '';
$jabatan = '';
$tmtjabatan = '';
$pendidikan = '';
$uke1 = '';
$uke2 = '';
$jurusan = '';
$pt = '';
$noijazah = '';
$tglijazah = '';
$nilaiipk = '';
$email='';
$telepon='';
$pernahs2='';
$disclaimer=0;
$status='';

// mengambil data tabel pendaftaran dengan kondisi status Cadangan
$query = mysqli_query($db, "SELECT * FROM mr_pegawai WHERE nip='$nip'");
$data = mysqli_fetch_array($query);

// cek kolom dari pendaftaran
if(mysqli_num_rows($query) >0) {
	$nama = $data['nama'];
	$jabatan = $data['jabatan'];
	$nama = $data['nama'];
	$tmplahir = $data['tmp_lahir'];
	$tgllahir = $data['tgl_lahir'];
  $golpangkat = $data['gol_pangkat'];
	$nohp = $data['no_hp'];
	$email = $data['email'];
	$komponen = $data['komponen'];
	$unitkerja = $data['unit_kerja'];
	$tingkatpendidikan = $data['tingkat_pendidikan'];
}
?>

<!-- container -->
<div class="container">
    <form class="card m-4 p-4 o-hidden border-0 shadow-lg" method="post" action="proses/proses_pendaftaran.php?iddaftar=0" enctype="multipart/form-data">

        <!-- heading -->
<h2 class="text-center">Pendaftaran</h2>
<fieldset>
    <div class="card p-3">
        <legend>Jenis Pengajuan</legend>
        <div class="mb-3">
            <input type="radio" id="tubelibel" name="tubelibel" value="tubel">
            <label for="tubelibel">Tugas Belajar</label>

            <input type="radio" id="tubelibel2" name="tubelibel" value="ibel">
            <label for="tubelibel2">Izin Belajar</label>
        </div>

        <!-- input nama -->
        <div class="mb-3">
            <label for="name" class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" name="nama" value="<?php echo $nama; ?>" disabled />
        </div>

        <!-- input nisn -->
        <div class="mb-3">
            <label for="NIP" class="form-label">NIP</label>
            <input type="text" class="form-control" name="nip" value="<?php echo $nip; ?>" disabled />
        </div>

        <div class="mb-3">
            <label for="tmplahir" class="form-label">Tempat Lahir</label>
            <input type="text" class="form-control" name="tmplahir" value="<?php echo $tmplahir; ?>" disabled />
        </div>

        <div class="mb-3">
            <label for="tgllahir" class="form-label">Tgl Lahir</label>
            <input type="text" class="form-control" name="tgllahir" value="<?php echo $tgllahir; ?>" disabled />
        </div>

        <div class="mb-3">
            <label for="emailhp" class="form-label">Email/No.Hp</label>
            <input type="text" class="form-control" name="email" value="<?php echo $email; ?>" disabled />
            <input type="text" class="form-control" name="nohp" value="<?php echo $nohp; ?>" disabled />
        </div>

        <div class="mb-3">
            <label for="jabatan" class="form-label">Jabatan Saat Ini</label>
            <input type="text" class="form-control" name="jabatan" value="<?php echo $jabatan; ?>" disabled />
        </div>

        <div class="mb-3">
            <label for="komponen" class="form-label">Komponen</label>
            <input type="text" class="form-control" name="komponen" value="<?php echo $komponen; ?>" disabled />
        </div>

        <div class="mb-3">
            <label for="golpang" class="form-label">Gol/Pangkat</label>
            <input type="text" class="form-control" name="golpang" value="<?php echo $golpangkat; ?>" disabled />
        </div>

        <div class="mb-3">
            <label for="tkpendidikan" class="form-label">Tingkat Pendidikan</label>
            <input type="text" class="form-control" name="tkpendidikan" value="<?php echo $tingkatpendidikan; ?>" disabled />
        </div>

        <div class="mb-3">
			<label>Perguruan Tinggi</label><label>
            &nbsp;&nbsp;
        <input type="radio" name="tipe_kampus" value="DN" onchange="loadKampus()">Dalam Negeri</label>
            &nbsp;&nbsp;
         <label>
        <input type="radio" name="tipe_kampus" value="LN" onchange="loadKampus()">Luar Negeri</label>
		&nbsp;&nbsp; 
		<label><i>(Jika Perguruan Tinggi tidak ada di daftar silahkan hubungi admin yang ada di menu contact atau email ke <b>timbangkombangrir@gmail.com</b> untuk didaftarkan dahulu ke dalam Database)</i><br/></label>
            <!-- [DIUBAH] tambahkan name="kampus" agar terkirim ke proses_pendaftaran.php -->
			<select class="form-control select2" id="kampus" name="kampus" onchange="loadprodi()"> <!-- [DIUBAH] -->

				<option value=""></option>
				<?php
				$q = mysqli_query($db, "SELECT * FROM mr_kampus");
				while($r = mysqli_fetch_array($q)){
					echo "<option value='$r[id]'>$r[nama_kampus]</option>";
				}
				?>
			</select>
		</div>

        <div class="mb-3">
            <label for="UnitKerja" class="form-label">Program Studi</label>
            <select class="form-control" name="prodi" id="prodi" class="select">
                <option>Pilih Program Studi</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="rencanakuliah" class="form-label">Rencana Kuliah</label>
            <input type="date" class="form-control" name="rencana_kuliah"/>
        </div>

        <!-- input Lama Studi -->
        <div class="mb-3" id="akhir_studi_container" style="display:block;">
            <label for="akhir_studi" class="form-label">Akhir Studi</label>
            <input type="date" class="form-control" name="akhir_studi" id="akhir_studi" required/>
        </div>

        <br/>
        <!-- tombol submit -->
        <button type="submit" name="simpan" class="btn btn-primary font-weight-bold">Simpan</button>
    </div>
</fieldset>

<script>
  // Field Akhir Studi selalu muncul untuk TUBEL dan IBEL
  document.addEventListener('DOMContentLoaded', function () {
    // Tidak perlu toggle lagi, field selalu visible dan required
  });
</script>

<!-- ===================================================== -->
<!-- [DIUBAH] SWEETALERT2 DI-LOAD SEKALI (untuk validasi front-end + error back-end) -->
<!-- ===================================================== -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11">
 <!-- [DIUBAH] -->

<!-- ===================================================== -->
<!-- [DIUBAH] VALIDASI SEBELUM SUBMIT (TETAP DALAM KONTEKS POP UP) -->
<!-- ===================================================== -->
<script> // [DIUBAH]
document.addEventListener('DOMContentLoaded', function () {
  const form = document.querySelector('form[action^="proses/proses_pendaftaran.php"]');
  if (!form) return;

  function getJenis() {
    const r = document.querySelector('input[name="tubelibel"]:checked');
    return r ? r.value : '';
  }

  function getKampusVal() {
    const el = document.getElementById('kampus');
    return el ? (el.value || '') : '';
  }

  function getProdiVal() {
    const el = document.getElementById('prodi');
    return el ? (el.value || '') : '';
  }

  function getRencanaVal() {
    const el = form.querySelector('input[name="rencana_kuliah"]');
    return el ? (el.value || '') : '';
  }

  function getAkhirVal() {
    const el = document.getElementById('akhir_studi');
    return el ? (el.value || '') : '';
  }

  function showWarn(msg, focusFn) {
    Swal.fire({
      icon: 'warning',
      title: 'Lengkapi Data',
      text: msg,
      timer: 2300,
      showConfirmButton: false,
      allowOutsideClick: false,
      timerProgressBar: false,
      didClose: () => { if (typeof focusFn === 'function') focusFn(); }
    });
  }

  form.addEventListener('submit', function (e) {
    const jenis  = getJenis();
    const kampus = getKampusVal();
    const prodi  = getProdiVal();
    const rencana= getRencanaVal();
    const akhir  = getAkhirVal();

    // 1) Jenis wajib
    if (!jenis) {
      e.preventDefault();
      showWarn('Jenis pengajuan wajib dipilih (Tugas Belajar / Izin Belajar).', () => {
        const el = document.getElementById('tubelibel');
        if (el) el.focus();
      });
      return;
    }

    // 2) Kampus wajib (TB & IB)
    if (!kampus) {
      e.preventDefault();
      showWarn('Perguruan tinggi wajib dipilih.', () => {
        const el = document.getElementById('kampus');
        if (el) el.focus();
      });
      return;
    }

    // 3) Prodi wajib (TB & IB)
    if (!prodi || prodi === '0' || prodi === 'Pilih Program Studi') {
      e.preventDefault();
      showWarn('Program studi wajib dipilih.', () => {
        const el = document.getElementById('prodi');
        if (el) el.focus();
      });
      return;
    }

    // 4) Rencana kuliah wajib (TB & IB)
    if (!rencana) {
      e.preventDefault();
      showWarn('Rencana kuliah wajib diisi.', () => {
        const el = form.querySelector('input[name="rencana_kuliah"]');
        if (el) el.focus();
      });
      return;
    }

    // 5) Akhir studi wajib hanya TB
    if (jenis === 'tubel' || jenis === 'ibel' && !akhir) {
      e.preventDefault();
      showWarn('Akhir studi wajib diisi', () => {
        const el = document.getElementById('akhir_studi');
        if (el) el.focus();
      });
      return;
    }
// 6) Akhir studi harus lebih besar dari rencana kuliah
if (rencana && akhir) {
  const tglRencana = new Date(rencana);
  const tglAkhir   = new Date(akhir);

  if (tglAkhir <= tglRencana) {
    e.preventDefault();
    showWarn('Tanggal akhir studi harus lebih besar dari rencana kuliah.', () => {
      const el = document.getElementById('akhir_studi');
      if (el) el.focus();
    });
    return;
  }
}

  });
});
</script>
<!-- ===================================================== -->

<!-- ===================================================== -->
<!-- [DIUBAH] BLOK SWEETALERT UNTUK ERROR DARI proses_pendaftaran.php -->
<!-- ===================================================== -->
<?php if (!empty($_SESSION['pendaftaran_error'])): ?> <!-- [DIUBAH] -->
<script> <!-- [DIUBAH] -->
document.addEventListener('DOMContentLoaded', function () {
  Swal.fire({
    icon: 'warning',
    title: 'Peringatan',
    text: <?= json_encode($_SESSION['pendaftaran_error']); ?>,
    timer: 2300,
    showConfirmButton: false,
    allowOutsideClick: false,
    timerProgressBar: false,
    didClose: () => {
      const prodi = document.getElementById('prodi');
      if (prodi) prodi.focus();
    }
  });
});
</script> <!-- [DIUBAH] -->
<?php unset($_SESSION['pendaftaran_error']); endif; ?> <!-- [DIUBAH] -->
<!-- ===================================================== -->

    </form>
</div>

<?php
// import footer
//include 'footer.php';

} else {
    echo "<script>
            alert('Silahkan Login Terlebih Dahulu!');
            window.location = 'login.php';
        </script>";
}
?>

<script>
$(document).ready(function() {
    var s = $('#commanumber').val().replace(/\,/g, '.');
    $('#commanumber').attr('type','number');
    $('#commanumber').val(s);
});
</script>

<script>
function loadKampus(){
  let tipe = $('input[name="tipe_kampus"]:checked').val() || null;

  $.ajax({
    type: 'POST',
    url: 'pendaftaran.php',   // (opsional) atau file ini sendiri
    data: { aksi: 'filter_kampus', tipe: tipe },
    success: function(res){
      $('#kampus').html(res).trigger('change.select2'); // biar select2 update
    }
  });
}
</script>

<?php

if (isset($_SESSION['pendaftaran_status'], $_SESSION['pendaftaran_message'])) {
    unset($_SESSION['pendaftaran_status'], $_SESSION['pendaftaran_message']);
}
?>

