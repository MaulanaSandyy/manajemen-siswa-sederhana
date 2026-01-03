<?php
session_start();

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location: ../login/");
    exit;
}

if ($_SESSION['role'] != 'guru' && $_SESSION['role'] != 'admin') {
    header("location: ../login/");
    exit;
}

include '../config/koneksi.php';

$siswa = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa"));
$kelas = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM kelas"));
$pelanggaran = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM pelanggaran"));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SMK WIJAYA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    
    <?php include '../layout/sidebar.php'; ?>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Selamat Datang, <strong><?php echo $_SESSION['nama']; ?></strong> 👋</h4>
            <span class="badge bg-primary px-3 py-2">Role: <?php echo $_SESSION['role']; ?></span>
        </div>

        <div class="row mt-4">
            <div class="col-md-4 mb-4">
                <div class="card card-custom bg-white p-4">
                    <div class="d-flex align-items-center">
                        <div class="fs-1 me-3">👨‍🎓</div>
                        <div>
                            <h6 class="text-muted mb-0">Total Siswa</h6>
                            <h2 class="fw-bold mb-0"><?php echo $siswa; ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card card-custom bg-white p-4">
                    <div class="d-flex align-items-center">
                        <div class="fs-1 me-3">🏫</div>
                        <div>
                            <h6 class="text-muted mb-0">Total Kelas</h6>
                            <h2 class="fw-bold mb-0"><?php echo $kelas; ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card card-custom bg-danger text-white p-4">
                    <div class="d-flex align-items-center">
                        <div class="fs-1 me-3">⚠️</div>
                        <div>
                            <h6 class="text-white-50 mb-0">Pelanggaran Tercatat</h6>
                            <h2 class="fw-bold mb-0"><?php echo $pelanggaran; ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 p-5 bg-white rounded-4 shadow-sm">
            <h3>Dashboard Admin</h3>
            <p class="text-muted">Ini adalah pusat kendali sistem sekolah. Anda bisa mengelola data kelas, siswa, dan memantau kedisiplinan.</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>