<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login" || $_SESSION['role'] != 'siswa') {
    header("location: ../login.php");
    exit;
}

$id_siswa = $_SESSION['id_user'];

$pelanggaran = mysqli_query($koneksi, "SELECT * FROM pelanggaran WHERE siswa_id = '$id_siswa' ORDER BY tanggal DESC");

$total = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(poin) as total FROM pelanggaran WHERE siswa_id = '$id_siswa'"));
$total_poin = $total['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pelanggaran Siswa - SMK WIJAYA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php include '../layout/sidebar.php'; ?>

<div class="main-content">
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card bg-danger text-white p-4">
                <div class="text-center">
                    <h5 class="mb-2">Total Poin Pelanggaran</h5>
                    <h1 class="display-4 fw-bold mb-3"><?php echo $total_poin; ?></h1>
                    <?php
                    if($total_poin >= 71) {
                        echo '<span class="badge bg-dark">Drop Out (DO)</span>';
                    } elseif($total_poin >= 40) {
                        echo '<span class="badge bg-warning text-dark">Peringatan Keras</span>';
                    } else {
                        echo '<span class="badge bg-light text-dark">Aman</span>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom p-4 shadow-sm">
        <h5 class="fw-bold mb-4">Riwayat Pelanggaran</h5>
        
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Jenis Pelanggaran</th>
                        <th class="text-center">Poin</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if(mysqli_num_rows($pelanggaran) > 0) {
                        $no = 1;
                        while($row = mysqli_fetch_array($pelanggaran)) {
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($row['tanggal'])); ?></td>
                        <td><?php echo $row['jenis_pelanggaran']; ?></td>
                        <td class="text-center">
                            <span class="badge bg-danger"><?php echo $row['poin']; ?></span>
                        </td>
                    </tr>
                    <?php 
                        }
                    } else {
                        echo "<tr><td colspan='4' class='text-center text-muted'>Tidak ada pelanggaran</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            <a href="index.php" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>