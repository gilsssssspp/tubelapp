<?php
ob_start();          
session_start();
include 'koneksi.php';
?>

<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript">
var htmlobjek;

$(document).ready(function(){

  // === KODE ANDA (AJAX UKE) TETAP ===
  $("#uke1").change(function(){
    var uke1 = $("#uke1").val();
    $.ajax({
      url: "ambiluke2.php",
      data: "uke1=" + uke1,
      cache: false,
      success: function(msg){
        $("#uke2").html(msg);
      }
    });
  });

  // === PENTING: SET KONDISI AWAL SAAT HALAMAN DIBUKA ===
  // pakai setTimeout kecil supaya value select dari PHP (selected) sudah "nempel"
  setTimeout(function(){
    $('select[data-target]').each(function(){
      toggleMS(this, true); // initMode = true
    });
  }, 0);

  // === UPDATE SAAT USER GANTI MS/TMS ===
  $(document).on('change', 'select[data-target]', function(){
    toggleMS(this, false);
  });

});

function toggle() {
  var ele = document.getElementById("sembunyi");
  var gmbr = document.getElementById("gmbr");
  var text = document.getElementById("tampil");
  if(ele.style.display == "block") {
    ele.style.display = "none";
    gmbr.style.display = "block";
    text.innerHTML = "Change Image";
  } else {
    ele.style.display = "block";
    gmbr.style.display = "none";
    text.innerHTML = "Cancel";
  }
}

// initMode: true = dipanggil saat page load (jangan ganggu alasan yang sudah ada kalau TMS)
function toggleMS(el, initMode) {
  var id = el.getAttribute('data-target') || (el.dataset ? el.dataset.target : '');
  if (!id) return;

  var target = document.getElementById(id);
  if (!target) return;

 
  var wrapper = target.closest('.col-sm-4') || target.parentElement;

  var v = String(el.value || '').trim().toUpperCase();
  var isTMS = (v === 'TMS');

  if (isTMS) {
    if (wrapper) wrapper.style.display = 'block';
    else target.style.display = 'block';
  } else {
    
    if (wrapper) wrapper.style.display = 'none';
    else target.style.display = 'none';

    
    if (v === 'MS') {
      if (!initMode) target.value = '';
      
    }
  }
}
</script>

<style>
	.remark {
    color: #ff0000;
}
</style>

<style>
input {width:95%;border:solid 1px #eeeeee;padding:6px 5px;}
input:hover {border:solid 1px #0099FF}
.group { margin:10px;}
.label2{  display:table}
.button {text-align:center;background:#0099FF;color:#ffffff; padding:5px; border:0px; width:100px; cursor:pointer; border: solid 3px #eeeeee;}
.button:hover{border: solid 3px #0099FF;}
.gambar-container { width:980px;margin:auto;}
.gambar-container h3 { text-align:center;font-family:Arial, Helvetica, sans-serif;font-size:20px;color:#999999}
.gambar {float:left;width:190px;height:200px; padding:1px;border: dotted 1px #eeeeee;}
.gambar h3 {text-align:center;font-family:Arial, Helvetica, sans-serif;font-size:14px;color:#333333; margin:0px; padding:4px;}
.gambar img { width:100%;height:170px;}
.img-thumbnail {border: solid 1px #eeeeee; padding:3px; margin:10px;}
#tampil {background:#eeeeee; padding:5px; color:#333333;left:0px;}

.edit{float:left; text-align:center;font-size:14px; color:#fff; font-family:Arial, Helvetica, sans-serif; background:#0066FF; padding:4px; display:block}
.hapus {float:right;text-align:center;font-size:14px; color:#ffffff; font-family:Arial, Helvetica, sans-serif; background:#FF0000; padding:4px; display:block; width:80px;}
a{text-decoration:none;}
.container {width:400px; margin:auto;padding:10px;border:dotted 1px #cccccc;}

/* Tooltip container */
.tooltip {
  position: relative;
  display: inline-block;
  border-bottom: 1px dotted black; /* If you want dots under the hoverable text */
}

/* Tooltip text */
.tooltip .tooltiptext {
  visibility: hidden;
  width: 120px;
  background-color: black;
  color: #fff;
  text-align: center;
  padding: 5px 0;
  border-radius: 6px;
 
  /* Position the tooltip text - see examples below! */
  position: absolute;
  z-index: 1;
}

/* Show the tooltip text when you mouse over the tooltip container */
.tooltip:hover .tooltiptext {
  visibility: visible;
}

</style>

<?php


if(isset($_GET['iddaftar'])){

$header = "- Pendaftaran";

// import header
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
    timer: 2200,
    showConfirmButton: false,
    allowOutsideClick: false,
    allowEscapeKey: false
  });
});
</script>
<?php endif;

$iddaftar = $_GET['iddaftar'];



$suratpengantar = '';
$surat_pengantar_hasil='';
$surat_pengantar_alasan='';
$suratloa = '';
$buktibeasiswa = '';
$ketmulai = '';
$suratakre = '';
$ketsehat_jasmani = '';
$ketsehat_rohani = '';
$ijazahterakhir = '';
$transkripnilai = '';
$skp2 = '';
$surathukdis = '';
$suratberhentistruktural = '';
$suratberhentifungsional = '';
$perjanianpenempatan = '';
$rekomibel = '';
$pernyataanibel = '';

//echo $folderBaseNip; die;
$query = "SELECT * FROM pendaftaran where id='$iddaftar'";
//echo $query; die;
$sql = mysqli_query($db, $query);

while ($row = $sql->fetch_assoc()){
	$id = $row['id'];
	$surat_rpkasn_hasil = $row['surat_rpkasn_hasil'];
	$surat_rpkasn_alasan = $row['surat_rpkasn_alasan'];
	$suratpengantar = $row['surat_pengantar'];
	$surat_pengantar_hasil = $row['surat_pengantar_hasil'];
	$surat_pengantar_alasan = $row['surat_pengantar_alasan'];
	$suratloa = $row['surat_loa'];
	$surat_loa_hasil = $row['surat_loa_hasil'];
	$surat_loa_alasan = $row['surat_loa_alasan'];
	$buktibeasiswa = $row['surat_penerimabeasiswa'];
	$surat_penerimabeasiswa_hasil = $row['surat_penerimabeasiswa_hasil'];
	$surat_penerimabeasiswa_alasan = $row['surat_penerimabeasiswa_alasan'];
	$ketmulai = $row['kalender_akademik'];
	$kalender_akademik_hasil = $row['kalender_akademik_hasil'];
	$kalender_akademik_alasan = $row['kalender_akademik_alasan'];
	$suratakre = $row['surat_akreditasi'];
	$surat_akreditasi_hasil = $row['surat_akreditasi_hasil'];
	$surat_akreditasi_alasan = $row['surat_akreditasi_alasan'];
	$ketsehat_jasmani = $row['surat_jasmani'];
	$surat_jasmani_hasil = $row['surat_jasmani_hasil'];
	$surat_jasmani_alasan = $row['surat_jasmani_alasan'];
	$ketsehat_rohani = $row['surat_rohani'];
	$surat_rohani_hasil = $row['surat_rohani_hasil'];
	$surat_rohani_alasan = $row['surat_rohani_alasan'];
	$ijazahterakhir = $row['ijazah_terakhir'];
	$ijazah_terakhir_hasil = $row['ijazah_terakhir_hasil'];
	$ijazah_terakhir_alasan = $row['ijazah_terakhir_alasan'];
	$transkripnilai = $row['transkrip_nilai'];
	$transkrip_nilai_hasil = $row['transkrip_nilai_hasil'];
	$transkrip_nilai_alasan = $row['transkrip_nilai_alasan'];
	$skp1 = $row['skp_1'];
	$skp_1_hasil = $row['skp_1_hasil'];
	$skp_1_alasan = $row['skp_1_alasan'];
	$skp2 = $row['skp_2'];
	$skp_2_hasil = $row['skp_2_hasil'];
	$skp_2_alasan = $row['skp_2_alasan'];
	$surathukdis = $row['surat_hukdis'];
	$surat_hukdis_hasil = $row['surat_hukdis_hasil'];
	$surat_hukdis_alasan = $row['surat_hukdis_alasan'];
	$suratberhentistruktural = $row['surat_berhentistruktural'];
	$surat_berhentistruktural_hasil = $row['surat_berhentistruktural_hasil'];
	$surat_berhentistruktural_alasan = $row['surat_berhentistruktural_alasan'];
	$suratberhentifungsional = $row['surat_berhentiJF'];
	$surat_berhentiJF_hasil = $row['surat_berhentiJF_hasil'];
	$surat_berhentiJF_alasan = $row['surat_berhentiJF_alasan'];
	$surat_dimanasaja = $row['surat_dimanasaja'];
	$surat_dimanasaja_hasil = $row['surat_dimanasaja_hasil'];
	$surat_dimanasaja_alasan = $row['surat_dimanasaja_alasan'];
	$rekomibel = $row['surat_rekomibel'];
	$pernyataanibel = $row['surat_pernyataanibel'];
	$surat_rpkasn_hasil = $row['surat_rpkasn_hasil'];
	$surat_rpkasn_alasan = $row['surat_rpkasn_alasan'];
	$status = $row['status_usul'];
	$nip = $row['nip'];
	$jenis   = isset($row['tubel_ibel']) ? strtolower(trim($row['tubel_ibel'])) : 'tubel';
    if ($jenis !== 'ibel' && $jenis !== 'tubel') {
        $jenis = 'tubel';
    }
    // folder view file: uploads/{tubel|ibel}/{nip}/
    $folderBaseNip = "uploads/" . $jenis . "/" . $nip . "/";
}

//echo $suratpengantar; die;
?>

<!-- container -->
<div class="container">
    <form class="card m-4 p-4 o-hidden border-0 shadow-lg" method="post" action="proses/proses_verifikasi_tubel.php?iddaftar=<?=$id;?>" enctype="multipart/form-data">
        
        <!-- heading -->
        <h2 class="text-center">Verifikasi Pengajuan Tugas/Izin Belajar</h2><br/>
		
        <fieldset>
            <legend>Data Pendukung</legend><br/>
			<!--<p><a role="button" name="submit" href="index.php" class="btn btn-sm btn-secondary">Pending</a></p>-->
				
				
				<div class="card">

					<div class="row">
						<div class="col-sm-4">
							<h6 class="mb-0">1. Prodi Sudah Sesuai dengan RPKASN</h6>
						</div>
						<div class="col-sm-2 text-secondary">
						</div>
						<div class="col-sm-2 text-secondary">
							<select id="surat_rpkasn_hasil" name="surat_rpkasn_hasil" class="custom-select" aria-label="Default select example" data-target="surat_rpkasn_alasan" onchange="toggleMS(this)">
							  <option value="">--MS/TMS--</option>
							  <option value="MS"<?php if($surat_rpkasn_hasil== 'MS'){ echo 'selected'; }?>>MS</option>
							  <option value="TMS"<?php if($surat_rpkasn_hasil== 'TMS'){ echo 'selected'; }?>>TMS</option>
							</select>
						</div>
						<div class="col-sm-4 text-secondary">
							<input type="text" class="form-control" id="surat_rpkasn_alasan" name="surat_rpkasn_alasan" value="<?php echo $surat_rpkasn_alasan; ?>" placeholder="Alasan"/>
						</div>
					</div>	
					<hr/>
					<div class="row">
						<div class="col-sm-4">
							<h6 class="mb-0">2. Surat Pengantar Dari Unit Kerja</h6>
						</div>
						<div class="col-sm-2 text-secondary">
							<?php if ($suratpengantar<>'') {

								$namaFile = basename($suratpengantar);
								$urlFile = $folderBaseNip . rawurlencode($namaFile);

								echo "<td>";
								echo "<a href=\"$urlFile\" target=\"_blank\">View</a>";
								echo "</td>";
							}else{
								echo "<td>";
								echo "Tidak Upload File";
								echo "</td>";
							}
						?>
						</div>
						<div class="col-sm-2 text-secondary">
							<select id="surat_pengantar_hasil" name="surat_pengantar_hasil" class="custom-select" aria-label="Default select example" data-target="surat_pengantar_alasan" onchange="toggleMS(this)">
							  <option value="">--MS/TMS--</option>
							  <option value="MS"<?php if($surat_pengantar_hasil== 'MS'){ echo 'selected'; }?>>MS</option>
							  <option value="TMS"<?php if($surat_pengantar_hasil== 'TMS'){ echo 'selected'; }?>>TMS</option>
							</select>
						</div>
						<div class="col-sm-4 text-secondary">
							<input type="text" class="form-control" id="surat_pengantar_alasan" name="surat_pengantar_alasan" value="<?php echo $surat_pengantar_alasan; ?>" placeholder="Alasan"/>
						</div>
					</div>	
					<hr/>
					<div class="row">
						<div class="col-sm-4">
							<h6 class="mb-0">3. Surat lulus seleksi dari Lembaga Pendidikan yang dituju/Letter of Acceptance (LOA)</h6>
						</div>
						<div class="col-sm-2 text-secondary">
							<?php if ($suratloa<>'') {

								$namaFile = basename($suratloa);
								$urlFile = $folderBaseNip . rawurlencode($namaFile);

								echo "<td>";
								echo "<a href=\"$urlFile\" target=\"_blank\">View</a>";
								echo "</td>";
							}else{
								echo "<td>";
								echo "Tidak Upload File";
								echo "</td>";
							}
						?>
						</div>
						<div class="col-sm-2 text-secondary">
							<select id="surat_loa_hasil" name="surat_loa_hasil" class="custom-select" aria-label="Default select example" data-target="surat_loa_alasan" onchange="toggleMS(this)">
							  <option value="">--MS/TMS--</option>
							  <option value="MS"<?php if($surat_loa_hasil== 'MS'){ echo 'selected'; }?>>MS</option>
							  <option value="TMS"<?php if($surat_loa_hasil== 'TMS'){ echo 'selected'; }?>>TMS</option>
							</select>
						</div>
						<div class="col-sm-4 text-secondary">
							<input type="text" class="form-control" id="surat_loa_alasan" name="surat_loa_alasan" value="<?php echo $surat_loa_alasan; ?>" placeholder="Alasan"/>
						</div>
					</div>	
					<hr>
					<div class="row">
						<div class="col-sm-4">
							<h6 class="mb-0">4. Surat bukti penerima Beasiswa dari donatur pemberi beasiswa</h6>
						</div>
						<div class="col-sm-2 text-secondary">
							<?php if ($buktibeasiswa<>'') {

								$namaFile = basename($buktibeasiswa);
								$urlFile = $folderBaseNip . rawurlencode($namaFile);

								echo "<td>";
								echo "<a href=\"$urlFile\" target=\"_blank\">View</a>";
								echo "</td>";
							}else{
								echo "<td>";
								echo "Tidak Upload File";
								echo "</td>";
							}
						?>
						</div>
						<div class="col-sm-2 text-secondary">
							<select id="surat_penerimabeasiswa_hasil" name="surat_penerimabeasiswa_hasil" class="custom-select" aria-label="Default select example" data-target="surat_penerimabeasiswa_alasan" onchange="toggleMS(this)">
							  <option value="">--MS/TMS--</option>
							  <option value="MS"<?php if($surat_penerimabeasiswa_hasil== 'MS'){ echo 'selected'; }?>>MS</option>
							  <option value="TMS"<?php if($surat_penerimabeasiswa_hasil== 'TMS'){ echo 'selected'; }?>>TMS</option>
							</select>
						</div>
						<div class="col-sm-4 text-secondary">
							<input type="text" class="form-control" id="surat_penerimabeasiswa_alasan" name="surat_penerimabeasiswa_alasan" value="<?php echo $surat_penerimabeasiswa_alasan; ?>" placeholder="Alasan"/>
						</div>
					</div>	
				<hr>
					<div class="row">
						<div class="col-sm-4">
							<h6 class="mb-0">5. Kalender akademik atau keterangan mulai perkuliahan</h6>
						</div>
						<div class="col-sm-2 text-secondary">
							<?php if ($ketmulai<>'') {

								$namaFile = basename($ketmulai);
								$urlFile = $folderBaseNip . rawurlencode($namaFile);

								echo "<td>";
								echo "<a href=\"$urlFile\" target=\"_blank\">View</a>";
								echo "</td>";
							}else{
								echo "<td>";
								echo "Tidak Upload File";
								echo "</td>";
							}
						?>
						</div>
						<div class="col-sm-2 text-secondary">
							<select id="kalender_akademik_hasil" name="kalender_akademik_hasil" class="custom-select" aria-label="Default select example" data-target="kalender_akademik_alasan" onchange="toggleMS(this)">
							  <option value="">--MS/TMS--</option>
							  <option value="MS"<?php if($kalender_akademik_hasil== 'MS'){ echo 'selected'; }?>>MS</option>
							  <option value="TMS"<?php if($kalender_akademik_hasil== 'TMS'){ echo 'selected'; }?>>TMS</option>
							</select>
						</div>
						<div class="col-sm-4 text-secondary">
							<input type="text" class="form-control" id="kalender_akademik_alasan" name="kalender_akademik_alasan" value="<?php echo $kalender_akademik_alasan; ?>" placeholder="Alasan"/>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-sm-4">
							<h6 class="mb-0">6. Surat Akreditasi minimal “B” (baik) (khusus untuk Dalam Negeri)</h6>
						</div>
						<div class="col-sm-2 text-secondary">
							<?php if ($suratakre<>'') {

								$namaFile = basename($suratakre);
								$urlFile = $folderBaseNip . rawurlencode($namaFile);

								echo "<td>";
								echo "<a href=\"$urlFile\" target=\"_blank\">View</a>";
								echo "</td>";
							}else{
								echo "<td>";
								echo "Tidak Upload File";
								echo "</td>";
							}
						?>
						</div>
						<div class="col-sm-2 text-secondary">
							<select id="surat_akreditasi_hasil" name="surat_akreditasi_hasil" class="custom-select" aria-label="Default select example" data-target="surat_akreditasi_alasan" onchange="toggleMS(this)">
							  <option value="">--MS/TMS--</option>
							  <option value="MS"<?php if($surat_akreditasi_hasil== 'MS'){ echo 'selected'; }?>>MS</option>
							  <option value="TMS"<?php if($surat_akreditasi_hasil== 'TMS'){ echo 'selected'; }?>>TMS</option>
							</select>
						</div>
						<div class="col-sm-4 text-secondary">
							<input type="text" class="form-control" id="surat_akreditasi_alasan" name="surat_akreditasi_alasan" value="<?php echo $surat_akreditasi_alasan; ?>" placeholder="Alasan"/>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-sm-4">
							<h6 class="mb-0">7. Surat keterangan sehat jasmani</h6>
						</div>
						<div class="col-sm-2 text-secondary">
							<?php if ($ketsehat_jasmani<>'') {

								$namaFile = basename($ketsehat_jasmani);
								$urlFile = $folderBaseNip . rawurlencode($namaFile);

								echo "<td>";
								echo "<a href=\"$urlFile\" target=\"_blank\">View</a>";
								echo "</td>";
							}else{
								echo "<td>";
								echo "Tidak Upload File";
								echo "</td>";
							}
						?>
						</div>
						<div class="col-sm-2 text-secondary">
							<select id="surat_jasmani_hasil" name="surat_jasmani_hasil" class="custom-select" aria-label="Default select example" data-target="surat_jasmani_alasan" onchange="toggleMS(this)">
							  <option value="">--MS/TMS--</option>
							  <option value="MS"<?php if($surat_jasmani_hasil== 'MS'){ echo 'selected'; }?>>MS</option>
							  <option value="TMS"<?php if($surat_jasmani_hasil== 'TMS'){ echo 'selected'; }?>>TMS</option>
							</select>
						</div>
						<div class="col-sm-4 text-secondary">
							<input type="text" class="form-control" id="surat_jasmani_alasan" name="surat_jasmani_alasan" value="<?php echo $surat_jasmani_alasan; ?>" placeholder="Alasan"/>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-sm-4">
							<h6 class="mb-0">8. Surat keterangan sehat rohani/jiwa</h6>
						</div>
						<div class="col-sm-2 text-secondary">
							<?php if ($ketsehat_rohani<>'') {

								$namaFile = basename($ketsehat_rohani);
								$urlFile = $folderBaseNip . rawurlencode($namaFile);

								echo "<td>";
								echo "<a href=\"$urlFile\" target=\"_blank\">View</a>";
								echo "</td>";
							}else{
								echo "<td>";
								echo "Tidak Upload File";
								echo "</td>";
							}
						?>
						</div>
						<div class="col-sm-2 text-secondary">
							<select id="surat_rohani_hasil" name="surat_rohani_hasil" class="custom-select" aria-label="Default select example" data-target="surat_rohani_alasan" onchange="toggleMS(this)">
							  <option value="">--MS/TMS--</option>
							  <option value="MS"<?php if($surat_rohani_hasil== 'MS'){ echo 'selected'; }?>>MS</option>
							  <option value="TMS"<?php if($surat_rohani_hasil== 'TMS'){ echo 'selected'; }?>>TMS</option>
							</select>
						</div>
						<div class="col-sm-4 text-secondary">
							<input type="text" class="form-control" id="surat_rohani_alasan" name="surat_rohani_alasan" value="<?php echo $surat_rohani_alasan; ?>" placeholder="Alasan"/>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-sm-4">
							<h6 class="mb-0">9. Salinan Ijazah terakhir</h6>
						</div>
						<div class="col-sm-2 text-secondary">
							<?php if ($ijazahterakhir<>'') {

								$namaFile = basename($ijazahterakhir);
								$urlFile = $folderBaseNip . rawurlencode($namaFile);

								echo "<td>";
								echo "<a href=\"$urlFile\" target=\"_blank\">View</a>";
								echo "</td>";
							}else{
								echo "<td>";
								echo "Tidak Upload File";
								echo "</td>";
							}
						?>
						</div>
						<div class="col-sm-2 text-secondary">
							<select id="ijazah_terakhir_hasil" name="ijazah_terakhir_hasil" class="custom-select" aria-label="Default select example" data-target="ijazah_terakhir_alasan" onchange="toggleMS(this)">
							  <option value="">--MS/TMS--</option>
							  <option value="MS"<?php if($ijazah_terakhir_hasil== 'MS'){ echo 'selected'; }?>>MS</option>
							  <option value="TMS"<?php if($ijazah_terakhir_hasil== 'TMS'){ echo 'selected'; }?>>TMS</option>
							</select>
						</div>
						<div class="col-sm-4 text-secondary">
							<input type="text" class="form-control" id="ijazah_terakhir_alasan" name="ijazah_terakhir_alasan" value="<?php echo $ijazah_terakhir_alasan; ?>" placeholder="Alasan"/>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-sm-4">
							<h6 class="mb-0">10. Salinan Transkrip Nilai</h6>
						</div>
						<div class="col-sm-2 text-secondary">
							<?php if ($transkripnilai<>'') {

								$namaFile = basename($transkripnilai);
								$urlFile = $folderBaseNip . rawurlencode($namaFile);

								echo "<td>";
								echo "<a href=\"$urlFile\" target=\"_blank\">View</a>";
								echo "</td>";
							}else{
								echo "<td>";
								echo "Tidak Upload File";
								echo "</td>";
							}
						?>
						</div>
						<div class="col-sm-2 text-secondary">
							<select id="transkrip_nilai_hasil" name="transkrip_nilai_hasil" class="custom-select" aria-label="Default select example" data-target="transkrip_nilai_alasan" onchange="toggleMS(this)">
							  <option value="">--MS/TMS--</option>
							  <option value="MS"<?php if($transkrip_nilai_hasil== 'MS'){ echo 'selected'; }?>>MS</option>
							  <option value="TMS"<?php if($transkrip_nilai_hasil== 'TMS'){ echo 'selected'; }?>>TMS</option>
							</select>
						</div>
						<div class="col-sm-4 text-secondary">
							<input type="text" class="form-control" id="transkrip_nilai_alasan" name="transkrip_nilai_alasan" value="<?php echo $transkrip_nilai_alasan; ?>" placeholder="Alasan"/>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-sm-4">
							<h6 class="mb-0">11. SKP N-1</h6>
						</div>
						<div class="col-sm-2 text-secondary">
							<?php if ($skp2<>'') {

								$namaFile = basename($skp2);
								$urlFile = $folderBaseNip . rawurlencode($namaFile);

								echo "<td>";
								echo "<a href=\"$urlFile\" target=\"_blank\">View</a>";
								echo "</td>";
							}else{
								echo "<td>";
								echo "Tidak Upload File";
								echo "</td>";
							}
						?>
						</div>
						<div class="col-sm-2 text-secondary">
							<select id="skp_2_hasil" name="skp_2_hasil" class="custom-select" aria-label="Default select example" data-target="skp_2_alasan" onchange="toggleMS(this)">
							  <option value="">--MS/TMS--</option>
							  <option value="MS"<?php if($skp_2_hasil== 'MS'){ echo 'selected'; }?>>MS</option>
							  <option value="TMS"<?php if($skp_2_hasil== 'TMS'){ echo 'selected'; }?>>TMS</option>
							</select>
						</div>
						<div class="col-sm-4 text-secondary">
							<input type="text" class="form-control" id="skp_2_alasan" name="skp_2_alasan" value="<?php echo $skp_2_alasan; ?>" placeholder="Alasan"/>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-sm-4">
							<h6 class="mb-0">12. SKP N-2</h6>
						</div>
						<div class="col-sm-2 text-secondary">
							<?php if ($skp1<>'') {

								$namaFile = basename($skp1);
								$urlFile = $folderBaseNip . rawurlencode($namaFile);

								echo "<td>";
								echo "<a href=\"$urlFile\" target=\"_blank\">View</a>";
								echo "</td>";
							}else{
								echo "<td>";
								echo "Tidak Upload File";
								echo "</td>";
							}
						?>
						</div>
						<div class="col-sm-2 text-secondary">
							<select id="skp_1_hasil" name="skp_1_hasil" class="custom-select" aria-label="Default select example" data-target="skp_1_alasan" onchange="toggleMS(this)">
							  <option value="">--MS/TMS--</option>
							  <option value="MS"<?php if($skp_1_hasil== 'MS'){ echo 'selected'; }?>>MS</option>
							  <option value="TMS"<?php if($skp_1_hasil== 'TMS'){ echo 'selected'; }?>>TMS</option>
							</select>
						</div>
						<div class="col-sm-4 text-secondary">
							<input type="text" class="form-control" id="skp_1_alasan" name="skp_1_alasan" value="<?php echo $skp_1_alasan; ?>" placeholder="Alasan"/>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-sm-4">
							<h6 class="mb-0">13. Surat Keterangan tidak sedang menjalani hukuman disiplin</h6>
						</div>
						<div class="col-sm-2 text-secondary">
							<?php if ($surathukdis<>'') {

								$namaFile = basename($surathukdis);
								$urlFile = $folderBaseNip . rawurlencode($namaFile);

								echo "<td>";
								echo "<a href=\"$urlFile\" target=\"_blank\">View</a>";
								echo "</td>";
							}else{
								echo "<td>";
								echo "Tidak Upload File";
								echo "</td>";
							}
						?>
						</div>
						<div class="col-sm-2 text-secondary">
							<select id="surat_hukdis_hasil" name="surat_hukdis_hasil" class="custom-select" aria-label="Default select example" data-target="surat_hukdis_alasan" onchange="toggleMS(this)">
							  <option value="">--MS/TMS--</option>
							  <option value="MS"<?php if($surat_hukdis_hasil== 'MS'){ echo 'selected'; }?>>MS</option>
							  <option value="TMS"<?php if($surat_hukdis_hasil== 'TMS'){ echo 'selected'; }?>>TMS</option>
							</select>
						</div>
						<div class="col-sm-4 text-secondary">
							<input type="text" class="form-control" id="surat_hukdis_alasan" name="surat_hukdis_alasan" value="<?php echo $surat_hukdis_alasan; ?>" placeholder="Alasan"/>
						</div>
					</div>

					<?php if (false): ?>
					<hr>
					<div class="row">
						<div class="col-sm-4">
							<h6 class="mb-0">13. Surat bersedia diberhentikan dari jabatan Struktural</h6>
						</div>
						<div class="col-sm-2 text-secondary">
							<?php if ($suratberhentistruktural <> '') {
								$namaFile = basename($suratberhentistruktural);
								$urlFile = $folderBaseNip . rawurlencode($namaFile);
								echo "<a href=\"$urlFile\" target=\"_blank\">View</a>";
							} else {
								echo "Tidak Upload File";
							} ?>
						</div>
						<div class="col-sm-2 text-secondary">
							<select class="custom-select">
								<option>--MS/TMS--</option>
							</select>
						</div>
						<div class="col-sm-4 text-secondary">
							<input type="text" class="form-control" placeholder="Alasan">
						</div>
					</div>

					<hr>

					<div class="row">
						<div class="col-sm-4">
							<h6 class="mb-0">14. Surat bersedia diberhentikan dari jabatan fungsional</h6>
						</div>
						<div class="col-sm-2 text-secondary">
							<?php if ($suratberhentifungsional <> '') {
								$namaFile = basename($suratberhentifungsional);
								$urlFile = $folderBaseNip . rawurlencode($namaFile);
								echo "<a href=\"$urlFile\" target=\"_blank\">View</a>";
							} else {
								echo "Tidak Upload File";
							} ?>
						</div>
						<div class="col-sm-2 text-secondary">
							<select class="custom-select">
								<option>--MS/TMS--</option>
							</select>
						</div>
						<div class="col-sm-4 text-secondary">
							<input type="text" class="form-control" placeholder="Alasan">
						</div>
					</div>
					<?php endif; ?>


					<hr>
					<div class="row">
						<div class="col-sm-4">
							<h6 class="mb-0">14. Surat Pernyataan bersedia ditempatkan dimana saja pada Unit Kerja bagi 
     									Pegawai Tugas Belajar setelah menyelesaikan Program Studi (bermaterai)</h6>
						</div>
						<div class="col-sm-2 text-secondary">
							<?php if ($surat_dimanasaja<>'') {

								$namaFile = basename($surat_dimanasaja);
								$urlFile = $folderBaseNip . rawurlencode($namaFile);

								echo "<td>";
								echo "<a href=\"$urlFile\" target=\"_blank\">View</a>";
								echo "</td>";
							}else{
								echo "<td>";
								echo "Tidak Upload File";
								echo "</td>";
							}
						?>
						</div>
						<div class="col-sm-2 text-secondary">
							<select id="surat_dimanasaja_hasil" name="surat_dimanasaja_hasil" class="custom-select" aria-label="Default select example" data-target="surat_dimanasaja_alasan" onchange="toggleMS(this)">
							  <option value="">--MS/TMS--</option>
							  <option value="MS"<?php if($surat_dimanasaja_hasil== 'MS'){ echo 'selected'; }?>>MS</option>
							  <option value="TMS"<?php if($surat_dimanasaja_hasil== 'TMS'){ echo 'selected'; }?>>TMS</option>
							</select>
						</div>
						<div class="col-sm-4 text-secondary">
							<input type="text" class="form-control" id="surat_dimanasaja_alasan" name="surat_dimanasaja_alasan" value="<?php echo $surat_dimanasaja_alasan; ?>" placeholder="Alasan"/>
						</div>
					</div>
				</div>
				

					<!-- tombol submit -->
					<div class="mb-3 text-center d-grid gap-md-2 mx-auto">
						<button type="submit" name="simpan" class="btn btn-secondary font-weight-bold">Simpan Verifikasi</button>
					</div>
				</div>        
        </fieldset>
    </form>
</div>

<script>
(function () {

  function applyMS(selectEl) {
    if (!selectEl) return;

    var id = selectEl.getAttribute('data-target');
    if (!id) return;

    var alasanInput = document.getElementById(id);
    if (!alasanInput) return;

    var alasanCol = alasanInput.closest('.col-sm-4') || alasanInput.parentElement;
    var v = (selectEl.value || '').toString().trim().toUpperCase();

    if (v === 'MS') {
      if (alasanCol) alasanCol.style.display = 'none';
      else alasanInput.style.display = 'none';
      alasanInput.value = '';
    } else {
      if (alasanCol) alasanCol.style.display = '';
      else alasanInput.style.display = '';
    }
  }

  function initAll() {
    var sels = document.querySelectorAll('select[data-target]');
    for (var i = 0; i < sels.length; i++) {
      applyMS(sels[i]);
    }
  }

  document.addEventListener('DOMContentLoaded', function () {
    initAll();
    setTimeout(initAll, 50);
  });

  window.addEventListener('load', function () {
    initAll();
    setTimeout(initAll, 50);
  });

  document.addEventListener('change', function (e) {
    var t = e.target;
    if (t && t.matches && t.matches('select[data-target]')) {
      applyMS(t);
    }
  });

})();
</script>

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
