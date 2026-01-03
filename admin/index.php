<?php
include '../config/koneksi.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("location: ../login.php");
    exit;
}

if (isset($_POST['tombol_daftar'])) {
    $nama = $_POST['nama_user'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $cek = mysqli_query($koneksi, "SELECT id FROM users WHERE username='$username'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Username sudah ada!'); history.back();</script>";
        exit;
    }

    mysqli_query($koneksi, "INSERT INTO users (username, password, nama_user, role_user) VALUES ('$username', '$password', '$nama', '$role')");
    echo "<script>alert('User berhasil ditambah!'); window.location='../admin/';</script>";
    exit;
}

if (isset($_GET['hapus'])) {
    if ($_SESSION['role'] != 'admin') {
        header("location: ../login.php");
        exit;
    }

    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM users WHERE id='$id'");
    header("location: ../admin/");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi Pengguna - SMK WIJAYA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php include '../layout/sidebar.php'; ?>

<div class="main-content">
    <h3 class="fw-bold mb-4">Registrasi Akun Baru</h3>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card card-custom p-4 shadow-sm">
                <h5 class="fw-bold mb-3 text-primary">Tambah User</h5>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama_user" class="form-control" placeholder="Nama" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="Username" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select" required>
                            <option value="">Pilih Role</option>
                            <option value="guru">Guru</option>
                            <option value="siswa">Siswa</option>
                            <option value="wali">Wali Murid</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <button type="submit" name="tombol_daftar" class="btn btn-primary w-100 fw-bold">Tambah User</button>
                </form>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card card-custom p-4 shadow-sm">
                <h5 class="fw-bold mb-3">Daftar Akun Sistem</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Nama</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $data = mysqli_query($koneksi, "SELECT * FROM users ORDER BY role_user");
                            
                            while ($user = mysqli_fetch_array($data)) {
                                if ($user['role_user'] == 'admin') $warna = "bg-dark";
                                elseif ($user['role_user'] == 'guru') $warna = "bg-primary";
                                elseif ($user['role_user'] == 'siswa') $warna = "bg-info";
                                elseif ($user['role_user'] == 'wali') $warna = "bg-success";
                                else $warna = "bg-secondary";
                            ?>
                            <tr>
                                <td><strong><?php echo $user['nama_user']; ?></strong></td>
                                <td><?php echo $user['username']; ?></td>
                                <td><span class="badge <?php echo $warna; ?>"><?php echo $user['role_user']; ?></span></td>
                                <td class="text-center">
                                    <?php if ($user['id'] != $_SESSION['id_user']) : ?>
                                        <a href="../admin/?hapus=<?php echo $user['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus akun ini?')">Hapus</a>
                                    <?php else: ?>
                                        <span class="text-muted">Aktif</span>
                                    <?php endif; ?>
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