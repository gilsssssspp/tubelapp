<?php
session_start();
include 'koneksi.php';

// ============================
// CEK LOGIN
// ============================
if (!isset($_SESSION['nip'])) {
    echo "<script>
            alert('Silahkan Login Terlebih Dahulu!');
            window.location = 'login.php';
          </script>";
    exit;
}

// ============================
// AMBIL FILTER
// ============================
$bulan = isset($_GET['bulan']) ? intval($_GET['bulan']) : '';
$tahun = isset($_GET['tahun']) ? intval($_GET['tahun']) : '';
$jenis_pengajuan = isset($_GET['jenis_pengajuan']) ? $_GET['jenis_pengajuan'] : '';

// ============================
// FUNCTION FORMAT TANGGAL
// ============================
function formatTanggal($tanggal) {
    return ($tanggal && $tanggal != '0000-00-00')
        ? date('d-m-Y', strtotime($tanggal))
        : '-';
}

// ============================
// FUNCTION AMBIL DATA
// ============================
function getDataRekap($db, $bulan = null, $tahun = null, $jenis_pengajuan = null) {

    $where = "WHERE a.hasil_verifikasi = 'Lulus Verifikasi'
              AND a.sk IS NOT NULL";

    if (!empty($bulan) && !empty($tahun)) {
        $where .= " AND MONTH(a.tgl_usul) = '$bulan'
                    AND YEAR(a.tgl_usul) = '$tahun'";
    }

    if (!empty($jenis_pengajuan)) {
        $where .= " AND a.tubel_ibel = '$jenis_pengajuan'";
    }

    $sql = "SELECT
                a.id,
                p.nama,
                p.nip,
                p.jabatan,
                p.unit_kerja,
                a.tgl_usul,
                a.rencana_kuliah,
                a.akhir_studi,
                a.tubel_ibel,
                a.tgl_verifikasi,
                a.tgl_sk_terbit,
                a.sk
            FROM pendaftaran a
            LEFT JOIN mr_pegawai p ON p.nip = a.nip
            $where
            ORDER BY a.id DESC";

    return mysqli_query($db, $sql);
}

// =============================
// MODE PRINT
// =============================
if (isset($_GET['print'])) {

    $query = getDataRekap($db, $bulan, $tahun, $jenis_pengajuan);

    echo "<html>
            <head>
                <title>Print Rekapitulasi</title>
                <style>
                    body { font-family: Arial; }
                    table { border-collapse: collapse; width:100%; font-size:12px; }
                    th, td { border:1px solid black; padding:8px; text-align:center; }
                    th { background:#f2f2f2; }
                    td.jabatan { 
                        white-space: normal; 
                        word-wrap: break-word; 
                        max-width: 150px; 
                    }
                </style>
            </head>
            <body>";

    echo "<h3 style='text-align:center;'>
            Rekapitulasi Data Tubel & Ibel (Selesai Verifikasi)
          </h3>";

    if ($bulan && $tahun) {
        echo "<p style='text-align:center;'>
                Bulan: " . date('F', mktime(0,0,0,$bulan,10)) . " $tahun
              </p>";
    }

    if ($jenis_pengajuan) {
        $jenis_label = ($jenis_pengajuan == 'tubel') ? 'Tugas Belajar' : 'Izin Belajar';
        echo "<p style='text-align:center;'>Jenis Pengajuan: $jenis_label</p>";
    }

    echo "<table>
            <tr>
                <th>NO</th>
                <th>NAMA & NIP</th>
                <th class='jabatan'>JABATAN</th>
                <th>UNIT KERJA</th>
                <th>TANGGAL PENGAJUAN</th>
                <th>TMT MULAI</th>
                <th>TMT SELESAI</th>
                <th>JENIS PENGAJUAN</th>
                <th>TANGGAL VERIFIKASI</th>
                <th>TANGGAL SK</th>
            </tr>";

    $no = 1;

    if ($query && mysqli_num_rows($query) > 0) {
        while ($data = mysqli_fetch_assoc($query)) {

            $jenis = ($data['tubel_ibel'] === 'tubel')
                ? 'Tugas Belajar'
                : 'Izin Belajar';

            echo "<tr>
                    <td>$no</td>
                    <td>".htmlspecialchars($data['nama'])."<br>
                        ".htmlspecialchars($data['nip'])."
                    </td>
                    <td class='jabatan'>".htmlspecialchars($data['jabatan'])."</td>
                    <td>".htmlspecialchars($data['unit_kerja'])."</td>
                    <td>".formatTanggal($data['tgl_usul'])."</td>
                    <td>".formatTanggal($data['rencana_kuliah'])."</td>
                    <td>".formatTanggal($data['akhir_studi'])."</td>
                    <td>$jenis</td>
                    <td>".formatTanggal($data['tgl_verifikasi'])."</td>
                    <td>".formatTanggal($data['tgl_sk_terbit'])."</td>
                  </tr>";
            $no++;
        }
    } else {
        echo "<tr><td colspan='10'>Belum ada data!</td></tr>";
    }

    echo "</table>

        <script>
            window.print();
            window.onafterprint = function(){ window.close(); }
        </script>

        </body></html>";
    exit;
}

// =============================
// MODE EXPORT EXCEL
// =============================
if (isset($_GET['export'])) {

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=rekap_selesai_verifikasi.xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    echo "NO\tNAMA & NIP\tJABATAN\tUNIT KERJA\tTANGGAL PENGAJUAN\tTMT MULAI\tTMT SELESAI\tJENIS PENGAJUAN\tTANGGAL VERIFIKASI\tTANGGAL SK\n";

    $query = getDataRekap($db, $bulan, $tahun, $jenis_pengajuan);
    $no = 1;

    while ($data = mysqli_fetch_assoc($query)) {

        $jenis = ($data['tubel_ibel'] === 'tubel')
            ? 'Tugas Belajar'
            : 'Izin Belajar';

        echo $no . "\t" .
            $data['nama'] . " ( " . $data['nip'] . ")" . "\t" .
            $data['jabatan'] . "\t" .
            $data['unit_kerja'] . "\t" .
            formatTanggal($data['tgl_usul']) . "\t" .
            formatTanggal($data['rencana_kuliah']) . "\t" .
            formatTanggal($data['akhir_studi']) . "\t" .
            $jenis . "\t" .
            formatTanggal($data['tgl_verifikasi']) . "\t" .
            formatTanggal($data['tgl_sk_terbit']) . "\n";

        $no++;
    }

    exit;
}

// ============================
// TAMPILAN NORMAL
// ============================

$header = "- Rekapitulasi Selesai Verifikasi";
include 'header.php';
?>

<div class="container-fluid px-10">
    <div class="card my-3 shadow">

        <h3 class="card-header text-center">
            Rekapitulasi Data Tubel & Ibel (Selesai Verifikasi)
        </h3>

        <div class="card-body">

            <!-- FILTER -->
            <form method="GET" class="mb-3">
                <div class="row">

                    <div class="col-md-3">
                        <select name="bulan" class="form-control">
                            <option value="">-- Pilih Bulan --</option>
                            <?php
                            for ($i=1; $i<=12; $i++) {
                                $selected = ($bulan == $i) ? 'selected' : '';
                                echo "<option value='$i' $selected>" . date('F', mktime(0,0,0,$i,10)) . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <select name="tahun" class="form-control">
                            <option value="">-- Pilih Tahun --</option>
                            <?php
                            for ($t=date('Y'); $t>=2020; $t--) {
                                $selected = ($tahun == $t) ? 'selected' : '';
                                echo "<option value='$t' $selected>$t</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <select name="jenis_pengajuan" class="form-control">
                            <option value="">-- Pilih Jenis Pengajuan --</option>
                            <option value="tubel" <?= ($jenis_pengajuan == 'tubel') ? 'selected' : '' ?>>Tugas Belajar</option>
                            <option value="ibel" <?= ($jenis_pengajuan == 'ibel') ? 'selected' : '' ?>>Izin Belajar</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="<?= strtok($_SERVER['REQUEST_URI'], '?'); ?>" class="btn btn-secondary">Reset</a>
                        <a href="?print=1&bulan=<?= $bulan ?>&tahun=<?= $tahun ?>&jenis_pengajuan=<?= $jenis_pengajuan ?>" target="_blank" class="btn btn-info">Print</a>
                        <a href="?export=1&bulan=<?= $bulan ?>&tahun=<?= $tahun ?>&jenis_pengajuan=<?= $jenis_pengajuan ?>" class="btn btn-success">Export Excel</a>
                    </div>

                </div>
            </form>

            <style>
                #dataTable { width:100%!important; font-size:13px; }
                th, td { vertical-align:middle!important; }
                th.jabatan, td.jabatan {
                    white-space: normal;
                    word-wrap: break-word;
                    max-width: 150px;
                }
            </style>

            <div class="table-responsive">
                <table class="table table-bordered table-sm text-center" id="dataTable">
                    <thead class="thead-light">
                        <tr>
                            <th>NO</th>
                            <th>NAMA & NIP</th>
                            <th class="jabatan">JABATAN</th>
                            <th>UNIT KERJA</th>
                            <th>TANGGAL PENGAJUAN</th>
                            <th>TMT MULAI</th>
                            <th>TMT SELESAI</th>
                            <th>JENIS PENGAJUAN</th>
                            <th>TANGGAL VERIFIKASI</th>
                            <th>TANGGAL SK</th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php
                    $query = getDataRekap($db, $bulan, $tahun, $jenis_pengajuan);
                    $no = 1;

                    if ($query && mysqli_num_rows($query) > 0) {
                        while ($data = mysqli_fetch_assoc($query)) {

                            $jenis = ($data['tubel_ibel'] === 'tubel')
                                ? 'Tugas Belajar'
                                : 'Izin Belajar';
                    ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($data['nama']); ?><br>
                                <?= htmlspecialchars($data['nip']); ?>
                            </td>
                            <td class="jabatan"><?= htmlspecialchars($data['jabatan']); ?></td>
                            <td><?= htmlspecialchars($data['unit_kerja']); ?></td>
                            <td><?= formatTanggal($data['tgl_usul']); ?></td>
                            <td><?= formatTanggal($data['rencana_kuliah']); ?></td>
                            <td><?= formatTanggal($data['akhir_studi']); ?></td>
                            <td><?= $jenis; ?></td>
                            <td><?= formatTanggal($data['tgl_verifikasi']); ?></td>
                            <td><?= formatTanggal($data['tgl_sk_terbit']); ?></td>
                        </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='10'>Belum ada data!</td></tr>";
                    }
                    ?>

                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
