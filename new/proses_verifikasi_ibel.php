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
	//$ketmulai = $_POST['ketmulai'];
	$kalender_akademik_hasil = $_POST['kalender_akademik_hasil'];
	$kalender_akademik_alasan = $_POST['kalender_akademik_alasan'];
	//$suratakre = $_POST['suratakre'];
	$surat_akreditasi_hasil = $_POST['surat_akreditasi_hasil'];
	$surat_akreditasi_alasan = $_POST['surat_akreditasi_alasan'];
	//$skp1 = $_POST['skp1'];
	$skp_1_hasil = $_POST['skp_1_hasil'];
	$skp_1_alasan = $_POST['skp_1_alasan'];
	//$skp2 = $_POST['skp2'];
	$skp_2_hasil = $_POST['skp_2_hasil'];
	$skp_2_alasan = $_POST['skp_2_alasan'];
	//$surathukdis = $_POST['surathukdis'];
	$surat_hukdis_hasil = $_POST['surat_hukdis_hasil'];
	$surat_hukdis_alasan = $_POST['surat_hukdis_alasan'];
	
	//$surat_dimanasaja = $_POST['surat_dimanasaja'];
	$surat_rekomibel_hasil = $_POST['surat_rekomibel_hasil'];
	$surat_rekomibel_alasan = $_POST['surat_rekomibel_alasan'];
	$surat_pernyataanibel_hasil = $_POST['surat_pernyataanibel_hasil'];
	$surat_pernyataanibel_alasan = $_POST['surat_pernyataanibel_alasan'];
	//$rekomibel = $_POST['rekomibel'];
	//$pernyataanibel = $_POST['pernyataanibel'];

$status = "Menunggu SK";
$tms=0;
//echo $surat_pengantar_hasil; die;
if (($surat_pengantar_hasil=='') or ($surat_loa_hasil=='') or ($skp_1_hasil=='') 
	or ($skp_2_hasil=='') or ($kalender_akademik_hasil=='')
	or ($surat_akreditasi_hasil=='') or ($surat_hukdis_hasil=='') 
	or ($surat_rekomibel_hasil=='') or ($surat_pernyataanibel_hasil=='')) {
	echo "<script>alert('Berkas ada yang belum dilakukan verifikasi , Silahkan cek kembali')</script>";
	echo "<script>window.location.href = '../verifikasi_upload_ibel.php?iddaftar=$id' </script>";

}else{
	// mengubah data status pendaftaran menjadi Diterima
	if (($surat_pengantar_hasil=='MS') and ($surat_loa_hasil=='MS') and ($skp_1_hasil=='MS') 
	and ($skp_2_hasil=='MS') and ($kalender_akademik_hasil=='MS')
	and ($surat_akreditasi_hasil=='MS') and ($surat_hukdis_hasil=='MS') 
	and ($surat_rekomibel_hasil=='MS') and ($surat_pernyataanibel_hasil=='MS')) {
			$hasil_verifikasi='Lulus Verifikasi';
	}else{
			$hasil_verifikasi='Tidak Lulus Verifikasi';
	}
	
	if ($surat_rekomibel_alasan=='' and $surat_rekomibel_hasil=='TMS')  {
		$tms=1;
		echo "<script>alert('alasan TMS Bersedia ditempatkan di mana saja tidak diisi')</script>";	
	}
	
	if ($surat_pernyataanibel_alasan=='' and $surat_pernyataanibel_hasil=='TMS')  {
		$tms=1;
		echo "<script>alert('alasan TMS Bersedia ditempatkan di mana saja tidak diisi')</script>";	
	}
	
	
	if ($surat_hukdis_alasan=='' and $surat_hukdis_hasil=='TMS')  {
		$tms=1;
		echo "<script>alert('alasan TMS Surat Bebas Hukuman Disiplin tidak diisi')</script>";	
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
	
	
	if ($surat_loa_alasan=='' and $surat_loa_hasil=='TMS')  {
		$tms=1;
		echo "<script>alert('alasan TMS Letter of Acceptance (LOA) tidak diisi')</script>";	
	}
		
	if ($surat_pengantar_alasan=='' and $surat_pengantar_hasil=='TMS')  {
		$tms=1;
		echo "<script>alert('alasan TMS Surat Pengantar tidak diisi')</script>";	
	}
	
		
	
	if ($tms==0) {
			//$status='Proses Verifikasi';
			$str = "UPDATE pendaftaran SET  
			surat_pengantar_hasil='$surat_pengantar_hasil', surat_pengantar_alasan='$surat_pengantar_alasan',
			surat_loa_hasil='$surat_loa_hasil', surat_loa_alasan='$surat_loa_alasan',
			kalender_akademik_hasil='$kalender_akademik_hasil', kalender_akademik_alasan='$kalender_akademik_alasan',
			surat_akreditasi_hasil='$surat_akreditasi_hasil', surat_akreditasi_alasan='$surat_akreditasi_alasan',
			skp_1_hasil='$skp_1_hasil', skp_1_alasan='$skp_1_alasan',
			skp_2_hasil='$skp_2_hasil', skp_2_alasan='$skp_2_alasan',
			surat_hukdis_hasil='$surat_hukdis_hasil', surat_hukdis_alasan='$surat_hukdis_alasan',
			surat_rekomibel_hasil='$surat_rekomibel_hasil', surat_rekomibel_alasan='$surat_rekomibel_alasan',
			surat_pernyataanibel_hasil='$surat_pernyataanibel_hasil', surat_pernyataanibel_alasan='$surat_pernyataanibel_alasan',
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
			echo "<script>window.location.href = '../verifikasi_upload_ibel.php?iddaftar=$id' </script>";
		}
}


?>