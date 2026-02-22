<?php

session_start();
include 'koneksi.php';

// fungsi cek sesi
if(isset($_SESSION['nip'])){
    $header = "- Data Usul Tubel";
    include 'header.php';

?>

<!-- container -->
<div class="container">    
    <div class="card my-2">
        <h3 class="card-header text-center">Data Usul Tugas Belajar/Izin Belajar</h3>
        <div class="card-body container ">
            
            <!-- Tabel Siswa Cadangan -->
            <table class="table table-bordered nowrap" id="dataTable">
                <thead class="thead-dark">
                    <tr>
                        <th>NO</th>
						<th>JENIS PENGAJUAN</th>
                        <th>TANGGAL PENGAJUAN</th>
                        <th>STATUS</th>
                        <th>SK</th>
                        <th>ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    
                <?php  
					$nip=$_SESSION['nip'];
                    // mengambil data tabel pendaftaran dengan kondisi status Cadangan
                    $query = mysqli_query($db, "SELECT * FROM pendaftaran WHERE nip='$nip'");
                    $data = mysqli_fetch_array($query);

                    // cek kolom dari pendaftaran
                    if(mysqli_num_rows($query) >0) {
                        $no = 1;
                        
                        // loop data tabel pendaftaran kondisi cadangan
                        do{
							if ($data['tubel_ibel']=='tubel') {
									$tubel='Tugas Belajar';
                                    $urlUpload = 'daftar_upload.php'; 
							}else{
								$tubel='Izin Belajar';
                                $urlUpload = 'ibel_upload.php'; 
							}
							
							$skFile = trim($data['sk'] ?? '');


                ?>
                    <tr>
                        <td><?=$no++;?></td>
						<td><?=$tubel;?></td>
                        <td><?=$data['tgl_usul'];?></td>
                        <td><?=$data['status_usul'];?></td>
                       <td>
                            <?php if ($skFile == '') { ?>
                                Belum ada
                            <?php } else { ?>
                                <a class="btn btn-sm btn-success"
                                    href="download_sk.php?iddaftar=<?=$data['id'];?>"
                                    target="_blank">
                                    Download
                                </a>
                            <?php } ?>
                            </td>
                        <td>
						<?php if ($data['status_usul']=='Pendaftaran') { ?>
                            <a role="button" name="submit" href="detail.php?iddaftar=<?=$data['id'];?>" class="btn btn-sm btn-secondary">Edit</a>
							<a role="button" name="submit" href="<?=$urlUpload;?>?iddaftar=<?=$data['id'];?>" class="btn btn-sm btn-secondary">Upload</a>
                            <a role="button" name="submit" href="submit.php?iddaftar=<?= $data['id']; ?>" class="btn btn-sm btn-primary"
                            onclick="return confirm('Yakin ingin submit pendaftaran? Jika sudah disubmit Anda tidak dapat merubah data dan berkas yang sudah di upload');">
                            Submit
                            </a>
							<a role="button" name="submit" href="delete.php?iddaftar=<?= $data['id']; ?>" class="btn btn-sm btn-danger"
                            onclick="return confirm('Yakin ingin menghapus data ini?');">
                            Delete
                            </a>
						<?php }else{ ?>
							<a role="button" name="submit" href="cetak_status.php?iddaftar=<?=$data['id'];?>" class="btn btn-sm btn-secondary">Bukti Pengajuan</a>
						<?php } ?>
							
                        </td>
                    </tr>
                <?php 
                        }while($data = mysqli_fetch_assoc($query));
                    }else{

                        // jika false
                        echo "<tr><td colspan='7'><center>Belum ada data!</center></td></tr>";
                    }
                ?>
                </tbody>
            </table>
			<div class="mb-3 text-center d-grid gap-md-2 mx-auto">
                 <button class="GFG" onclick="window.location.href = 'pendaftaran.php';">
					Ajukan Usul
				</button>
             </div>
        </div>
    </div>
</div>

<?php

include 'footer.php';

} else {
    echo "<script>
            alert('Silahkan Login Terlebih Dahulu!');
            window.location = 'login.php';
        </script>";
}

?>