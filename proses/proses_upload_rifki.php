<?php
session_start();


// import file koneksi
include '../koneksi.php';
$nip = $_SESSION['nip'];
$iddaftar = $_GET['iddaftar'];
$tubelibel = $_GET['tubelibel'];


// fungsi ketika tombol Simpan ditekan
if(isset($_POST['cetak'])){
	echo "<meta http-equiv='refresh' content='0; url=../status.php'>";
}

if(isset($_POST['simpan'])){
    extract($_POST);

	// menginisialisasi variabel dengan data yang diinputkan
	
	$foldername = $_SESSION['nip'];
    $base = __DIR__ . '/../uploads/';

    // buat folder uploads jika belum ada
    if (!is_dir($base)) {
        mkdir($base, 0777, true);
    }

    // buat folder user
    $structure = $base . $foldername . '/';

    if (!is_dir($structure)) {
        mkdir($structure, 0777, true);
    }
	
$nip = $_SESSION['nip'];
$suratpengantar = '';
$suratloa = '';
$buktibeasiswa = '';
$ketmulai = '';
$suratakre = '';
$ketsehat_jasmani = '';
$ketsehat_rohani = '';
$ijazahterakhir = '';
$transkripnilai = '';
$skp2 = '';
$skp2 = '';
$surathukdis = '';
$suratberhentistruktural = '';
$suratberhentifungsional = '';
$perjanianpenempatan = '';
$rekomibel = '';
$pernyataanibel = '';



$sql = mysqli_query($db, "SELECT * FROM pendaftaran where nip='$nip'");
while ($row = $sql->fetch_assoc()){
	$suratpengantar = $row['surat_pengantar'];
	$suratloa = $row['surat_loa'];
	$buktibeasiswa = $row['surat_penerimabeasiswa'];
	$ketmulai = $row['kalender_akademik'];
	$suratakre = $row['surat_akreditasi'];
	$ketsehat_jasmani = $row['surat_jasmani'];
	$ketsehat_rohani = $row['surat_rohani'];
	$ijazahterakhir = $row['ijazah_terakhir'];
	$transkripnilai = $row['transkrip_nilai'];
	$skp1 = $row['skp_1'];
	$skp2 = $row['skp_2'];
	$surathukdis = $row['surat_hukdis'];
	$suratberhentistruktural = $row['surat_berhentistruktural'];
	$suratberhentifungsional = $row['surat_berhentiJF'];
	$perjanianpenempatan = $row['surat_dimanasaja'];
	$rekomibel = $row['surat_rekomibel'];
	$pernyataanibel = $row['surat_pernyataanibel'];
	
}


	// ambil data file
	
	$pengantarfile = $_FILES['surat_pengantar']['name'];
	$pengantarsementara = $_FILES['surat_pengantar']['tmp_name'];
	
	$fileloa = $_FILES['surat_loa']['name'];
	$loasementara = $_FILES['surat_loa']['tmp_name'];
	
	$filebukti = $_FILES['surat_penerimabeasiswa']['name'];
	$buktisementara = $_FILES['surat_penerimabeasiswa']['tmp_name'];
	
	$filemulai = $_FILES['kalender_akademik']['name'];
	$mulaisementara = $_FILES['kalender_akademik']['tmp_name'];
	
	$fileakreditasi = $_FILES['surat_akreditasi']['name'];
	$akreditasisementara = $_FILES['surat_akreditasi']['tmp_name'];
	
	$filejasmani = $_FILES['surat_jasmani']['name'];
	$jasmanisementara = $_FILES['surat_jasmani']['tmp_name'];
	
	$filerohani = $_FILES['surat_rohani']['name'];
	$rohanisementara = $_FILES['surat_rohani']['tmp_name'];
	
	$fileijazahterakhir = $_FILES['ijazah_terakhir']['name'];
	$ijazahterakhirsementara = $_FILES['ijazah_terakhir']['tmp_name'];
	
	$filetranskripnilai = $_FILES['transkrip_nilai']['name'];
	$transkripnilaisementara = $_FILES['transkrip_nilai']['tmp_name'];

	$fileskp1 = $_FILES['skp_1']['name'];
	$skp1sementara = $_FILES['skp_1']['tmp_name'];
	
	$fileskp2 = $_FILES['skp_2']['name'];
	$skp2sementara = $_FILES['skp_2']['tmp_name'];
	
	$filesurathukdis = $_FILES['surat_hukdis']['name'];
	$surathukdissementara = $_FILES['surat_hukdis']['tmp_name'];

	$filestruktural = $_FILES['surat_berhentistruktural']['name'];
	$strukturalsementara = $_FILES['surat_berhentistruktural']['tmp_name'];

	$filefungsional = $_FILES['surat_berhentiJF']['name'];
	$fungsionalsementara = $_FILES['surat_berhentiJF']['tmp_name'];

	$filepenempatan = $_FILES['surat_dimanasaja']['name'];
	$penempatansementara = $_FILES['surat_dimanasaja']['tmp_name'];

	$filerekomibel = $_FILES['surat_rekomibel']['name'];
	$rekomibelsementara = $_FILES['surat_rekomibel']['tmp_name'];

	$filepernyataanibel = $_FILES['surat_pernyataanibel']['name'];
	$pernyataanibelsementara = $_FILES['surat_pernyataanibel']['tmp_name'];




	$uploadOk = 1;	
	$diruploads = $structure;
	
	if ($pengantarfile<>'') {
		$target_file = $diruploads . basename($_FILES["surat_pengantar"]["name"]);
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		
		// Check file size
		if ($_FILES["surat_pengantar"]["size"] > 1000000) {
		  $uploadOk = 0;
		  echo "<script>alert('ukuran file terlalu besar, maksimal 1 Mb')</script>";
		}

		if ($imageFileType != "jpg" && $imageFileType != "pdf" ) {
		  $uploadOk = 0;
		  echo "<script>alert('Jenis file yang diupload harus pdf atau jpg')</script>";
		}	
	}
	
	if ($fileloa<>'') {
		$target_loa = $diruploads . basename($_FILES["surat_loa"]["name"]);
		$imageFileType_loa = strtolower(pathinfo($target_loa,PATHINFO_EXTENSION));
		
		 if ($_FILES["surat_loa"]["size"] > 1000000) {
		  $uploadOk = 0;
		  echo "<script>alert('ukuran file terlalu besar, maksimal 1 Mb')</script>";
		}
	
		if ($imageFileType_loa != "jpg" && $imageFileType_loa != "pdf" ) {
		  $uploadOk = 0;
		  echo "<script>alert('Jenis file yang diupload harus pdf atau jpg')</script>";
		}
	}
	
	if ($filebukti<>'') {
		$target_bukti = $diruploads . basename($_FILES["surat_penerimabeasiswa"]["name"]);
		$imageFileType_bukti = strtolower(pathinfo($target_bukti,PATHINFO_EXTENSION));
		
		if ($_FILES["surat_penerimabeasiswa"]["size"] > 1000000) {
			$uploadOk = 0;
			echo "<script>alert('ukuran file surat_penerimabeasiswa terlalu besar, maksimal 1 Mb')</script>";
		}
		
		if ($imageFileType_bukti != "jpg" && $imageFileType_bukti != "pdf") {
		  $uploadOk = 0;
		  echo "<script>alert('Jenis file surat_penerimabeasiswa yang diupload harus jpg')</script>";
		}
	}
	
	if ($filemulai<>'') {
		$target_mulai = $diruploads . basename($_FILES["kalender_akademik"]["name"]);
		$imageFileType_mulai = strtolower(pathinfo($target_mulai,PATHINFO_EXTENSION));
		
		if ($_FILES["kalender_akademik"]["size"] > 1000000) {
		  $uploadOk = 0;
		  echo "<script>alert('ukuran file Kalender Akademik terlalu besar, maksimal 1 Mb')</script>";
		}
		
		if ($imageFileType_mulai != "jpg" && $imageFileType_mulai != "pdf") {
		  $uploadOk = 0;
		  echo "<script>alert('Jenis file Kalender Akademik yang diupload harus jpg atau pdf')</script>";
		}
	}
	
	if ($fileakreditasi<>'') {
		$target_akreditasi = $diruploads . basename($_FILES["surat_akreditasi"]["name"]);
		$imageFileType_akreditasi = strtolower(pathinfo($target_akreditasi,PATHINFO_EXTENSION));
		
		if ($_FILES["surat_akreditasi"]["size"] > 1000000) {
		  $uploadOk = 0;
		  echo "<script>alert('ukuran file akreditasi terlalu besar, maksimal 1 Mb')</script>";
		}
		if ($imageFileType_akreditasi != "jpg" && $imageFileType_akreditasi != "pdf") {
		  $uploadOk = 0;
		  echo "<script>alert('Jenis file akreditasi yang diupload harus jpg atau pdf')</script>";
		}
	}
	
	if ($filejasmani<>'') {
			$target_jasmani = $diruploads . basename($_FILES["surat_jasmani"]["name"]);
			$imageFileType_jasmani = strtolower(pathinfo($target_jasmani,PATHINFO_EXTENSION));
			
			if ($_FILES["surat_jasmani"]["size"] > 1000000) {
			  $uploadOk = 0;
			  echo "<script>alert('ukuran surat_jasmani terlalu besar, maksimal 1 Mb')</script>";
			}
			
			if ($imageFileType_jasmani != "jpg" && $imageFileType_jasmani != "pdf") {
				  $uploadOk = 0;
				  echo "<script>alert('Jenis file surat_jasmani yang diupload harus jpg atau pdf')</script>";
			}
	}

	if ($filerohani<>'') {
		$target_rohani = $diruploads . basename($_FILES["surat_rohani"]["name"]);
		$imageFileType_rohani = strtolower(pathinfo($target_rohani,PATHINFO_EXTENSION));
		if ($_FILES["surat_rohani"]["size"] > 1000000) {
		  $uploadOk = 0;
		  echo "<script>alert('ukuran surat_rohani terlalu besar, maksimal 1 Mb')</script>";
		}
		if ($imageFileType_rohani != "jpg" && $imageFileType_rohani != "pdf") {
		  $uploadOk = 0;
		  echo "<script>alert('Jenis file surat_rohani yang diupload harus jpg atau pdf')</script>";
		}
	}
	
	if ($fileijazahterakhir<>'') {
		$target_ijazahterakhir = $diruploads . basename($_FILES["ijazah_terakhir"]["name"]);
		$imageFileType_ijazahterakhir = strtolower(pathinfo($target_ijazahterakhir,PATHINFO_EXTENSION));
		if ($_FILES["ijazah_terakhir"]["size"] > 1000000) {
		  $uploadOk = 0;
		  echo "<script>alert('ukuran File ijazah_terakhir terlalu besar, maksimal 1 Mb')</script>";
		}
		if ($imageFileType_ijazahterakhir != "jpg" && $imageFileType_ijazahterakhir != "pdf") {
		  $uploadOk = 0;
		  echo "<script>alert('Jenis file ijazah_terakhir yang diupload harus jpg atau pdf')</script>";
		}
	}	
	
	
	if ($filetranskripnilai<>'') {
		$target_transkripnilai = $diruploads . basename($_FILES["transkrip_nilai"]["name"]);
		$imageFileType_transkripnilai = strtolower(pathinfo($target_transkripnilai,PATHINFO_EXTENSION));
		
		if ($_FILES["transkrip_nilai"]["size"] > 1000000) {
		  $uploadOk = 0;
		  echo "<script>alert('ukuran file transkrip_nilai terlalu besar, maksimal 1 Mb')</script>";
		}
		if ($imageFileType_transkripnilai != "jpg" && $imageFileType_transkripnilai != "pdf") {
			  $uploadOk = 0;
			  echo "<script>alert('Jenis file transkrip_nilai yang diupload harus jpg atau pdf')</script>";
		}
	}

	if ($fileskp1<>'') {
		$target_skp1 = $diruploads . basename($_FILES["skp_1"]["name"]);
		$imageFileType_skp1 = strtolower(pathinfo($target_skp1,PATHINFO_EXTENSION));
		
		if ($_FILES["skp_1"]["size"] > 1000000) {
		  $uploadOk = 0;
		  echo "<script>alert('ukuran file skp 2025 terlalu besar, maksimal 1 Mb')</script>";
		}
		if ($imageFileType_skp1 != "jpg" && $imageFileType_skp1 != "pdf") {
		  $uploadOk = 0;
		  echo "<script>alert('Jenis file skp 2025 yang diupload harus jpg atau pdf')</script>";
		}
	}	
	
	if ($fileskp2<>'') {
		$target_skp2 = $diruploads . basename($_FILES["skp_2"]["name"]);
		$imageFileType_skp2 = strtolower(pathinfo($target_skp2,PATHINFO_EXTENSION));
		
		if ($_FILES["skp_2"]["size"] > 1000000) {
		  $uploadOk = 0;
		  echo "<script>alert('ukuran file skp_2 terlalu besar, maksimal 1 Mb')</script>";
		}
		if ($imageFileType_skp2 != "jpg" && $imageFileType_skp2 != "pdf") {
		  $uploadOk = 0;
		  echo "<script>alert('Jenis file skp_2 yang diupload harus jpg atau pdf')</script>";
		}
	}	
	
	
	
	if ($filesurathukdis<>'') {
		$target_surathukdis = $diruploads . basename($_FILES["surat_hukdis"]["name"]);
		$imageFileType_surathukdis = strtolower(pathinfo($target_surathukdis,PATHINFO_EXTENSION));
		if ($_FILES["surat_hukdis"]["size"] > 1000000) {
		  $uploadOk = 0;
		  echo "<script>alert('ukuran file surat_hukdis terlalu besar, maksimal 1 Mb')</script>";
		}
		if ($imageFileType_surathukdis != "jpg" && $imageFileType_surathukdis != "pdf") {
		  $uploadOk = 0;
		  echo "<script>alert('Jenis file surat_hukdis yang diupload harus jpg atau pdf')</script>";
		}
	}

	if ($filestruktural<>'') {
		$target_struktural = $diruploads . basename($_FILES["surat_berhentistruktural"]["name"]);
		$imageFileType_struktural = strtolower(pathinfo($target_struktural,PATHINFO_EXTENSION));	
		if ($_FILES["surat_berhentistruktural"]["size"] > 1000000) {
		  $uploadOk = 0;
		  echo "<script>alert('ukuran file suratberhentistruktural terlalu besar, maksimal 1 Mb')</script>";
		}
		if ($imageFileType_struktural != "jpg" && $imageFileType_struktural != "pdf") {
		  $uploadOk = 0;
		  echo "<script>alert('Jenis file suratberhentistruktural yang diupload harus jpg atau pdf')</script>";
		}
	}

	if ($filefungsional<>'') {
		$target_fungsional = $diruploads . basename($_FILES["surat_berhentiJF"]["name"]);
		$imageFileType_fungsional = strtolower(pathinfo($target_fungsional,PATHINFO_EXTENSION));	
		if ($_FILES["surat_berhentiJF"]["size"] > 1000000) {
		  $uploadOk = 0;
		  echo "<script>alert('ukuran file surat_berhentiJF terlalu besar, maksimal 1 Mb')</script>";
		}
		if ($imageFileType_fungsional != "jpg" && $imageFileType_fungsional != "pdf") {
		  $uploadOk = 0;
		  echo "<script>alert('Jenis file surat_berhentiJF yang diupload harus jpg atau pdf')</script>";
		}
	}
	
	if ($filepenempatan<>'') {
		$target_penempatan = $diruploads . basename($_FILES["surat_dimanasaja"]["name"]);
		$imageFileType_penempatan = strtolower(pathinfo($target_penempatan,PATHINFO_EXTENSION));
		if ($_FILES["surat_dimanasaja"]["size"] > 1000000) {
		  $uploadOk = 0;
		  echo "<script>alert('ukuran file Surat Pernyataan Bersedia  Di tempatkan dimana saja Data terlalu besar, maksimal 1 Mb')</script>";
		}
		if ($imageFileType_penempatan != "jpg" && $imageFileType_penempatan != "pdf") {
			  $uploadOk = 0;
			  echo "<script>alert('Jenis file Surat Pernyataan penempatan yang diupload harus jpg atau pdf')</script>";
		}
	}

	if ($filerekomibel<>'') {
		$target_rekomibel = $diruploads . basename($_FILES["surat_rekomibel"]["name"]);
		$imageFileType_rekomibel = strtolower(pathinfo($target_rekomibel,PATHINFO_EXTENSION));
		if ($_FILES["surat_rekomibel"]["size"] > 1000000) {
		  $uploadOk = 0;
		  echo "<script>alert('ukuran file rekomendasi izin belajar terlalu besar, maksimal 1 Mb')</script>";
		}
		if ($imageFileType_rekomibel != "jpg" && $imageFileType_rekomibel != "pdf") {
			  $uploadOk = 0;
			  echo "<script>alert('Jenis file rekomendasi izin belajar yang diupload harus jpg atau pdf')</script>";
		}
	}

	if ($filepernyataanibel<>'') {
		$target_pernyataanibel = $diruploads . basename($_FILES["surat_pernyataanibel"]["name"]);
		$imageFileType_pernyataanibel = strtolower(pathinfo($target_pernyataanibel,PATHINFO_EXTENSION));
		if ($_FILES["surat_pernyataanibel"]["size"] > 1000000) {
		  $uploadOk = 0;
		  echo "<script>alert('ukuran file pernyataan izin belajar terlalu besar, maksimal 1 Mb')</script>";
		}
		if ($imageFileType_pernyataanibel != "jpg" && $imageFileType_pernyataanibel != "pdf") {
			  $uploadOk = 0;
			  echo "<script>alert('Jenis file pernyataan izin belajar yang diupload harus jpg atau pdf')</script>";
		}
	}

	if ($uploadOk == 1) {

    // --- 1. SET DEFAULT: nama file lama dari DB (jika ada) ---
    $target_pengantar       = $suratpengantar;           // kolom surat_pengantar
    $target_loa             = $suratloa;                 // kolom surat_loa
    $target_bukti           = $buktibeasiswa;            // kolom surat_penerimabeasiswa
    $target_mulai           = $ketmulai;                 // kolom kalender_akademik
    $target_akreditasi      = $suratakre;                // kolom surat_akreditasi
    $target_jasmani         = $ketsehat_jasmani;         // kolom surat_jasmani
    $target_rohani          = $ketsehat_rohani;          // kolom surat_rohani
    $target_ijazahterakhir  = $ijazahterakhir;                   // kolom ijazah_terakhir
    $target_transkripnilai  = $transkripnilai; 
	$target_skp1            = $skp1;                  // kolom transkrip_nilai
    $target_skp2            = $skp2;                     // kolom skp_2
    $target_surathukdis     = $surathukdis;              // kolom surat_hukdis
    $target_struktural      = $suratberhentistruktural;  // kolom surat_berhentistruktural
    $target_fungsional      = $suratberhentifungsional;  // kolom surat_berhentiJF
    $target_penempatan      = $perjanianpenempatan;      // kolom surat_dimanasaja
	$target_rekomibel 		= $rekomibel;
	$target_pernyataanibel  = $pernyataanibel;
    

    // 1. surat_pengantar
    if ($pengantarfile <> '') {
		
        if (move_uploaded_file($pengantarsementara, $diruploads . $pengantarfile)) {
            $target_pengantar = $pengantarfile;
        }
    }

    // 2. surat_loa
    if ($fileloa <> '') {
        if (move_uploaded_file($loasementara, $diruploads . $fileloa)) {
            $target_loa = $fileloa;
        }
    }

    // 3. surat_penerimabeasiswa
    if ($filebukti <> '') {
        if (move_uploaded_file($buktisementara, $diruploads . $filebukti)) {
            $target_bukti = $filebukti;
        }
    }

    // 4. kalender_akademik
    if ($filemulai <> '') {
        if (move_uploaded_file($mulaisementara, $diruploads . $filemulai)) {
            $target_mulai = $filemulai;
        }
    }

    // 5. surat_akreditasi
    if ($fileakreditasi <> '') {
        if (move_uploaded_file($akreditasisementara, $diruploads . $fileakreditasi)) {
            $target_akreditasi = $fileakreditasi;
        }
    }

    // 6. surat_jasmani
    if ($filejasmani <> '') {
        if (move_uploaded_file($jasmanisementara, $diruploads . $filejasmani)) {
            $target_jasmani = $filejasmani;
        }
    }

    // 7. surat_rohani
    if ($filerohani <> '') {
        if (move_uploaded_file($rohanisementara, $diruploads . $filerohani)) {
            $target_rohani = $filerohani;
        }
    }

    // 8. ijazah_terakhir
    if ($fileijazahterakhir <> '') {
   		 if (move_uploaded_file($ijazahterakhirsementara, $diruploads . $fileijazahterakhir)) {
        $target_ijazahterakhir = $fileijazahterakhir;
    	}
    }

    // 9. transkrip_nilai
    if ($filetranskripnilai <> '') {
		if (move_uploaded_file($transkripnilaisementara, $diruploads . $filetranskripnilai)) {
			$target_transkripnilai = $filetranskripnilai;
		}
    }

	// 10. skp_1
    if ($fileskp1 <> '') {
        if (move_uploaded_file($skp1sementara, $diruploads . $fileskp1)) {
            $target_skp1 = $fileskp1;
        }
    }

    // 10. skp_2
    if ($fileskp2 <> '') {
        if (move_uploaded_file($skp2sementara, $diruploads . $fileskp2)) {
            $target_skp2 = $fileskp2;
        }
    }

    // 11. surat_hukdis
    if ($filesurathukdis <> '') {
        if (move_uploaded_file($surathukdissementara, $diruploads . $filesurathukdis)) {
            $target_surathukdis = $filesurathukdis;
        }
    }

    // 12. surat_berhentistruktural
    if ($filestruktural <> '') {
        if (move_uploaded_file($strukturalsementara, $diruploads . $filestruktural)) {
            $target_struktural = $filestruktural;
        }
    }

    // 13. surat_berhentiJF
    if ($filefungsional <> '') {
        if (move_uploaded_file($fungsionalsementara, $diruploads . $filefungsional)) {
            $target_fungsional = $filefungsional;
        }
    }

    // 14. surat_dimanasaja
    if ($filepenempatan <> '') {
        if (move_uploaded_file($penempatansementara, $diruploads . $filepenempatan)) {
            $target_penempatan = $filepenempatan;
        }
    }
	
	if ($filerekomibel <> '') {
        if (move_uploaded_file($rekomibelsementara, $diruploads . $filerekomibel)) {
            $target_rekomibel = $filerekomibel;
        }
    }

	if ($filepernyataanibel <> '') {
        if (move_uploaded_file($pernyataanibelsementara, $diruploads . $filepernyataanibel)) {
            $target_pernyataanibel = $filepernyataanibel;
        }
    }

    // --- 3. UPDATE KE TABEL PENDAFTARAN (SUSUAI KOLOM DB) ---
    $query = mysqli_query($db, "
        UPDATE pendaftaran SET
            surat_pengantar        = '$target_pengantar',
            surat_loa              = '$target_loa',
            surat_penerimabeasiswa = '$target_bukti',
            kalender_akademik      = '$target_mulai',
            surat_akreditasi       = '$target_akreditasi',
            surat_jasmani          = '$target_jasmani',
            surat_rohani           = '$target_rohani',
            ijazah_terakhir        = '$target_ijazahterakhir',
            transkrip_nilai        = '$target_transkripnilai',
			skp_1                  = '$target_skp1',
            skp_2                  = '$target_skp2',
            surat_hukdis           = '$target_surathukdis',
            surat_berhentistruktural = '$target_struktural',
            surat_berhentiJF       = '$target_fungsional',
            surat_dimanasaja       = '$target_penempatan',
			surat_rekomibel        = '$target_rekomibel',
			surat_pernyataanibel	= '$target_pernyataanibel'
        WHERE nip = '$nip'
    ");

    if ($query) {
        echo "<script>alert('berhasil simpan')</script>";
        echo "<meta http-equiv='refresh' content='0; url=../daftar_tubel.php'>";
    } else {
        echo "<script>alert('Gagal Simpan, Cek kembali inputan Anda')</script>";
        
		if ($tubelibel=='tubel') {
		 echo "<meta http-equiv='refresh' content='0; url=../daftar_upload.php?iddaftar=$iddaftar'>";
		}else{
			echo "<meta http-equiv='refresh' content='0; url=../ibel_upload.php?iddaftar=$iddaftar'>";
		}
    }

} else {
	if ($tubelibel=='tubel') {
		 echo "<meta http-equiv='refresh' content='0; url=../daftar_upload.php?iddaftar=$iddaftar'>";
	}else{
		echo "<meta http-equiv='refresh' content='0; url=../ibel_upload.php?iddaftar=$iddaftar'>";
    }
}

	

}else{
	if(isset($_POST['selesai'])){
		extract($_POST);

		// menginisialisasi variabel dengan data yang diinputkan
		
		$foldername = $_SESSION['nip'];
		
		$structure = 'uploads/'.$foldername.'/';
		
		if (!is_dir($structure)) {
			mkdir($structure);
		}
		
		$nip = $_SESSION['nip'];
		$suratpengantar = '';
		$suratloa = '';
		$buktibeasiswa = '';
		$ketmulai = '';
		$suratakre = '';
		$ketsehat_jasmani = '';
		$ketsehat_rohani = '';
		$ijazahterakhir = '';
		$transkripnilai = '';
		$skp1 = '';
		$skp2 = '';
		$surathukdis = '';
		$suratberhentistruktural = '';
		$suratberhentifungsional = '';
		$perjanianpenempatan = '';
		$rekomibel = '';
		$pernyataanibel = '';
		// $sk_pns = '';
		// $sk_pangkat = '';
		// $sk_jabatan = '';
		// $sertifikat_toefl = '';
		// $sertifikat_tpa = '';
		$sql = mysqli_query($db, "SELECT * FROM pendaftaran where nip='$nip'");
while ($row = $sql->fetch_assoc()){
	$suratpengantar = $row['surat_pengantar'];
	$suratloa = $row['surat_loa'];
	$buktibeasiswa = $row['surat_penerimabeasiswa'];
	$ketmulai = $row['kalender_akademik'];
	$suratakre = $row['surat_akreditasi'];
	$ketsehat_jasmani = $row['surat_jasmani'];
	$ketsehat_rohani = $row['surat_rohani'];
	$ijazahterakhir = $row['ijazah_terakhir'];
	$transkripnilai = $row['transkrip_nilai'];
	$skp1 = $row['skp_1'];
	$skp2 = $row['skp_2'];
	$surathukdis = $row['surat_hukdis'];
	$suratberhentistruktural = $row['surat_berhentistruktural'];
	$suratberhentifungsional = $row['surat_berhentiJF'];
	$perjanianpenempatan = $row['surat_dimanasaja'];
	$rekomibel = $row['surat_rekomibel'];
	$pernyataanibel = $row['surat_pernyataanibel'];
	
}



		// ambil data file

		
		$pengantarfile = $_FILES['surat_pengantar']['name'];
	$pengantarsementara = $_FILES['surat_pengantar']['tmp_name'];
	
	$fileloa = $_FILES['surat_loa']['name'];
	$loasementara = $_FILES['surat_loa']['tmp_name'];
	
	$filebukti = $_FILES['surat_penerimabeasiswa']['name'];
	$buktisementara = $_FILES['surat_penerimabeasiswa']['tmp_name'];
	
	$filemulai = $_FILES['kalender_akademik']['name'];
	$mulaisementara = $_FILES['kalender_akademik']['tmp_name'];
	
	$fileakreditasi = $_FILES['surat_akreditasi']['name'];
	$akreditasisementara = $_FILES['surat_akreditasi']['tmp_name'];
	
	$filejasmani = $_FILES['surat_jasmani']['name'];
	$jasmanisementara = $_FILES['surat_jasmani']['tmp_name'];
	
	$filerohani = $_FILES['surat_rohani']['name'];
	$rohanisementara = $_FILES['surat_rohani']['tmp_name'];
	
	$fileijazahterakhir = $_FILES['ijazah_terakhir']['name'];
	$ijazahterakhirsementara = $_FILES['ijazah_terakhir']['tmp_name'];
	
	$filetranskripnilai = $_FILES['transkrip_nilai']['name'];
	$transkripnilaisementara = $_FILES['transkrip_nilai']['tmp_name'];

	$fileskp1 = $_FILES['skp_1']['name'];
	$skp1sementara = $_FILES['skp_1']['tmp_name'];
	
	$fileskp2 = $_FILES['skp_2']['name'];
	$skp2sementara = $_FILES['skp_2']['tmp_name'];
	
	$filesurathukdis = $_FILES['surat_hukdis']['name'];
	$surathukdissementara = $_FILES['surat_hukdis']['tmp_name'];

	$filestruktural = $_FILES['surat_berhentistruktural']['name'];
	$strukturalsementara = $_FILES['surat_berhentistruktural']['tmp_name'];

	$filefungsional = $_FILES['surat_berhentiJF']['name'];
	$fungsionalsementara = $_FILES['surat_berhentiJF']['tmp_name'];

	$filepenempatan = $_FILES['surat_dimanasaja']['name'];
	$penempatansementara = $_FILES['surat_dimanasaja']['tmp_name'];

	$filerekomibel = $_FILES['surat_rekomibel']['name'];
	$rekomibelsementara = $_FILES['surat_rekomibel']['tmp_name'];

	$filepernyataanibel = $_FILES['surat_pernyataanibel']['name'];
	$pernyataanibelsementara = $_FILES['surat_pernyataanibel']['tmp_name'];

		


		$uploadOk = 1;	
		$diruploads = $structure;
		
		if ($pengantarfile<>'') {
			//$target_file = $diruploads.'-SuratKeterangan';
			$target_file = $diruploads . basename($_FILES["surat_pengantar"]["name"]);
			$imageFileType_pengantar = strtolower(pathinfo($target_pengantar,PATHINFO_EXTENSION));
			
			// Check file size
			if ($_FILES["surat_pengantar"]["size"] > 1000000) {
			  $uploadOk = 0;
			  echo "<script>alert('ukuran file Surat pengantar terlalu besar, maksimal 1 Mb')</script>";
			}

			if ($imageFileType_pengantar != "jpg" && $imageFileType_pengantar != "pdf" ) {
			  $uploadOk = 0;
			  echo "<script>alert('Surat yang diupload harus pdf atau jpg')</script>";
			}	
		}
		
		if ($fileloa<>'') {
			$target_loa = $diruploads . basename($_FILES["surat_loa"]["name"]);
			$imageFileType_loa = strtolower(pathinfo($target_loa,PATHINFO_EXTENSION));
			
			 if ($_FILES["surat_loa"]["size"] > 1000000) {
			  $uploadOk = 0;
			  echo "<script>alert('ukuran file terlalu besar, maksimal 1 Mb')</script>";
			}
		
			if ($imageFileType_loa != "jpg" && $imageFileType_loa != "pdf" ) {
			  $uploadOk = 0;
			  echo "<script>alert('Jenis file yang diupload harus pdf atau jpg')</script>";
			}
		}
		
		if ($filebukti<>'') {
			$target_bukti = $diruploads . basename($_FILES["surat_penerimabeasiswa"]["name"]);
			$imageFileType_bukti = strtolower(pathinfo($target_bukti,PATHINFO_EXTENSION));
			
			if ($_FILES["surat_penerimabeasiswa"]["size"] > 1000000) {
				$uploadOk = 0;
				echo "<script>alert('ukuran file surat_penerimabeasiswa terlalu besar, maksimal 1 Mb')</script>";
			}
			
			if ($imageFileType_bukti != "jpg" && $imageFileType_bukti != "pdf" ) {
			  $uploadOk = 0;
			  echo "<script>alert('Jenis file surat_penerimabeasiswa yang diupload harus jpg')</script>";
			}
		}
		
		if ($filemulai<>'') {
			$target_mulai = $diruploads . basename($_FILES["kalender_akademik"]["name"]);
			$imageFileType_mulai = strtolower(pathinfo($target_mulai,PATHINFO_EXTENSION));
			
			if ($_FILES["kalender_akademik"]["size"] > 1000000) {
			  $uploadOk = 0;
			  echo "<script>alert('ukuran file kalender terlalu besar, maksimal 1 Mb')</script>";
			}
			
			if ($imageFileType_mulai != "jpg" && $imageFileType_mulai != "pdf") {
			  $uploadOk = 0;
			  echo "<script>alert('Jenis file kalender  yang diupload harus jpg atau pdf')</script>";
			}
		}
		
		if ($fileakreditasi<>'') {
			$target_akreditasi = $diruploads . basename($_FILES["surat_akreditasi"]["name"]);
			$imageFileType_akreditasi = strtolower(pathinfo($target_akreditasi,PATHINFO_EXTENSION));
			
			if ($_FILES["surat_akreditasi"]["size"] > 1000000) {
			  $uploadOk = 0;
			  echo "<script>alert('ukuran file akreditasi terlalu besar, maksimal 1 Mb')</script>";
			}
			if ($imageFileType_akreditasi != "jpg" && $imageFileType_akreditasi != "pdf") {
			  $uploadOk = 0;
			  echo "<script>alert('Jenis file akreditasi yang diupload harus jpg atau pdf')</script>";
			}
		}
		
		
		if ($filejasmani<>'') {
				$target_jasmani = $diruploads . basename($_FILES["surat_jasmani"]["name"]);
				$imageFileType_jasmani = strtolower(pathinfo($target_jasmani,PATHINFO_EXTENSION));
				
				if ($_FILES["surat_jasmani"]["size"] > 1000000) {
				  $uploadOk = 0;
				  echo "<script>alert('ukuran surat_jasmani terlalu besar, maksimal 1 Mb')</script>";
				}
				
				if ($imageFileType_jasmani != "jpg" && $imageFileType_jasmani != "pdf") {
					  $uploadOk = 0;
					  echo "<script>alert('Jenis file surat_jasmani yang diupload harus jpg atau pdf')</script>";
				}
		}
		
		if ($filerohani<>'') {
			$target_rohani = $diruploads . basename($_FILES["surat_rohani"]["name"]);
			$imageFileType_rohani = strtolower(pathinfo($target_rohani,PATHINFO_EXTENSION));
			if ($_FILES["surat_rohani"]["size"] > 1000000) {
			  $uploadOk = 0;
			  echo "<script>alert('ukuran surat_rohani terlalu besar, maksimal 1 Mb')</script>";
			}
			if ($imageFileType_rohani != "jpg" && $imageFileType_rohani != "pdf") {
			  $uploadOk = 0;
			  echo "<script>alert('Jenis file surat_rohani yang diupload harus jpg atau pdf')</script>";
			}
		}
		
		if ($fileijazahterakhir<>'') {
			$target_ijazahterakhir = $diruploads . basename($_FILES["ijazah_terakhir"]["name"]);
			$imageFileType_ijazahterakhir = strtolower(pathinfo($target_ijazahterakhir,PATHINFO_EXTENSION));
			if ($_FILES["ijazah_terakhir"]["size"] > 1000000) {
			  $uploadOk = 0;
			  echo "<script>alert('ukuran file ijazah_terakhir terlalu besar, maksimal 1 Mb')</script>";
			}
			if ($imageFileType_ijazahterakhir != "jpg" && $imageFileType_ijazahterakhir != "pdf") {
			  $uploadOk = 0;
			  echo "<script>alert('Jenis file ijazah_terakhir yang diupload harus jpg atau pdf')</script>";
			}
		}
		
		
		if ($filetranskripnilai<>'') {
			$target_transkripnilai = $diruploads . basename($_FILES["transkrip_nilai"]["name"]);
			$imageFileType_transkripnilai = strtolower(pathinfo($target_transkripnilai,PATHINFO_EXTENSION));
			
			if ($_FILES["transkrip_nilai"]["size"] > 1000000) {
			  $uploadOk = 0;
			  echo "<script>alert('ukuran file transkrip_nilai terlalu besar, maksimal 1 Mb')</script>";
			}
			if ($imageFileType_transkripnilai != "jpg" && $imageFileType_transkripnilai != "pdf") {
				  $uploadOk = 0;
				  echo "<script>alert('Jenis file transkrip_nilai yang diupload harus jpg atau pdf')</script>";
			}
		}

		if ($fileskp1<>'') {
			$target_skp1 = $diruploads . basename($_FILES["skp_1"]["name"]);
			$imageFileType_skp1 = strtolower(pathinfo($target_skp1,PATHINFO_EXTENSION));
			
			if ($_FILES["skp_1"]["size"] > 1000000) {
			  $uploadOk = 0;
			  echo "<script>alert('ukuran file skp 2025 terlalu besar, maksimal 1 Mb')</script>";
			}
			if ($imageFileType_skp1 != "jpg" && $imageFileType_skp1 != "pdf") {
			  $uploadOk = 0;
			  echo "<script>alert('Jenis file skp 2025 yang diupload harus jpg atau pdf')</script>";
			}
		}
		
		if ($fileskp2<>'') {
			$target_skp2 = $diruploads . basename($_FILES["skp_2"]["name"]);
			$imageFileType_skp2 = strtolower(pathinfo($target_skp2,PATHINFO_EXTENSION));
			
			if ($_FILES["skp_2"]["size"] > 1000000) {
			  $uploadOk = 0;
			  echo "<script>alert('ukuran file skp_2 terlalu besar, maksimal 1 Mb')</script>";
			}
			if ($imageFileType_skp2 != "jpg" && $imageFileType_skp2 != "pdf") {
			  $uploadOk = 0;
			  echo "<script>alert('Jenis file skp_2 yang diupload harus jpg atau pdf')</script>";
			}
		}
		
		
		if ($filesurathukdis<>'') {
			$target_surathukdis = $diruploads . basename($_FILES["surat_hukdis"]["name"]);
			$imageFileType_surathukdis = strtolower(pathinfo($target_surathukdis,PATHINFO_EXTENSION));
			if ($_FILES["surat_hukdis"]["size"] > 1000000) {
			  $uploadOk = 0;
			  echo "<script>alert('ukuran file surat_hukdis terlalu besar, maksimal 1 Mb')</script>";
			}
			if ($imageFileType_surathukdis != "jpg" && $imageFileType_surathukdis != "pdf") {
			  $uploadOk = 0;
			  echo "<script>alert('Jenis file surat_hukdis yang diupload harus jpg atau pdf')</script>";
			}
		}
		
		if ($suratberhentistruktural<>'') {
			$target_struktural = $diruploads . basename($_FILES["surat_berhentistruktural"]["name"]);
			$imageFileType_struktural = strtolower(pathinfo($target_struktural,PATHINFO_EXTENSION));	
			if ($_FILES["surat_berhentistruktural"]["size"] > 1000000) {
			  $uploadOk = 0;
			  echo "<script>alert('ukuran file surat_berhentistruktural terlalu besar, maksimal 1 Mb')</script>";
			}
			if ($imageFileType_struktural != "jpg" && $imageFileType_struktural != "pdf") {
			  $uploadOk = 0;
			  echo "<script>alert('Jenis file surat_berhentistruktural Nilai yang diupload harus jpg atau pdf')</script>";
			}
		}
		
		if ($suratberhentifungsional<>'') {
			$target_fungsional = $diruploads . basename($_FILES["surat_berhentiJF"]["name"]);
			$imageFileType_fungsional = strtolower(pathinfo($target_fungsional,PATHINFO_EXTENSION));	
			if ($_FILES["surat_berhentifungsional"]["size"] > 1000000) {
			  $uploadOk = 0;
			  echo "<script>alert('ukuran file surat_berhentistruktural terlalu besar, maksimal 1 Mb')</script>";
			}
			if ($imageFileType_fungsional != "jpg" && $imageFileType_fungsional != "pdf") {
			  $uploadOk = 0;
			  echo "<script>alert('Jenis file yang diupload harus jpg atau pdf')</script>";
			}
		}
		
		if ($filesurat_kebenaran<>'') {
			$target_kebenaran = $diruploads . basename($_FILES["surat_dimanasaja"]["name"]);
			$imageFileType_kebenaran = strtolower(pathinfo($target_kebenaran,PATHINFO_EXTENSION));
			if ($_FILES["surat_dimanasaja"]["size"] > 1000000) {
			  $uploadOk = 0;
			  echo "<script>alert('ukuran file Surat Pernyataan Kebenaran Data terlalu besar, maksimal 1 Mb')</script>";
			}
			if ($imageFileType_kebenaran != "jpg" && $imageFileType_kebenaran != "pdf") {
				  $uploadOk = 0;
				  echo "<script>alert('Jenis file Surat Pernyataan Kebenaran yang diupload harus jpg atau pdf')</script>";
			}
		}
	
			if ($filesurat_kebenaran<>'') {
			$target_kebenaran = $diruploads . basename($_FILES["surat_dimanasaja"]["name"]);
			$imageFileType_kebenaran = strtolower(pathinfo($target_kebenaran,PATHINFO_EXTENSION));
			if ($_FILES["surat_dimanasaja"]["size"] > 1000000) {
			  $uploadOk = 0;
			  echo "<script>alert('ukuran file Surat Pernyataan Kebenaran Data terlalu besar, maksimal 1 Mb')</script>";
			}
			if ($imageFileType_kebenaran != "jpg" && $imageFileType_kebenaran != "pdf") {
				  $uploadOk = 0;
				  echo "<script>alert('Jenis file Surat Pernyataan Kebenaran yang diupload harus jpg atau pdf')</script>";
			}
		}

		if ($filerekomibel<>'') {
		$target_rekomibel = $diruploads . basename($_FILES["surat_rekomibel"]["name"]);
		$imageFileType_rekomibel = strtolower(pathinfo($target_rekomibel,PATHINFO_EXTENSION));
		if ($_FILES["surat_rekomibel"]["size"] > 1000000) {
		  $uploadOk = 0;
		  echo "<script>alert('ukuran file rekomendasi izin belajar terlalu besar, maksimal 1 Mb')</script>";
		}
		if ($imageFileType_rekomibel != "jpg" && $imageFileType_rekomibel != "pdf") {
			  $uploadOk = 0;
			  echo "<script>alert('Jenis file rekomendasi izin belajar yang diupload harus jpg atau pdf')</script>";
		}
	}

	if ($filepernyataanibel<>'') {
		$target_pernyataanibel = $diruploads . basename($_FILES["surat_pernyataanibel"]["name"]);
		$imageFileType_pernyataanibel = strtolower(pathinfo($target_pernyataanibel,PATHINFO_EXTENSION));
		if ($_FILES["surat_pernyataanibel"]["size"] > 1000000) {
		  $uploadOk = 0;
		  echo "<script>alert('ukuran file pernyataan izin belajar terlalu besar, maksimal 1 Mb')</script>";
		}
		if ($imageFileType_pernyataanibel != "jpg" && $imageFileType_pernyataanibel != "pdf") {
			  $uploadOk = 0;
			  echo "<script>alert('Jenis file pernyataan izin belajar yang diupload harus jpg atau pdf')</script>";
		}
	}
				
		if ($uploadOk==1)  {
				// pindahkan file
				
				if ($pengantarfile<>'') {
					//$namasementara = 'Surat Keterangan';
					move_uploaded_file($namasementara, $diruploads.$pengantarfile); 
				}else{
					$target_pengantar=$surat_pengantar;
					if ($target_pengantar=='') {
						$uploadOk=2;
						echo "<script>alert('Surat Usul dari Pimpinan belum di upload')</script>";
					}
				}
				if ($fileloa<>'') {
						move_uploaded_file($loasementara, $diruploads.$fileloa);
				}else{
					$target_loa=$surat_loa ;
					if ($target_loa=='') {
						$uploadOk=2;
						echo "<script>alert('Surat loa belum di upload')</script>";
					}
				}
				if ($filebukti<>'') {
						move_uploaded_file($buktisementara, $diruploads.$filebukti);
				}else{
					$target_bukti=$surat_penerimabeasiswa  ;
					if ($target_bukti=='') {
						$uploadOk=2;
						echo "<script>alert('Pas surat_penerimabeasiswa belum di upload')</script>";
					}
				}
				if ($filemulai<>'') {
					move_uploaded_file($mulaisementara, $diruploads.$filemulai);
				}else{
					$target_mulai=$kalender_akademik  ;
					if ($target_mulai=='') {
						$uploadOk=2;
						echo "<script>alert('kalender akademik belum di upload')</script>";
					}
				}
				if ($fileakreditasi<>'') {
					move_uploaded_file($akreditasisementara, $diruploads.$fileakreditasi);
				}else{
					$target_akreditasi=$surat_akreditasi  ;
					if ($target_akreditasi=='') {
						$uploadOk=2;
						echo "<script>alert('surat akreditasi belum di upload')</script>";
					}
				}
				if ($filejasmani<>'') {
					move_uploaded_file($jasmanisementara, $diruploads.$filejasmani);
				}else{
					$target_jasmani=$surat_jasmani ;
					if ($target_jasmani=='') {
						$uploadOk=2;
						echo "<script>alert('surat_jasmani belum di upload')</script>";
					}
				}
				if ($filerohani<>'') {
					move_uploaded_file($rohanisementara, $diruploads.$filerohani);
				}else{
					$target_rohani=$surat_rohani ;
				}
				if ($fileijazahterakhir<>'') {
					if (move_uploaded_file($ijazahterakhirsementara, $diruploads . $fileijazahterakhir)) {
       					 $target_ijazahterakhir = $fileijazahterakhir;
    				}
				}
				else{
					$target_ijazahterakhir=$ijazah_terakhir;
					if ($target_ijazahterakhir=='') {
						$uploadOk=2;
						echo "<script>alert('ijazah_terakhir belum di upload')</script>";
					}
				}
				if ($filetranskripnilai<>'') {
					if (move_uploaded_file($transkripnilaisementara, $diruploads . $filetranskripnilai)) {
						$target_transkripnilai = $filetranskripnilai;
					}
				}
				else{
					$target_transkripnilai=$transkrip_nilai;
					if ($target_transkripnilai=='') {
						$uploadOk=2;
						echo "<script>alert('Daftar Riwayat Hidup belum di upload')</script>";
					}
				}

				if ($fileskp1<>'') {
					move_uploaded_file($skp1sementara, $diruploads.$fileskp1);
				}else{
					$target_skp1=$skp_1;
					if ($target_skp1=='') {
						$uploadOk=2;
						echo "<script>alert('skp 2025 belum di upload')</script>";
					}
				}

				if ($fileskp2<>'') {
					move_uploaded_file($skp2sementara, $diruploads.$fileskp2);
				}else{
					$target_skp2=$skp_2;
					if ($target_skp2=='') {
						$uploadOk=2;
						echo "<script>alert('skp_2 belum di upload')</script>";
					}
				}
				
				if ($filesurathukdis<>'') {
					move_uploaded_file($surathukdissementara, $diruploads.$filesurathukdis);
				}else{
					$target_surathukdis=$surat_hukdis;
					if ($target_surathukdis=='') {
						$uploadOk=2;
						echo "<script>alert('surat_hukdis belum di upload')</script>";
					}
				}			
				if ($suratberhentistruktural<>'') {
					move_uploaded_file($strukturalsementara, $diruploads.$filestruktural);
				}else{
					$target_struktural=$surat_berhentistruktural;
					if ($target_struktural=='') {
						$uploadOk=2;
						echo "<script>alert('surat perjanjian belum di upload')</script>";
					}
				}			
			
				if ($suratberhentifungsional<>'') {
					move_uploaded_file($fungsionalsementara, $diruploads.$filefungsional);
				}else{
					$target_fungsional=$surat_berhentiJF;
					if ($target_fungsional=='') {
						$uploadOk=2;
						echo "<script>alert('surat perjanjian belum di upload')</script>";
					}
				}			

				if ($filesurat_kebenaran<>'') {
					move_uploaded_file($suratkebenaran_sementara, $diruploads.$filesurat_kebenaran);
				}else{
					$target_kebenaran=$surat_dimanasaja;
					if ($target_kebenaran=='') {
						$uploadOk=2;
						echo "<script>alert('Surat Pernyataan Kebenaran dan keabsahan data belum di upload')</script>";
					}
				}

				if ($filerekomibel<>'') {
					move_uploaded_file($rekomibel_sementara, $diruploads.$filesurat_rekomibel);
				}else{
					$target_rekomibel=$filesurat_rekomibel;
					if ($target_rekomibel=='') {
						$uploadOk=2;
						echo "<script>alert('Surat rekomendasi izin belajar belum di upload')</script>";
					}
				}

				if ($filepernyataanibel<>'') {
					move_uploaded_file($pernyataanibel_sementara, $diruploads.$filesurat_pernyataanibel);
				}else{
					$target_pernyataanibel=$filesurat_pernyataanibel;
					if ($target_pernyataanibel=='') {
						$uploadOk=2;
						echo "<script>alert('Surat pernyataan izin belajar belum di upload')</script>";
					}
				}
				
				//cek kelengkapan file upload 
				
				
				// memasukkan data inputan ke tabel pendaftaran
				if ($uploadOk==1) {
				$query = mysqli_query($db, "surat_pengantar='$target_pengantar', surat_loa='$target_loa',
						surat_penerimabeasiswa='$target_bukti', kalender_akademik='$target_mulai', surat_akreditasi='$target_akreditasi', 
						surat_jasmani='$target_jasmani', surat_rohani='$target_rohani',skp_1='$target_skp1', skp_2='$target_skp2', ijazah_terakhir='$target_ijazahterakhir', 
						surat_hukdis='$target_surathukdis', transkrip_nilai='$target_transkripnilai', surat_berhentistruktural='$target_struktural, 
						surat_berhentiJF='$target_fungsional', surat_dimanasaja='$target_kebenaran',
						 surat_rekomibel='$rekomibel', surat_pernyataanibel='$pernyataanibel', status='Pendaftaran' where nip='$nip'");
				}else{ 
					if ($uploadOk==2) {
						$query = mysqli_query($db, "surat_pengantar='$target_pengantar', surat_loa='$target_loa',
						surat_penerimabeasiswa='$target_bukti', kalender_akademik='$target_mulai', surat_akreditasi='$target_akreditasi', 
						surat_jasmani='$target_jasmani', surat_rohani='$target_rohani',skp_1='$target_skp1', skp_2='$target_skp2', ijazah_terakhir='$target_ijazahterakhir', 
						surat_hukdis='$target_surathukdis', transkrip_nilai='$target_transkripnilai', surat_berhentistruktural='$target_struktural, 
						surat_berhentiJF='$target_fungsional', surat_dimanasaja='$target_kebenaran',
						 surat_rekomibel='$rekomibel', surat_pernyataanibel='$pernyataanibel', where nip='$nip'");
					}
				}
				// fungsi pengecekan $query
				if($query){
					// jika berhasil tampilkan alert berhasil dan load ke halaman status.php
					if ($uploadOk==1) {
						echo "<script>alert('Pendaftaran anda sudah tersimpan dan tidak dapat diedit kembali')</script>";
						echo "<meta http-equiv='refresh' content='0; url=../status.php'>";
					}else{
						if ($uploadOk==2) {
							echo "<script>alert('Anda belum dapat menyelesaikan pendaftaran, dikarenakan dokumen pendukung masih ada yang belum di upload')</script>";
							echo "<meta http-equiv='refresh' content='0; url=../daftar_upload.php'>";
						}
					}
					//echo "<meta http-equiv='refresh' content='0; url=../index2.php'>";
				} else {

					// jika gagal tampilkan alert Gagal
					echo "<script>alert('Gagal Simpan, Cek kembali inputan Anda')</script>";
					echo "<meta http-equiv='refresh' content='0; url=../daftar_upload.php'>";
				}
		}else{
			echo "<meta http-equiv='refresh' content='0; url=../daftar_upload.php'>";
		}
	}
}

?>

