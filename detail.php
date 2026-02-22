<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript">
function loadprodi() {
   var id_prodi = $('#kampus').val();

   $.ajax({
    type: 'GET',
    url: 'ambilpt.php',
    data: "id_prodi=" + id_prodi,
    success: function(data){
     if(data.length > 0){
      $('#prodi').html(data);
     } else {
      $('#prodi').html("<option>Pilih Program Studi</option>");
     }
    }
   });
  }

</script>
<?php

session_start();

if(isset($_GET['iddaftar'])){

	$header = "- Pendaftaran";

	// import header
	include 'header.php';
	include 'koneksi.php';

	$nip = $_SESSION['nip'];
	$iddaftar = $_GET['iddaftar'];
	$alasantms = $_GET['alasantms'] ?? '';
	
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
	$sql = mysqli_query($db, "SELECT a.*, b.id id_prodi, b.nama_prodi, c.id id_kampus, c.nama_kampus 
    FROM pendaftaran a
    LEFT JOIN mr_prodi b ON b.id=a.id_prodi
    LEFT JOIN mr_kampus c ON c.id=b.id_kampus 
    WHERE a.id='$iddaftar'
    LIMIT 1
");

$row = mysqli_fetch_assoc($sql);

$prodi_id      = $row['id_prodi'] ?? '';
$kampus_id     = $row['id_kampus'] ?? '';
$tubelibel     = $row['tubel_ibel'] ?? '';
$rencanakuliah = $row['rencana_kuliah'] ?? '';
$akhirstudi    = $row['akhir_studi'] ?? '';

// tentukan apakah ini TMS
$isTMS = false;
$stUsul = strtoupper(trim($row['status_usul'] ?? ''));
$hstVer = strtoupper(trim($row['hasil_verifikasi'] ?? ''));

if (strpos($stUsul, 'TMS') === 0 || $hstVer === 'TIDAK LULUS VERIFIKASI') {
    $isTMS = true;
}


?>

<!-- container -->
<div class="container">
    <form class="card m-4 p-4 o-hidden border-0 shadow-lg" method="post" action="proses/proses_pendaftaran.php?iddaftar=<?=$iddaftar;?>&alasantms=<?=$alasantms; ?>" enctype="multipart/form-data">
        
        <!-- heading -->
        <h2 class="text-center">Pendaftaran</h2>
        <fieldset>
            <div class="card p-3">
					<?php
					if ($tubelibel=='tubel') { ?>
				
						<legend>Jenis Pengajuan </legend>
						<div class="mb-3">
							<input type="radio" id="tubelibel" name="tubelibel" value="tubel" <?php if ($tubelibel == "tubel") echo "checked"; ?> >
							<label for="html">Tugas Belajar</label>
							
							
						</div>
					<?php }else{ ?>
						<legend>Jenis Pengajuan </legend>
						<div class="mb-3">
							
							
							<input type="radio" id="tubelibel" name="tubelibel" value="ibel" <?php if ($tubelibel == "ibel") echo "checked"; ?> >
							<label for="html">Izin Belajar</label>
						</div>
					<?php } ?>
					
				
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
					
					<!-- input alamat -->
					<div class="mb-3">
						<label for="UnitKerja" class="form-label">Perguruan Tinggi</label>
						<select class="form-control" id="kampus" name="kampus" onchange="loadprodi()">
						 <option value="0">Pilih Perguruan Tinggi</option>
						 <?php
						  $tableQry = mysqli_query($db, "SELECT * FROM mr_kampus");

						  while($rKampus = mysqli_fetch_array($tableQry)){
							if ($rKampus['id'] == $kampus_id) {
								echo "<option value=\"{$rKampus['id']}\" selected>{$rKampus['nama_kampus']}</option>\n";
							}else{
								echo "<option value=\"{$rKampus['id']}\">{$rKampus['nama_kampus']}</option>\n";
							}
						  }
						 ?>					 
						 
						</select>
					</div>
					
					<div class="mb-3">
						<label for="UnitKerja" class="form-label">Program Studi</label>
						<select class="form-control" name="prodi" id="prodi" class="select">
							<option>Pilih Program Studi</option>
							<?php
							$tableQry = mysqli_query($db, "SELECT * FROM mr_prodi");

							while($rProdi = mysqli_fetch_array($tableQry)){
							if ($rProdi['id'] == $prodi_id) {
								echo "<option value=\"{$rProdi['id']}\" selected>{$rProdi['nama_prodi']}</option>\n";
							}else{
								echo "<option value=\"{$rProdi['id']}\">{$rProdi['nama_prodi']}</option>\n";
							}
							}
							?>
						</select>
						
					</div>
					
					<div class="mb-3">
						<label for="rencanakuliah" class="form-label">Rencana Kuliah</label>
						<input type="date" class="form-control" name="rencana_kuliah" value="<?php echo $rencanakuliah; ?>"/>
					</div>
					
					<div class="mb-3">
						<label for="akhir_studi" class="form-label">Akhir Studi</label>
						<input type="date" class="form-control" name="akhir_studi" value="<?php echo $akhirstudi; ?>"/>
					</div>
					
					<!-- tombol submit -->
					<button type="submit" name="simpan" class="btn btn-primary font-weight-bold">Simpan</button>			
            </div>        
        </fieldset>
			<?php if ($isTMS) { ?>
    <input type="hidden" name="origin" value="detail">
<?php } ?>


    </form>
</div>

<?php

	// import footer
	include 'footer.php';

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