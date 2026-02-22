<?php

$config = require __DIR__ . '/config.php';

$dbConf = $config['db'];

$db = mysqli_connect(
    $dbConf['host'],
    $dbConf['user'],
    $dbConf['pass'],
    $dbConf['name']
);

if (!$db) {
    die("Koneksi database gagal.");
}