<?php
if(isset($_POST['aksi']) && $_POST['aksi'] == 'filter_kampus'){
    include 'koneksi.php';
	
	
		$tipe = $_POST['tipe']; // DN atau LN

    $q = mysqli_query($db, "SELECT * FROM mr_kampus WHERE tipe='$tipe' or tipe='DNLN'");

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
   var id_prodi = $('#kampus').val();
	// const container = document.getElementById('kampuslain_container');
	// const input     = document.getElementById('kampus_lain');
	// const prodilaincontainer = document.getElementById('prodilain_container');
	// const prodilaininput     = document.getElementById('prodi_lain');
	// const prodicontainer = document.getElementById('prodi_container');

    // if (id_prodi==99999) {
      // container.style.display = 'block';
      // if (input) input.required = true;   // wajib untuk TUBEL
	  
	  // prodilaincontainer.style.display = 'block';
      // if (prodilaininput) prodilaininput.required = true;   // wajib untuk TUBEL
	  
	  // prodicontainer.style.display = 'none';
     
	  
    // } else {
      // container.style.display = 'none';
      // if (input) {
        // input.required = false;           // tidak wajib untuk IBEL
        // input.value = '';                 // bersihkan biar tidak ikut tersimpan
      // }
	  // prodilaincontainer.style.display = 'none';
      // if (prodilaininput) {
        // prodilaininput.required = false;           // tidak wajib untuk IBEL
        // prodilaininput.value = '';                 // bersihkan biar tidak ikut tersimpan
      // }
	  
	  // prodicontainer.style.display = 'block';
	  
	  
		$.ajax({
		type: 'GET',
		url: 'ambilpt.php',
		data: "id_kampus=" + id_prodi,"jenis="+
		success: function(data){
		//alert(data.length);
		 if(data.length > 0){
		  $('#prodi').html(data);
		 } else {
			   
			$('#prodi').html("<option>Pilih Program Studi</option>");
		 }
		}
	   });
    //}	
	
   
  }
  
  $(document).ready(function() {
    $('#kampus').select2({
        placeholder: "Pilih Perguruan Tinggi",
        allowClear: true,
        width: '100%'
    });
});

</script>
<?php

session_start();

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


// $lahir=substr($nip,4,2).'/'.substr($nip,6,2).'/'.substr($nip,0,4);
// $time = strtotime($lahir);

// $tgllahir = date('Y-m-d',$time);


//mengambil nama-nama propinsi yang ada di database
// $sql = mysqli_query($db, "SELECT * FROM pendaftaran where nip='$nip'");
// while ($row = $sql->fetch_assoc()){
	// $prodi = $row['prodi'];
	// $nama = $row['nama'];
	// $jenkel = $row['jenkel'];
	// $tmplahir = $row['tempat_lahir'];
	// if ($row['tgl_lahir'] <>'') {$tgllahir = $row['tgl_lahir']; }
	// $jabatan = $row['jabatan'];
	// $tmtjabatan = $row['tmt_jabatan'];
	// $uke1 = $row['unit_kerja1'];
	// $uke2 = $row['unit_kerja2'];
	// $pendidikan = $row['pendidikan_terakhir'];
	// $pernahs2 = $row['pernahs2'];
	// $disclaimer = $row['disclaimer'];
	// $jurusan = $row['jurusan_s1'];
	// $pt = $row['pt_s1'];
	// $noijazah = $row['no_ijazah_s1'];
	// $tglijazah = $row['tgl_ijazah_s1'];
	// $nilaiipk = $row['nilai_ipk_s1'];
	// $email = $row['email'];
	// $telepon = $row['telepon'];
	// $status = $row['status'];
// }
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
            <input type="radio" id="tubelibel" name="tubelibel" value="tubel" onclick="toggleAkhirStudi(true)">
            <label for="tubelibel">Tugas Belajar</label>
            
            <input type="radio" id="tubelibel2" name="tubelibel" value="ibel" onclick="toggleAkhirStudi(false)">
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
            <label for="tingkat_pendidikan" class="form-label">Tingkat Pendidikan</label>
            <input type="text" class="form-control" name="tingkat_pendidikan" value="<?php echo $tingkatpendidikan; ?>" disabled />
        </div>
            
        <div class="mb-3">
			<label>Perguruan Tinggi</label><label>
            &nbsp;&nbsp;
        <input type="radio" name="tipe_kampus" value="DN" onchange="loadKampus()" >Dalam Negeri</label>
            &nbsp;&nbsp;
         <label>
        <input type="radio" name="tipe_kampus" value="LN" onchange="loadKampus()">Luar Negeri</label>
		&nbsp;&nbsp; 
		<label><i>(Jika Perguruan Tinggi tidak ada di daftar silahkan hubungi admin yang ada di menu contact untuk didaftarkan dahulu ke dalam Database)</i><br/></label>
			<select class="form-control select2" id="kampus" onchange="loadprodi()">
                
				<option value=""></option>
				<?php
				$q = mysqli_query($db, "SELECT * FROM mr_kampus");
				while($r = mysqli_fetch_array($q)){
					echo "<option value='$r[id]'>$r[nama_kampus]</option>";
				}
				?>
			</select>
		</div>
        
		<div class="mb-3" id="kampuslain_container" style="display:none">
            <label for="jabatan" class="form-label">Masukan Kampus Lainnya</label>
            <input type="text" class="form-control" name="kampuslain" />
        </div>
		
        <div class="mb-3" id="prodi_container">
            <label for="UnitKerja" class="form-label">Program Studi</label>
            <select class="form-control" name="prodi" id="prodi" class="select">
                <option>Pilih Program Studi</option>
            </select>
        </div>
        
		<div class="mb-3" id="prodilain_container" style="display:none">
            <label for="jabatan" class="form-label">Masukan Program Studi</label>
            <input type="text" class="form-control" name="prodi" />
        </div>
		
        <div class="mb-3">
            <label for="rencanakuliah" class="form-label">Rencana Kuliah</label>
            <input type="date" class="form-control" name="rencana_kuliah"/>
        </div>
        
        <!-- input Lama Studi -->
        <div class="mb-3" id="akhir_studi_container" style="display:none;">
            <label for="akhir_studi" class="form-label">Akhir Studi</label>
            <input type="date" class="form-control" name="akhir_studi" id="akhir_studi"/>
        </div>

        
        <br/>
        <!-- tombol submit -->
        <button type="submit" name="simpan" class="btn btn-primary font-weight-bold">Simpan</button>            
    </div>        
</fieldset>

<script>
  function toggleAkhirStudi(isTugasBelajar) {
    const container = document.getElementById('akhir_studi_container');
    const input     = document.getElementById('akhir_studi');

    if (isTugasBelajar) {
      container.style.display = 'block';
      if (input) input.required = true;   // wajib untuk TUBEL
    } else {
      container.style.display = 'none';
      if (input) {
        input.required = false;           // tidak wajib untuk IBEL
        input.value = '';                 // bersihkan biar tidak ikut tersimpan
      }
    }
  }

 function kampuslainnya(isTugasBelajar) {
    const container = document.getElementById('akhir_studi_container');
    const input     = document.getElementById('akhir_studi');

    if (isTugasBelajar) {
      container.style.display = 'block';
      if (input) input.required = true;   // wajib untuk TUBEL
    } else {
      container.style.display = 'none';
      if (input) {
        input.required = false;           // tidak wajib untuk IBEL
        input.value = '';                 // bersihkan biar tidak ikut tersimpan
      }
    }
  }
  
  // Jalankan saat halaman pertama kali dibuka (penting!)
  document.addEventListener('DOMContentLoaded', function () {
    const checked = document.querySelector('input[name="tubelibel"]:checked');

    if (!checked) {
      // belum pilih apa-apa -> hide & not required
      toggleAkhirStudi(true);
      return;
    }

    toggleAkhirStudi(checked.value === 'tubel');
  });
</script>


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

$(document).ready(function () {
  const $el = $("#commanumber");
  if ($el.length) {
    const s = ($el.val() ?? "").toString().replace(/,/g, "");
    $el.attr("type", "number");
    $el.val(s);
  }
});

  
 
 </script>

 <script>
function loadKampus(){
    let tipe = $('input[name="tipe_kampus"]:checked').val() || null;

    $.ajax({
        type: 'POST',
        data: {
            aksi: 'filter_kampus',
            tipe: tipe
        },
        success: function(res){
            $('#kampus').html(res);
        }
    });
}

</script>
