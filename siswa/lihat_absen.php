<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login" || $_SESSION['role'] != 'siswa') {
    header("location: ../login.php");
    exit;
}

$id_siswa = $_SESSION['id_user'];

$absensi = mysqli_query($koneksi, "SELECT * FROM absensi WHERE siswa_id = '$id_siswa' ORDER BY tanggal DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kehadiran Siswa - SMK WIJAYA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php include '../layout/sidebar.php'; ?>

<div class="main-content">
    <h3 class="fw-bold mb-4">Riwayat Kehadiran <?php echo $_SESSION['nama']; ?></h3>

    <div class="row mb-4">
        <?php
        $hadir = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(id) as total FROM absensi WHERE siswa_id='$id_siswa' AND status='Hadir'"))['total'];
        $sakit = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(id) as total FROM absensi WHERE siswa_id='$id_siswa' AND status='Sakit'"))['total'];
        $izin = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(id) as total FROM absensi WHERE siswa_id='$id_siswa' AND status='Izin'"))['total'];
        $alpha = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(id) as total FROM absensi WHERE siswa_id='$id_siswa' AND status='Alpha'"))['total'];
        ?>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white text-center p-3">
                <h5 class="mb-0"><?php echo $hadir; ?></h5>
                <small>Hadir</small>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white text-center p-3">
                <h5 class="mb-0"><?php echo $sakit; ?></h5>
                <small>Sakit</small>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white text-center p-3">
                <h5 class="mb-0"><?php echo $izin; ?></h5>
                <small>Izin</small>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-danger text-white text-center p-3">
                <h5 class="mb-0"><?php echo $alpha; ?></h5>
                <small>Alpha</small>
            </div>
        </div>
    </div>

    <div class="card card-custom p-4 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if(mysqli_num_rows($absensi) > 0) {
                        $no = 1;
                        while($row = mysqli_fetch_array($absensi)) {
                            if($row['status'] == 'Hadir') $warna = "success";
                            elseif($row['status'] == 'Sakit') $warna = "primary";
                            elseif($row['status'] == 'Izin') $warna = "info";
                            else $warna = "danger";
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($row['tanggal'])); ?></td>
                        <td><span class="badge bg-<?php echo $warna; ?>"><?php echo $row['status']; ?></span></td>
                    </tr>
                    <?php 
                        }
                    } else {
                        echo "<tr><td colspan='3' class='text-center text-muted'>Belum ada data kehadiran</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        
        <a href="index.php" class="btn btn-secondary mt-3">Kembali</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>