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
					LEFT JOIN mr_kampus c ON c.id=b.id_kampus where a.id='$iddaftar'");
	while ($row = $sql->fetch_assoc()){
		$prodi_id = $row['id_prodi'];	
		$kampus_id = $row['id_kampus'];	
		$tubelibel = $row['tubel_ibel'];
		$rencanakuliah = $row['rencana_kuliah'];
		$akhir_studi = $row['akhir_studi'];
	}
?>

<!-- container -->
<div class="container">
    <form class="card m-4 p-4 o-hidden border-0 shadow-lg" method="post" action="proses/proses_pendaftaran.php?iddaftar=<?=$iddaftar;?>" enctype="multipart/form-data">
        
        <!-- heading -->
        <h2 class="text-center">Pendaftaran</h2>
        <fieldset>
            <div class="card p-3">
			
				<legend>Jenis Pengajuan</legend>
				<div class="mb-3">
					<input type="radio" id="tubelibel" name="tubelibel" value="tubel" <?php if ($tubelibel == 'tubel') echo 'checked'; ?>>
					<label for="html">Tugas Belajar</label>
					
					<input type="radio" id="tubelibel" name="tubelibel" value="ibel" <?php if ($tubelibel == 'ibel') echo 'checked'; ?>>
					<label for="html">Izin Belajar</label>
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
                    <label for="jabatan" class="form-label">Jabatan Saat Iniiii</label>
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
					 <select class="form-control" id="kampus" onchange="loadprodi()" class="select">
					 <option value="0">Pilih Perguruan Tinggi</option>
					 <?php
					  $tableQry = mysqli_query($db, "SELECT * FROM mr_kampus");

					  while($row = mysqli_fetch_array($tableQry)){
						if ($row[id]==$kampus_id) {
							echo "<option value=\"$row[id]\" selected>$row[nama_kampus]</option>\n";
						}else{
							echo "<option value=\"$row[id]\">$row[nama_kampus]</option>\n";
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

						while($row[id] = mysqli_fetch_array($tableQry)){
							if ($tubelibel=='prodi_id') {
								echo "<option value=\"$row[id]\" selected>$row[nama_prodi]</option>\n";
							}else{
								echo "<option value=\"$row[id]\">$row[nama_prodi]</option>\n";
							}
						}
						?>
					</select>
						
						
						
				</div>
					
					
					<div class="mb-3">
						<label for="rencanakuliah" class="form-label">Rencana Kuliah</label>
						<input type="date" class="form-control" name="rencana_kuliah" value="<?php echo $rencanakuliah; ?>"/>
					</div>
					
					
					
					
					<br/>
					<!-- tombol submit -->
					<button type="submit" name="simpan" class="btn btn-primary font-weight-bold">Simpan & Lanjut</button>			
				
            </div>        
        </fieldset>
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