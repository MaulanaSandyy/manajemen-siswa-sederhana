<?php
session_start();
include '../../config/koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'wali') {
    header("location: ../../login/");
    exit;
}

$username = $_SESSION['username'];

$data = mysqli_query($koneksi, "SELECT id, nama_siswa FROM siswa WHERE username_wali = '$username'");
$siswa = mysqli_fetch_assoc($data);

if ($siswa) {
    $id_siswa = $siswa['id'];
    $absensi = mysqli_query($koneksi, "SELECT * FROM absensi WHERE siswa_id = '$id_siswa' ORDER BY tanggal DESC");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Absensi Siswa - SMK WIJAYA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<?php include '../../layout/sidebar.php'; ?>

<div class="main-content">
    <h3 class="fw-bold mb-4">Absensi <?php echo isset($siswa['nama_siswa']) ? $siswa['nama_siswa'] : 'Siswa'; ?></h3>

    <div class="card card-custom p-4 shadow-sm">
        <?php if (isset($siswa)): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($absensi) > 0): ?>
                            <?php while ($a = mysqli_fetch_array($absensi)): ?>
                            <tr>
                                <td><?php echo date('d/m/Y', strtotime($a['tanggal'])); ?></td>
                                <td>
                                    <?php
                                    if($a['status'] == 'Hadir') echo '<span class="badge bg-success">Hadir</span>';
                                    elseif($a['status'] == 'Sakit') echo '<span class="badge bg-primary">Sakit</span>';
                                    elseif($a['status'] == 'Izin') echo '<span class="badge bg-info">Izin</span>';
                                    else echo '<span class="badge bg-danger">Alpha</span>';
                                    ?>
                                </td>
                                <td><?php echo isset($a['catatan']) ? $a['catatan'] : '-'; ?></td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted">Belum ada data absensi</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">
                Data siswa tidak ditemukan
            </div>
        <?php endif; ?>
        
        <a href="../" class="btn btn-secondary mt-3">Kembali</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>