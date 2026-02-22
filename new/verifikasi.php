<?php

// session
session_start();

// import koneksi
include 'koneksi.php';

// cek session
if(isset($_SESSION['nip'])){

        $header = "- Tahap Pendaftaran";
    
        // import header
        include 'header.php';

?>

<!-- container -->
<div class="container">    
	<div class="card my-2 o-hidden border-0 shadow-lg">
    
	
        <!-- heading Data seleksi Siswa -->
        <h3 class="card-header text-center">Daftar Pengajuan Tugas/Izin Belajar</h3>
		<div class="card-body container">		
            <!-- Tabel data seleksi siswa -->
            <table class="table table-bordered" id="dataTable">
                
                <!-- header tabel -->
                <thead class="thead-dark">
                    <tr>
                        <th>NO</th>
                        <th>NIP</th>
						<th>NAMA</th>
						<th>JENIS PENGAJUAN</th>						
                        <th>STATUS</th>
                        <th>ACTION</th>
                        <th>UPLOAD SK</th>
                    </tr>
                </thead>

                <!-- body tabel -->
                <tbody>
                    
                <?php  

                    // mengambil data tabel pendaftaran dengan kondisi status Masih Seleksi
                    $query = mysqli_query($db, "SELECT a.*, p.* FROM pendaftaran a
							INNER JOIN mr_pegawai p ON p.nip=a.nip where tgl_submit is not null");
                    $data = mysqli_fetch_array($query);

                    // cek kolom dari pendaftaran
                    if(mysqli_num_rows($query) >0) {
                        $no = 1;
                        
                        // loop data tabel pendaftaran kondisi masih seleksi
                        do{

                ?>
					
					<?php if ($data['status_usul']=='Lulus Verifikasi') {
							echo "<tr style='color:black; background: skyblue'>";
						}
						if ($data['status_usul']=='Tidak Lulus Verifikasi') {
							echo "<tr style='color:black; background: orange'>";
						}
					?>
                        <td><?=$no++;?></td>
						<td><?=$data['nip'];?></td>
                        <td><?=$data['nama'];?></td>
						<td><?=$data['tubel_ibel'];?></td>
						<td><?=$data['status_usul'];?></td>
                        <td>
                            <!--<a role="button" name="submit" href="comingsoon.php" class="btn btn-sm btn-secondary">Verifikasi</a>-->
							<a role="button" name="submit" href="verifikasi_detail.php?iddaftar=<?=$data['id'];?>" class="btn btn-sm btn-secondary">Verifikasi</a>
							<!--<a role="button" name="submit" href="cetakdetail.php?nip=<?=$data['nip'];?>" class="btn btn-sm btn-secondary">Cetak</a>-->
                        </td>
                        <td>
                        <?php
                            // JIKA SK SUDAH ADA
                            if (!empty($data['sk'])) {
                                echo "<span class='text-success font-weight-bold'>
                                        SK sudah diupload
                                    </span>";
                            }
                            // JIKA BELUM ADA SK & STATUS MENUNGGU SK
                            elseif ($data['status_usul'] == 'Menunggu SK') {
                        ?>
                                <form action="/tubelapp/proses/upload_sk_proses.php"
                                    method="post"
                                    enctype="multipart/form-data">

                                    <input type="hidden" name="iddaftar" value="<?=$data['id'];?>">

                                    <input type="file"
                                        name="file_sk"
                                        accept=".pdf,.jpg,.jpeg,.png"
                                        required
                                        style="font-size:12px">

                                    <br>

                                    <button type="submit" class="btn btn-sm btn-primary mt-1">
                                        Upload SK
                                    </button>
                                </form>
                        <?php
                            }
                            // KONDISI LAIN (AMAN)
                            else {
                                echo "<span class='text-muted'>-</span>";
                            }
                        ?>
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
			<!--<a role="button" name="submit" href="cetakall.php" class="btn btn-sm btn-secondary">Cetak All</a>-->
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