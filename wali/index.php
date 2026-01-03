<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login" || $_SESSION['role'] != 'wali') {
    header("location: ../login.php");
    exit;
}

$username = $_SESSION['username'];

$sql = "SELECT siswa.*, kelas.nama_kelas FROM siswa 
        LEFT JOIN kelas ON siswa.kelas_id = kelas.id 
        WHERE siswa.username_wali = '$username'";
$query = mysqli_query($koneksi, $sql);

$data = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Wali - SMK WIJAYA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php include '../layout/sidebar.php'; ?>

<div class="main-content">
    <h3 class="fw-bold mb-4">Dashboard Wali Murid</h3>

    <?php if (!$data) : ?>
        <div class="alert alert-warning">
            Data anak belum terhubung. Hubungi Admin sekolah.
        </div>
    <?php else : ?>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card card-custom p-4 shadow-sm">
                    <h5 class="fw-bold mb-3">Informasi Siswa</h5>
                    <table class="table">
                        <tr><th>Nama Siswa</th><td><?php echo $data['nama_siswa']; ?></td></tr>
                        <tr><th>Kelas</th><td><?php echo $data['nama_kelas']; ?></td></tr>
                        <tr><th>Alamat</th><td><?php echo $data['alamat']; ?></td></tr>
                    </table>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card card-custom p-4 shadow-sm">
                    <h5 class="fw-bold mb-3">Poin Pelanggaran</h5>
                    <?php 
                    $id_siswa = $data['id'];
                    $poin = mysqli_query($koneksi, "SELECT SUM(poin) as total FROM pelanggaran WHERE siswa_id='$id_siswa'");
                    $total = mysqli_fetch_assoc($poin);
                    $poin_total = $total['total'] ?? 0;
                    ?>
                    <div class="text-center">
                        <h1 class="display-2 fw-bold text-danger"><?php echo $poin_total; ?></h1>
                        <p>Total Poin</p>
                        <?php if($poin_total < 40): ?>
                            <span class="badge bg-success">Aman</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Perhatian</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-custom p-4 shadow-sm">
            <h5 class="fw-bold mb-3">Riwayat Absensi</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $absensi = mysqli_query($koneksi, "SELECT * FROM absensi WHERE siswa_id='$id_siswa' ORDER BY tanggal DESC LIMIT 10");
                        while($a = mysqli_fetch_array($absensi)) {
                            $warna = "success";
                            if($a['status'] == 'Sakit') $warna = "primary";
                            elseif($a['status'] == 'Izin') $warna = "info";
                            elseif($a['status'] == 'Alpha') $warna = "danger";
                        ?>
                        <tr>
                            <td><?php echo $a['tanggal']; ?></td>
                            <td><span class="badge bg-<?php echo $warna; ?>"><?php echo $a['status']; ?></span></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>