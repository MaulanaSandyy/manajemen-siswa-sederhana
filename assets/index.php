<?php
session_start();

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    echo "<script>
        alert('Anda harus login terlebih dahulu!');
        window.location.href = '../index.php';
    </script>";
    exit;
}
?>