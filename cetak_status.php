<?php

// sesi
session_start();

// import koneksi.php
include 'koneksi.php';

// fungsi pengecekan sesi
if(isset($_GET['iddaftar'])){
    // import header.php
    $header = "- Status Siswa";
    include 'header.php';
	$id = $_GET['iddaftar'];
	
    // ambil data tabel pendaftaran dengan perguruan tinggi dan program studi
    $query = mysqli_query($db, "SELECT a.*, b.*, c.nama_kampus, d.nama_prodi FROM pendaftaran a
			INNER JOIN mr_pegawai b ON a.nip = b.nip 
			LEFT JOIN mr_prodi d ON a.id_prodi = d.id
			LEFT JOIN mr_kampus c ON d.id_kampus = c.id
			WHERE a.id='$id'");
    
    if (!$query) {
        die("Query error: " . mysqli_error($db));
    }
    
    $data = mysqli_fetch_array($query);

    // fungsi konversi tanggal 
    function tanggal_indo($tanggal){
    	$bulan = [  'bulan',
                    'Januari',
                    'Februari',
                    'Maret',
                    'April',
                    'Mei',
                    'Juni',
                    'Juli',
                    'Agustus',
                    'September',
                    'Oktober',
                    'November',
                    'Desember'
                ];

        // memecah tanggal bulan tahun
        $split = explode('-', $tanggal);
        return $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];
    }
?>

<!-- Container -->
<div class="container py-3">
    <div class="card">

        <!-- Card Header -->
        <h3 class="card-header text-center">
            Bukti Pengajuan
        </h3>

        <!-- Card Body -->
        <div class="card-body o-hidden border-0 shadow-lg ">

            <!-- Card Data Siswa -->
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-sm-3">
                            <h6 class="mb-0">Nomor Pendaftaran</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            <?php 
                            $id_format = str_pad($data['id'], 5, '0', STR_PAD_LEFT);

                            $bulan_romawi = [
                                1=>'I',2=>'II',3=>'III',4=>'IV',5=>'V',6=>'VI',
                                7=>'VII',8=>'VIII',9=>'IX',10=>'X',11=>'XI',12=>'XII'
                            ];

                            $tanggal = $data['tgl_submit'];
                            $bulan = date('n', strtotime($tanggal));
                            $tahun = date('Y', strtotime($tanggal));

                            echo "REG/" . $id_format . "/" . $bulan_romawi[$bulan] . "/" . $tahun;
                            ?>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <h6 class="mb-0">Nama Lengkap</h6>
                        </div>
                    <div class="col-sm-9 text-secondary">
                        <?php echo $data['nama']; ?>
                    </div>
                </div>
                <hr>
                
		<div class="row">
                    <div class="col-sm-3">
                        <h6 class="mb-0">NIP</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <?php echo $data['nip']; ?>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3">
                        <h6 class="mb-0">Jabatan</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <?php echo $data['jabatan']; ?>
                    </div>
                </div>
                <hr>
                <div class="row">
                <div class="col-sm-3">
                        <h6 class="mb-0">Komponen</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <?php echo  $data['komponen']; ?>
                    </div>
                </div>
                <hr>
                
		<div class="row">
                    <div class="col-sm-3">
                        <h6 class="mb-0">Pendidikan Terakhir</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <?php echo $data['tingkat_pendidikan']; ?>
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-sm-3">
                        <h6 class="mb-0">Jenis Pengajuan</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                       <?php $jenis_map = ['tubel' => 'TUGAS BELAJAR','ibel'  => 'IZIN BELAJAR'];
			echo isset($jenis_map[$data['tubel_ibel']]) 
     			? $jenis_map[$data['tubel_ibel']] : '-'; ?>
                    </div>
                </div>
		<hr>

                <div class="row">
                    <div class="col-sm-3">
                        <h6 class="mb-0">Perguruan Tinggi</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <?php echo $data['nama_kampus']; ?>
                    </div>
                </div>
                <hr>
                
		<div class="row">
                    <div class="col-sm-3">
                        <h6 class="mb-0">Program Studi</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <?php echo $data['nama_prodi']; ?>
                    </div>
                </div>
                <hr>
				
		<div class="row">
                    <div class="col-sm-3">
                        <h6 class="mb-0">Tanggal Submit Pengajuan</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <?php echo $data['tgl_submit']; ?>
                    </div>
                </div>
		<hr>

                <div class="row">
                    <div class="col-sm-3">
                        <h6 class="mb-0">Status Pengajuan</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <?php echo $data['status_usul']; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
            
            window.print();
        </script>              


<?php

    include 'footer.php';

} else {
    echo "<script>
            alert('Silahkan Login Terlebih Dahulu!');
            window.location = 'login.php';
        </script>";
}

?>