<?php
include '../config/koneksi.php';
session_start();

// Proteksi Admin
if ($_SESSION['role'] != 'admin') {
    header("location:../login.php");
    exit;
}

// --- PROSES SIMPAN DATA ---
if (isset($_POST['tombol_simpan'])) {
    $no_siswa   = mysqli_real_escape_string($koneksi, $_POST['no_siswa']);
    $nama_siswa = mysqli_real_escape_string($koneksi, $_POST['nama_siswa']);
    $id_kelas   = $_POST['id_kelas'];
    $alamat     = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    
    // Akun Siswa (Teks Biasa)
    $username   = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password   = $_POST['password']; 
    
    // Ambil Data Wali dari Dropdown (Tabel Users)
    $id_user_wali = $_POST['id_user_wali'];
    $query_wali   = mysqli_query($koneksi, "SELECT * FROM users WHERE id = '$id_user_wali'");
    $data_wali    = mysqli_fetch_assoc($query_wali);
    
    $nama_wali     = $data_wali['nama_user'];
    $username_wali = $data_wali['username'];

    // 1. Simpan ke tabel siswa
    $q_siswa = "INSERT INTO siswa (no_siswa, nama_siswa, username, password, kelas_id, alamat, nama_wali, username_wali) 
                VALUES ('$no_siswa', '$nama_siswa', '$username', '$password', '$id_kelas', '$alamat', '$nama_wali', '$username_wali')";
    
    // 2. Simpan Akun Siswa ke tabel users (Agar Siswa bisa login)
    $q_user = "INSERT INTO users (username, password, role_user, nama_user) 
               VALUES ('$username', '$password', 'siswa', '$nama_siswa')";

    if (mysqli_query($koneksi, $q_siswa) && mysqli_query($koneksi, $q_user)) {
        echo "<script>alert('Siswa Berhasil Ditambahkan & Terhubung dengan Wali!'); window.location='../tambah-siswa/';</script>";
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}

// --- PROSES HAPUS DATA ---
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    // Ambil username siswa untuk menghapus akun di tabel users juga
    $dt = mysqli_fetch_array(mysqli_query($koneksi, "SELECT username FROM siswa WHERE id='$id'"));
    $user_siswa = $dt['username'];

    mysqli_query($koneksi, "DELETE FROM users WHERE username='$user_siswa'");
    mysqli_query($koneksi, "DELETE FROM siswa WHERE id='$id'");
    header("location:../tambah-siswa/");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Siswa - SMK BISA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .select2-container--bootstrap-5 .select2-selection { height: 38px; }
    </style>
</head>


<body class="bg-light">
    
<?php include '../layout/sidebar.php'; ?>

<div class="container py-4">
    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 p-4 mb-4">
                <h5 class="fw-bold text-primary mb-3">Tambah Siswa Baru</h5>
                <form method="POST">
                    <div class="mb-2">
                        <label class="small fw-bold">NIS</label>
                        <input type="text" name="no_siswa" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label class="small fw-bold">Nama Lengkap Siswa</label>
                        <input type="text" name="nama_siswa" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-2">
                            <label class="small fw-bold">User Siswa</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="col-6 mb-2">
                            <label class="small fw-bold">Pass Siswa</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold text-success">Pilih Wali Murid (Cari Nama/User)</label>
                        <select name="id_user_wali" class="form-control select2-wali" required>
                            <option value="">-- Cari Wali --</option>
                            <?php
                            $wali = mysqli_query($koneksi, "SELECT * FROM users WHERE role_user = 'wali'");
                            while($w = mysqli_fetch_array($wali)){
                                echo "<option value='".$w['id']."'>".$w['nama_user']." (".$w['username'].")</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="small fw-bold">Kelas</label>
                        <select name="id_kelas" class="form-select" required>
                            <option value="">-- Pilih Kelas --</option>
                            <?php
                            $kelas = mysqli_query($koneksi, "SELECT * FROM kelas");
                            while($k = mysqli_fetch_array($kelas)){
                                echo "<option value='".$k['id']."'>".$k['nama_kelas']."</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="2"></textarea>
                    </div>
                    <button type="submit" name="tombol_simpan" class="btn btn-primary w-100 fw-bold">SIMPAN DATA</button>
                </form>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm border-0 p-4">
                <h5 class="fw-bold mb-3">Daftar Siswa & Relasi Wali</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle" style="font-size: 0.9rem;">
                        <thead class="table-dark">
                            <tr>
                                <th>NIS</th>
                                <th>Siswa</th>
                                <th>Kelas</th>
                                <th>Wali Murid (Anak Dari)</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $res = mysqli_query($koneksi, "SELECT siswa.*, kelas.nama_kelas FROM siswa JOIN kelas ON siswa.kelas_id = kelas.id ORDER BY siswa.id DESC");
                            if(mysqli_num_rows($res) == 0) echo "<tr><td colspan='5' class='text-center'>Belum ada data.</td></tr>";
                            while($row = mysqli_fetch_array($res)){
                            ?>
                            <tr>
                                <td><?php echo $row['no_siswa']; ?></td>
                                <td>
                                    <strong><?php echo $row['nama_siswa']; ?></strong><br>
                                    <!-- <small class="text-muted">User: <?php echo $row['nama_siswa']; ?></small> -->
                                </td>
                                <td><span class="badge bg-light text-dark border"><?php echo $row['nama_kelas']; ?></span></td>
                                <td>
                                    <?php echo $row['nama_wali']; ?><br>
                                    <small class="text-success">@<?php echo $row['username_wali']; ?></small>
                                </td>
                                <td class="text-center">
                                    <a href="tambah_siswa.php?hapus=<?php echo $row['id']; ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Hapus siswa dan akun loginnya?')">🗑️</a>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2-wali').select2({
            theme: 'classic',
            width: '100%'
        });
    });
</script>
</body>
</html>