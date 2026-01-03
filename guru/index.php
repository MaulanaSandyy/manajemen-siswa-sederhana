<?php
include '../config/koneksi.php';
session_start();

if ($_SESSION['role'] != 'guru') {
    header("location:../login.php");
    exit;
}

$hari_ini = date('Y-m-d');

$total_absen = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM absensi WHERE tanggal='$hari_ini'"));
$total_langgar = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM pelanggaran WHERE tanggal='$hari_ini'"));
$total_siswa = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa"));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Guru - SMK WIJAYA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    
    <?php include '../layout/sidebar.php'; ?>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Selamat Datang, <strong><?php echo $_SESSION['nama']; ?></strong> 👋</h4>
            <span class="badge bg-success px-3 py-2">Role: Guru Pengajar</span>
        </div>

        <div class="row mt-4">
            <div class="col-md-4 mb-4">
                <div class="card card-custom bg-white p-4">
                    <div class="d-flex align-items-center">
                        <div class="fs-1 me-3">📅</div>
                        <div>
                            <h6 class="text-muted mb-0">Absen Hari Ini</h6>
                            <h2 class="fw-bold mb-0"><?php echo $total_absen; ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card card-custom bg-danger text-white p-4">
                    <div class="d-flex align-items-center">
                        <div class="fs-1 me-3">⚠️</div>
                        <div>
                            <h6 class="text-white-50 mb-0">Pelanggaran Baru</h6>
                            <h2 class="fw-bold mb-0"><?php echo $total_langgar; ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card card-custom bg-white p-4">
                    <div class="d-flex align-items-center">
                        <div class="fs-1 me-3">👨‍🎓</div>
                        <div>
                            <h6 class="text-muted mb-0">Total Siswa</h6>
                            <h2 class="fw-bold mb-0"><?php echo $total_siswa; ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 p-5 bg-white rounded-4 shadow-sm">
            <h3>Halo, Bapak/Ibu Guru!</h3>
            <p class="text-muted">Gunakan menu di samping untuk mencatat kehadiran siswa atau memberikan poin pelanggaran jika terjadi tindakan indisipliner hari ini.</p>
        </div>
    </div>

</body>
</html>