<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript">
var htmlobjek;
$(document).ready(function(){

  //apabila terjadi event onchange terhadap object <select id=propinsi>
  $("#uke1").change(function(){
    var uke1 = $("#uke1").val();
    $.ajax({
        url: "ambiluke2.php",
        data: "uke1="+uke1,
        cache: false,
        success: function(msg){
            //jika data sukses diambil dari server kita tampilkan
            //di <select id=kota>
            $("#uke2").html(msg);
        }
    });
  });
  // $("#uke2").change(function(){
    // var kota = $("#kota").val();
    // $.ajax({
        // url: "ambilkecamatan.php",
        // data: "kota="+kota,
        // cache: false,
        // success: function(msg){
            // $("#kec").html(msg);
        // }
    // });
  // });
});

</script>
<?php

session_start();
include 'koneksi.php';

if(isset($_SESSION['nip'])){

$header = "- Pendaftaran Tugas Belajar";

// import header
include 'header.php';

$nip = $_SESSION['nip'];


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
    <form class="card m-4 p-4 o-hidden border-0 shadow-lg" method="post" action="proses/proses_pendaftaran.php" enctype="multipart/form-data">
        
        <!-- heading -->
        <h2 class="text-center">Pendaftaran Tugas Belajar</h2>
        <fieldset>
            <legend>Data Diri</legend>
            <div class="card p-3">


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
						<label for="UnitKerja" class="form-label">Komponen</label>
						<select class="form-control" name="uke1" id="uke1" readonly>
						<option>--Pilih Komponen--</option>
						<?php 

						$sql = mysqli_query($db, "SELECT * FROM mr_eselon1");

						while ($row = $sql->fetch_assoc()){
						
						if ($row[id_es1]==$uke1) {
							echo "<option value=\"$row[id_es1]\" selected>$row[nama_eselon1]</option>\n";
						}else{
							echo "<option value=\"$row[id_es1]\">$row[nama_eselon1]</option>\n";
						}
						}
						?>
						</select>
					</div>
					
					<div class="mb-3">
						<label for="UnitKerja" class="form-label">Unit Kerja</label>
						<select class="form-control" name="uke2" id="uke2" readonly>
						<option>--Pilih Unit Kerja--</option>
						<?php
						//mengambil nama-nama propinsi yang ada di database
						$sql = mysqli_query($db, "SELECT * FROM mr_eselon2 ORDER BY id_es2");
						while ($row = $sql->fetch_assoc()){
							
							if ($row[id_es2]==$uke2) {
								echo "<option value=\"$row[id_es2]\" selected>$row[nama_eselon2]</option>\n";
							}else{
								echo "<option value=\"$row[id_es2]\">$row[nama_eselon2]</option>\n";
							}
						}
						?>
						</select>
					</div>
					
                <!-- tombol submit -->
                <button type="submit" name="simpan" class="btn btn-secondary font-weight-bold">Simpan</button>
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