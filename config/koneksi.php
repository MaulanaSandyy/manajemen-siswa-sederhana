<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_sekolah";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Waduh, koneksi gagal: " . mysqli_connect_error());
}
?>