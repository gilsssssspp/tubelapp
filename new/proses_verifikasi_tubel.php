<?php

session_start();
// import file koneksi.php
include '../koneksi.php';

// inisialisasi variabel id dan status
$id = $_GET['iddaftar'];
$user = $_SESSION['nama'];

	//$suratpengantar = $_POST['suratpengantar'];
	$surat_pengantar_hasil = $_POST['surat_pengantar_hasil'];
	$surat_pengantar_alasan = $_POST['surat_pengantar_alasan'];
	//$suratloa = $_POST['suratloa'];
	$surat_loa_hasil = $_POST['surat_loa_hasil'];
	$surat_loa_alasan = $_POST['surat_loa_alasan'];
	//$buktibeasiswa = $_POST['buktibeasiswa'];
	$surat_penerimabeasiswa_hasil = $_POST['surat_penerimabeasiswa_hasil'];
	$surat_penerimabeasiswa_alasan = $_POST['surat_penerimabeasiswa_alasan'];
	//$ketmulai = $_POST['ketmulai'];
	$kalender_akademik_hasil = $_POST['kalender_akademik_hasil'];
	$kalender_akademik_alasan = $_POST['kalender_akademik_alasan'];
	//$suratakre = $_POST['suratakre'];
	$surat_akreditasi_hasil = $_POST['surat_akreditasi_hasil'];
	$surat_akreditasi_alasan = $_POST['surat_akreditasi_alasan'];
	//$ketsehat_jasmani = $_POST['ketsehat_jasmani'];
	$surat_jasmani_hasil = $_POST['surat_jasmani_hasil'];
	$surat_jasmani_alasan = $_POST['surat_jasmani_alasan'];
	//$ketsehat_rohani = $_POST['ketsehat_rohani'];
	$surat_rohani_hasil = $_POST['surat_rohani_hasil'];
	$surat_rohani_alasan = $_POST['surat_rohani_alasan'];
	//$ijazahterakhir = $_POST['ijazahterakhir'];
	$ijazah_terakhir_hasil = $_POST['ijazah_terakhir_hasil'];
	$ijazah_terakhir_alasan = $_POST['ijazah_terakhir_alasan'];
	//$transkripnilai = $_POST['transkripnilai'];
	$transkrip_nilai_hasil = $_POST['transkrip_nilai_hasil'];
	$transkrip_nilai_alasan = $_POST['transkrip_nilai_alasan'];
	//$skp1 = $_POST['skp1'];
	$skp_1_hasil = $_POST['skp_1_hasil'];
	$skp_1_alasan = $_POST['skp_1_alasan'];
	//$skp2 = $_POST['skp2'];
	$skp_2_hasil = $_POST['skp_2_hasil'];
	$skp_2_alasan = $_POST['skp_2_alasan'];
	//$surathukdis = $_POST['surathukdis'];
	$surat_hukdis_hasil = $_POST['surat_hukdis_hasil'];
	$surat_hukdis_alasan = $_POST['surat_hukdis_alasan'];
	//$suratberhentistruktural = $_POST['suratberhentistruktural'];
	$surat_berhentistruktural_hasil = $_POST['surat_berhentistruktural_hasil'];
	$surat_berhentistruktural_alasan = $_POST['surat_berhentistruktural_alasan'];
	//$suratberhentifungsional = $_POST['suratberhentifungsional'];
	$surat_berhentiJF_hasil = $_POST['surat_berhentiJF_hasil'];
	$surat_berhentiJF_alasan = $_POST['surat_berhentiJF_alasan'];
	//$surat_dimanasaja = $_POST['surat_dimanasaja'];
	$surat_dimanasaja_hasil = $_POST['surat_dimanasaja_hasil'];
	$surat_dimanasaja_alasan = $_POST['surat_dimanasaja_alasan'];
	//$rekomibel = $_POST['rekomibel'];
	//$pernyataanibel = $_POST['pernyataanibel'];

$status = "Menunggu SK";
$tms=0;
//echo $surat_pengantar_hasil; die;
if (($surat_pengantar_hasil=='') or ($surat_loa_hasil=='') or ($surat_penerimabeasiswa_hasil=='')
	or ($ijazah_terakhir_hasil=='') or ($transkrip_nilai_hasil=='') or ($skp_1_hasil=='') 
	or ($skp_2_hasil=='') or ($kalender_akademik_hasil=='')
	or ($surat_akreditasi_hasil=='') or ($surat_jasmani_hasil=='') or ($surat_rohani_hasil=='') 
	or ($surat_hukdis_hasil=='') 
	or ($surat_dimanasaja_hasil=='')) {
	echo "<script>alert('Berkas ada yang belum dilakukan verifikasi , Silahkan cek kembali')</script>";
	echo "<script>window.location.href = '../verifikasi_upload_tubel.php?iddaftar=$id' </script>";

}else{
	// mengubah data status pendaftaran menjadi Diterima
	if (($surat_pengantar_hasil=='MS') and ($surat_loa_hasil=='MS') and ($surat_penerimabeasiswa_hasil=='MS')
	and ($ijazah_terakhir_hasil=='MS') and ($transkrip_nilai_hasil=='MS') and ($skp_1_hasil=='MS') 
	and ($skp_2_hasil=='MS') and ($kalender_akademik_hasil=='MS')
	and ($surat_akreditasi_hasil=='MS') and ($surat_jasmani_hasil=='MS') and ($surat_rohani_hasil=='MS') 
	and ($surat_hukdis_hasil=='MS') and ($surat_berhentistruktural_hasil=='MS') and ($surat_berhentiJF_hasil=='MS') 
	and ($surat_dimanasaja_hasil=='MS')) {
			$hasil_verifikasi='Lulus Verifikasi';
	}else{
			$hasil_verifikasi='Tidak Lulus Verifikasi';
	}
	
	if ($surat_dimanasaja_hasil=='' and $surat_dimanasaja_hasil=='TMS')  {
		$tms=1;
		echo "<script>alert('alasan TMS Bersedia ditempatkan di mana saja tidak diisi')</script>";	
	}
	
	if ($surat_berhentiJF_alasan=='' and $surat_berhentiJF_hasil=='TMS')  {
		$tms=1;
		echo "<script>alert('alasan TMS Surat Bersedia Berhenti dari Jab Fungsional tidak diisi')</script>";	
	}
	
	if ($surat_berhentistruktural_alasan=='' and $surat_berhentistruktural_hasil=='TMS')  {
		$tms=1;
		echo "<script>alert('alasan TMS Surat Bersedia Berhenti dari Jab Struktural tidak diisi')</script>";	
	}
	
	if ($surat_hukdis_alasan=='' and $surat_hukdis_hasil=='TMS')  {
		$tms=1;
		echo "<script>alert('alasan TMS Surat Bebas Hukuman Disiplin tidak diisi')</script>";	
	}
	
	if ($surat_jasmani_hasil=='' and $surat_jasmani_hasil=='TMS')  {
		$tms=1;
		echo "<script>alert('alasan TMS Surat Sehat Jasmani tidak diisi')</script>";	
	}
	
	if ($surat_rohani_alasan=='' and $surat_rohani_hasil=='TMS')  {
		$tms=1;
		echo "<script>alert('alasan TMS Surat Sehat Rohani tidak diisi')</script>";	
	}
	
	if ($surat_akreditasi_alasan=='' and $surat_akreditasi_hasil=='TMS')  {
		$tms=1;
		echo "<script>alert('alasan TMS Surat Akreditasi tidak diisi')</script>";	
	}
	
	if ($kalender_akademik_alasan=='' and $kalender_akademik_hasil=='TMS')  {
		$tms=1;
		echo "<script>alert('alasan TMS Kalender Akademik tidak diisi')</script>";	
	}
	
	if ($skp_1_alasan=='' and $skp_1_hasil=='TMS')  {
		$tms=1;
		echo "<script>alert('alasan TMS SKP N-1 tidak diisi')</script>";	
	}
	
	if ($skp_2_alasan=='' and $skp_2_hasil=='TMS')  {
		$tms=1;
		echo "<script>alert('alasan TMS SKP N-2 tidak diisi')</script>";	
	}
	
	if ($transkrip_nilai_alasan=='' and $transkrip_nilai_hasil=='TMS')  {
		$tms=1;
		echo "<script>alert('alasan TMS Transkrip tidak diisi')</script>";	
	}
	
	if ($ijazah_terakhir_alasan=='' and $ijazah_terakhir_hasil=='TMS')  {
		$tms=1;
		echo "<script>alert('alasan TMS ijazah terakhir tidak diisi')</script>";	
	}
	
	if ($surat_pengantar_alasan=='' and $surat_pengantar_hasil=='TMS')  {
		$tms=1;
		echo "<script>alert('alasan TMS Surat Pengantar tidak diisi')</script>";	
	}
	
	if ($surat_loa_alasan=='' and $surat_loa_hasil=='TMS')  {
		$tms=1;
		echo "<script>alert('alasan TMS Letter of Acceptance (LOA) tidak diisi')</script>";	
	}
		
	if ($surat_penerimabeasiswa_alasan=='' and $surat_penerimabeasiswa_hasil=='TMS') {
		$tms=1;
		echo "<script>alert('alasan TMS Surat bukti penerima Beasiswa dari donatur pemberi beasiswa tidak diisi')</script>";	
	}
		
	
	if ($tms==0) {
			//$status='Proses Verifikasi';
			$str = "UPDATE pendaftaran SET  
			surat_pengantar_hasil='$surat_pengantar_hasil', surat_pengantar_alasan='$surat_pengantar_alasan',
			surat_loa_hasil='$surat_loa_hasil', surat_loa_alasan='$surat_loa_alasan',
			surat_penerimabeasiswa_hasil='$surat_penerimabeasiswa_hasil', surat_penerimabeasiswa_alasan='$surat_penerimabeasiswa_alasan',
			kalender_akademik_hasil='$kalender_akademik_hasil', kalender_akademik_alasan='$kalender_akademik_alasan',
			surat_akreditasi_hasil='$surat_akreditasi_hasil', surat_akreditasi_alasan='$surat_akreditasi_alasan',
			surat_jasmani_hasil='$surat_jasmani_hasil', surat_jasmani_alasan='$surat_jasmani_alasan',
			surat_rohani_hasil='$surat_rohani_hasil', surat_rohani_alasan='$surat_rohani_alasan',
			ijazah_terakhir_hasil='$ijazah_terakhir_hasil', ijazah_terakhir_alasan='$ijazah_terakhir_alasan',
			transkrip_nilai_hasil='$transkrip_nilai_hasil', transkrip_nilai_alasan='$transkrip_nilai_alasan',
			skp_1_hasil='$skp_1_hasil', skp_1_alasan='$skp_1_alasan',
			skp_2_hasil='$skp_2_hasil', skp_2_alasan='$skp_2_alasan',
			surat_hukdis_hasil='$surat_hukdis_hasil', surat_hukdis_alasan='$surat_hukdis_alasan',
			surat_berhentistruktural_hasil='$surat_berhentistruktural_hasil', surat_berhentistruktural_alasan='$surat_berhentistruktural_alasan',
			surat_berhentiJF_hasil='$surat_berhentiJF_hasil', surat_berhentiJF_alasan='$surat_berhentiJF_alasan',
			surat_dimanasaja_hasil='$surat_dimanasaja_hasil', surat_hukdis_alasan='$surat_dimanasaja_alasan',
			tgl_verifikasi=now(), pic_verifikasi='$user', hasil_verifikasi='$hasil_verifikasi', status_usul ='$status'
			WHERE id ='$id'";
			
			$query = mysqli_query ($db, $str);
			
			//  fungsi pengecekan $query 
			if($query){
				echo "<script>alert('Simpan Verifikasi Berhasil')</script>";
				// jika berhasil load ke halaman index.php
				echo "<meta http-equiv='refresh' content='0; url=../verifikasi.php'>";
			}else{

				// jika gagal tampilkan alert Gagal
				echo "$str <script type='text/javascript'>
				onload =function(){
					alert('Simpan Verifikasi Gagal');
				}
				</script>";
			}
		}else{
			echo "<script>window.location.href = '../verifikasi_upload_tubel.php?iddaftar=$id' </script>";
		}
}


?>