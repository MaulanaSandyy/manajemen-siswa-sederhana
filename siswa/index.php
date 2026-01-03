<?php
include '../config/koneksi.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'siswa') {
    header("location: ../login.php");
    exit;
}

$id_siswa = $_SESSION['id_user'];

$hadir = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM absensi WHERE siswa_id='$id_siswa' AND status='Hadir'");
$data_hadir = mysqli_fetch_assoc($hadir);
$total_hadir = $data_hadir['total'];

$poin = mysqli_query($koneksi, "SELECT SUM(poin) as total FROM pelanggaran WHERE siswa_id='$id_siswa'");
$data_poin = mysqli_fetch_assoc($poin);
$total_poin = $data_poin['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Siswa - SMK WIJAYA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <?php include '../layout/sidebar.php'; ?>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Halo, <strong><?php echo $_SESSION['nama']; ?></strong> 👋</h4>
            <span class="badge bg-info px-3 py-2">Siswa</span>
        </div>

        <div class="row mt-4">
            <div class="col-md-6 mb-4">
                <div class="card card-custom bg-white p-4">
                    <div class="d-flex align-items-center">
                        <div class="fs-1 me-3">📅</div>
                        <div>
                            <h6 class="text-muted mb-0">Total Hadir</h6>
                            <h2 class="fw-bold mb-0"><?php echo $total_hadir; ?> Hari</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card card-custom p-4 <?php echo ($total_poin > 0) ? 'bg-danger text-white' : 'bg-success text-white'; ?>">
                    <div class="d-flex align-items-center">
                        <div class="fs-1 me-3">⚠️</div>
                        <div>
                            <h6 class="mb-0">Poin Pelanggaran</h6>
                            <h2 class="fw-bold mb-0"><?php echo $total_poin; ?> Poin</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 p-4 bg-white rounded-4 shadow-sm">
            <h5>Dashboard Siswa</h5>
            <p class="text-muted">Cek kehadiran dan pantau poin pelanggaran Anda di sini.</p>
            
            <div class="mt-3">
                <a href="lihat_absen.php" class="btn btn-primary">Lihat Absensi</a>
                <a href="lihat_pelanggaran.php" class="btn btn-warning">Lihat Pelanggaran</a>
                <a href="logout.php" class="btn btn-outline-danger">Keluar</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>