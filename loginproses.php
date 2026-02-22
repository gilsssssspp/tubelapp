<?php
ob_start();
session_start();
require 'koneksi.php';

$config = require __DIR__ . '/config.php';
$apiConf = $config['api'];
$environment = $config['environment'] ?? 'production';

$api_username = $apiConf['username'] ?? '';
$api_password = $apiConf['password'] ?? '';

$max_attempts = 5;
$lock_minutes = 10;
$ip = $_SERVER['REMOTE_ADDR'];
$user = trim($_POST['name'] ?? '');
$pass = $_POST['pass'] ?? '';

/* =========================
   HELPER
========================= */

function login_gagal($pesan)
{
    $_SESSION['login_error'] = $pesan;
    header("Location: login.php");
    exit;
}

function login_sukses()
{
    session_regenerate_id(true);
    header("Location: index2.php");
    exit;
}

function curl_post($url, $fields, $headers = [], $environment = 'production')
{
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($fields),
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_TIMEOUT => 20
    ]);

    if ($environment === 'production') {
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
    } else {
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    }

    $response = curl_exec($curl);
    curl_close($curl);

    return $response;
}

function get_api_token($apiConf, $api_username, $api_password, $environment)
{
    $cacheFile = __DIR__ . '/token_cache.json';

    if (file_exists($cacheFile)) {
        $cache = json_decode(file_get_contents($cacheFile), true);
        if (!empty($cache['token']) && $cache['expired_at'] > time()) {
            return $cache['token'];
        }
    }

    $response = curl_post(
        $apiConf['token_url'],
        [
            'username' => $api_username,
            'password' => $api_password
        ],
        ['Content-Type: application/x-www-form-urlencoded'],
        $environment
    );

    $tokenData = json_decode($response, true);

    if (empty($tokenData['access_token'])) {
        login_gagal("Gagal mendapatkan token SSO.");
    }

    $token = $tokenData['access_token'];

    file_put_contents($cacheFile, json_encode([
        'token' => $token,
        'expired_at' => time() + 3000
    ]));

    return $token;
}

/* =========================
   VALIDASI METHOD
========================= */

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit;
}

if ($user === '' || $pass === '') {
    login_gagal("Username/NIP dan Password wajib diisi.");
}

/* =========================
   CEK LOGIN ATTEMPT (DB)
========================= */

$stmt = $db->prepare("SELECT attempt_count, last_attempt FROM login_attempts WHERE ip_address=? AND username=?");
$stmt->bind_param("ss", $ip, $user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $last_attempt = strtotime($row['last_attempt']);
    $attempt_count = $row['attempt_count'];

    if ($attempt_count >= $max_attempts && (time() - $last_attempt) < ($lock_minutes * 60)) {

        $remaining = ($lock_minutes * 60) - (time() - $last_attempt);
        $minutes = ceil($remaining / 60);

        login_gagal("Terlalu banyak percobaan login. Coba lagi dalam $minutes menit.");
    }
}

/* =========================
   ADMIN LOKAL
========================= */

$stmt = $db->prepare("SELECT id, name, pass, type FROM user WHERE name=?");
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();

    if (password_verify($pass, $row['pass'])) {

        $db->query("DELETE FROM login_attempts WHERE ip_address='$ip' AND username='$user'");

        $_SESSION['nip']  = $row['name'];
        $_SESSION['nama'] = $row['name'];
        $_SESSION['type'] = $row['type'] ?? 'admin';
        login_sukses();
    }
}

/* =========================
   CEK CACHE PEGAWAI 6 JAM
========================= */

$stmt = $db->prepare("SELECT * FROM mr_pegawai WHERE nip=?");
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();

    if (!empty($row['last_sync']) && strtotime($row['last_sync']) > (time() - 21600)) {
        $db->query("DELETE FROM login_attempts WHERE ip_address='$ip' AND username='$user'");

        $_SESSION['nip']  = $row['nip'];
        $_SESSION['nama'] = $row['nama'];
        $_SESSION['type'] = $row['type'];
        login_sukses();
    }
}

/* =========================
   SSO LOGIN
========================= */

$authToken = get_api_token($apiConf, $api_username, $api_password, $environment);

$loginResponse = curl_post(
    $apiConf['login_url'],
    ['nip' => $user, 'password' => $pass],
    [
        'Content-Type: application/x-www-form-urlencoded',
        "Auth: $authToken"
    ],
    $environment
);

$simpegData = json_decode($loginResponse, true);

if (($simpegData['code'] ?? 0) != 1) {

    // update attempt
    $db->query("
        INSERT INTO login_attempts (ip_address, username, attempt_count, last_attempt)
        VALUES ('$ip','$user',1,NOW())
        ON DUPLICATE KEY UPDATE
        attempt_count = attempt_count + 1,
        last_attempt = NOW()
    ");

    login_gagal("Username/NIP atau password salah.");
}

/* =========================
   LOGIN SUKSES → RESET ATTEMPT
========================= */

$db->query("DELETE FROM login_attempts WHERE ip_address='$ip' AND username='$user'");

$simpegUser = $simpegData['data'] ?? [];

$nip        = $simpegUser['nip'] ?? '';
$nama       = $simpegUser['nama'] ?? '';
$jabatan    = $simpegUser['jabatan'] ?? '';
$email      = $simpegUser['email'] ?? '';
$nohp       = $simpegUser['nomor_hp'] ?? '';
$tmp_lahir  = $simpegUser['tempat_lahir'] ?? '';
$tgl_lahir  = $simpegUser['tanggal_lahir'] ?? '';
$golpangkat = $simpegUser['golongan_ruang'] ?? '';
$komponen   = $simpegUser['komponen'] ?? '';
$unitkerja  = $simpegUser['unit_kerja'] ?? '';

if (!empty($tgl_lahir)) {
    $tgl_lahir = date('Y-m-d', strtotime(str_replace('/', '-', $tgl_lahir)));
}

/* =========================
   UPSERT PEGAWAI
========================= */

$stmt = $db->prepare("
INSERT INTO mr_pegawai (
    nip,nama,jabatan,email,no_hp,tmp_lahir,tgl_lahir,
    gol_pangkat,komponen,unit_kerja,tingkat_pendidikan,type,last_sync
) VALUES (?,?,?,?,?,?,?,?,?,?,?,'user',NOW())
ON DUPLICATE KEY UPDATE
    nama=VALUES(nama),
    jabatan=VALUES(jabatan),
    email=VALUES(email),
    no_hp=VALUES(no_hp),
    tmp_lahir=VALUES(tmp_lahir),
    tgl_lahir=VALUES(tgl_lahir),
    gol_pangkat=VALUES(gol_pangkat),
    komponen=VALUES(komponen),
    unit_kerja=VALUES(unit_kerja),
    last_sync=NOW()
");

$stmt->bind_param(
    "sssssssssss",
    $nip,$nama,$jabatan,$email,$nohp,
    $tmp_lahir,$tgl_lahir,$golpangkat,
    $komponen,$unitkerja,$tingkatpendidikan
);

$stmt->execute();

$_SESSION['nip']  = $nip;
$_SESSION['nama'] = $nama;
$_SESSION['type'] = 'user';

login_sukses();