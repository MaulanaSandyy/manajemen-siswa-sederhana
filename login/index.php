<?php
session_start();

include '../config/koneksi.php';

if (isset($_POST['login'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$user' AND password='$pass'");
   
    if (mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_assoc($query);

        $_SESSION['id_user']  = $data['id'];
        $_SESSION['username']  = $data['username'];
        $_SESSION['nama']     = $data['nama_user'];
        $_SESSION['role']     = $data['role_user'];
        $_SESSION['status']   = "login";

        if ($data['role_user'] == "admin") {
            header("location:../dashboard/");
        } else if ($data['role_user'] == "guru") {
            header("location:../guru/");
        } else if ($data['role_user'] == "siswa") {
            header("location:../siswa/");
        } else if ($data['role_user'] == "wali") {
            header("location:../wali/");
        }
    } else {
        $error = "Username atau Password Salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Login - SMK WIJAYA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), 
                        url('../assets/gambar/back.png');
            background-size: cover;
            height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4" data-aos="zoom-in">
            <div class="login-card">
                <div class="text-center mb-4">
                    <h3 class="fw-bold text-primary">SMK WIJAYA</h3>
                    <p class="text-muted">Silakan masuk ke akun Anda</p>
                </div>

                <?php if(isset($error)) : ?>
                    <div class="alert alert-danger p-2 small text-center"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                    </div>
                    <button type="submit" name="login" class="btn btn-primary w-100 py-2 fw-bold">MASUK</button>
                    <a href="../" class="d-block text-center mt-3 text-decoration-none text-muted small">← Kembali ke Beranda</a>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>AOS.init();</script>
</body>
</html>