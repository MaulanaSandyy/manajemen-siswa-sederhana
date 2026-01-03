<?php
include '../config/koneksi.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("location: ../login.php");
    exit;
}

if (isset($_POST['tombol_simpan'])) {
    $nama_kelas = $_POST['nama_kelas'];
    $kapasitas = $_POST['kapasitas'];
    $wali_kelas = $_POST['wali_kelas'];

    $query = "INSERT INTO kelas (nama_kelas, kapasitas, wali_kelas) VALUES ('$nama_kelas', '$kapasitas', '$wali_kelas')";
    $simpan = mysqli_query($koneksi, $query);

    if ($simpan) {
        echo "<script>alert('Kelas Berhasil Ditambah!'); window.location='tambah_kelas.php';</script>";
    }
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM kelas WHERE id='$id'");
    header("location: tambah_kelas.php");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Kelas - SMK WIJAYA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
 
    <?php include '../layout/sidebar.php'; ?>

    <div class="main-content">
        <h3 class="fw-bold mb-4">Kelola Data Kelas</h3>

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card card-custom p-4 shadow-sm">
                    <h5 class="fw-bold mb-3 text-primary">Tambah Kelas Baru</h5>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nama Kelas</label>
                            <input type="text" name="nama_kelas" class="form-control" placeholder="XII RPL 1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kapasitas Siswa</label>
                            <input type="number" name="kapasitas" class="form-control" placeholder="36" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Wali Kelas</label>
                            <input type="text" name="wali_kelas" class="form-control" placeholder="Nama Guru" required>
                        </div>
                        <button type="submit" name="tombol_simpan" class="btn btn-primary w-100 fw-bold">Simpan Kelas</button>
                    </form>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card card-custom p-4 shadow-sm">
                    <h5 class="fw-bold mb-3">Daftar Kelas</h5>
                    
                    <form method="GET" class="mb-3">
                        <div class="input-group">
                            <input type="text" name="cari" class="form-control" placeholder="Cari kelas atau wali kelas..." value="<?php echo isset($_GET['cari']) ? $_GET['cari'] : ''; ?>">
                            <button class="btn btn-secondary" type="submit">Cari</button>
                            <?php if(isset($_GET['cari'])): ?>
                                <a href="tambah_kelas.php" class="btn btn-outline-danger">Reset</a>
                            <?php endif; ?>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kelas</th>
                                    <th>Kapasitas</th>
                                    <th>Wali Kelas</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $cari = isset($_GET['cari']) ? $_GET['cari'] : '';
                                
                                $sql = "SELECT * FROM kelas WHERE nama_kelas LIKE '%$cari%' OR wali_kelas LIKE '%$cari%' ORDER BY nama_kelas";
                                $data = mysqli_query($koneksi, $sql);
                                
                                while ($row = mysqli_fetch_array($data)) {
                                ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><strong><?php echo $row['nama_kelas']; ?></strong></td>
                                    <td><?php echo $row['kapasitas']; ?> Siswa</td>
                                    <td><?php echo $row['wali_kelas']; ?></td>
                                    <td>
                                        <a href="tambah_kelas.php?hapus=<?php echo $row['id']; ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</a>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>