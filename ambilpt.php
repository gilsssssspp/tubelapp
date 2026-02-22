<?php
session_start();
include 'koneksi.php';

// terima parameter apa pun: id_prodi / id_kampus
$id_kampus = $_GET['id_prodi'] ?? ($_GET['id_kampus'] ?? '');
$id_kampus = trim($id_kampus);

echo "<option value=''>Pilih Program Studi</option>";
while($r = mysqli_fetch_assoc($q)){
  echo "<option value='{$r['id']}'>{$r['nama_prodi']}</option>";
}


if ($id_kampus === '') {
  echo "<option value='0'>[DEBUG] id_kampus kosong</option>";
  exit;
}

// ambil tingkat pendidikan pegawai
$nip = $_SESSION['nip'] ?? '';
$tingkat = '';

if ($nip !== '') {
  $qPeg = mysqli_query($db, "SELECT tingkat_pendidikan FROM mr_pegawai WHERE nip='$nip' LIMIT 1");
  if ($qPeg && mysqli_num_rows($qPeg) > 0) {
    $rPeg = mysqli_fetch_assoc($qPeg);
    $tingkat = strtoupper(trim($rPeg['tingkat_pendidikan'] ?? ''));
  }
}

// filter jenjang
$whereJenjang = "";
if ($tingkat === 'SARJANA') {
  $whereJenjang = "AND nama_prodi LIKE 'S2-%'";
} else {
  $whereJenjang = "AND (nama_prodi LIKE 'S2-%' OR nama_prodi LIKE 'S3-%')";
}

// QUERY
$sql = "
  SELECT id, nama_prodi
  FROM mr_prodi
  WHERE id_kampus = '$id_kampus'
  $whereJenjang
  ORDER BY nama_prodi ASC
";

$q = mysqli_query($db, $sql);

if (!$q) {
  //echo "<option value='0'>[DEBUG] SQL error: ".htmlspecialchars(mysqli_error($db))."</option>";
  echo "<option value='0'>[DEBUG] SQL error: ".htmlspecialchars(mysqli_error($db))."</option>";
  exit;
}

if (mysqli_num_rows($q) === 0) {
  // Menampilkan pesan jika tidak ada data yang ditemukan
  echo "<script>
          Swal.fire({
            icon: 'warning',
            title: 'Data Tidak Ditemukan',
            text: 'Belum ada prodi di database kami untuk Perguruan Tinggi tersebut. Mohon hubungi admin untuk penambahan data.',
            confirmButtonText: 'OK',
            allowOutsideClick: false
          });
        </script>";
  exit;
}


while ($p = mysqli_fetch_assoc($q)) {
  $id = htmlspecialchars($p['id']);
  $nama = htmlspecialchars($p['nama_prodi']);
  echo "<option value='{$id}'>{$nama}</option>";
}
