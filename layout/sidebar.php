<div class="sidebar">
    <div class="text-center mb-4">
        <h4 class="text-center fw-bold mb-4">ADMIN PANEL</h4>
        <small class="badge bg-light text-primary text-uppercase">
            <?php echo $_SESSION['role']; ?>
        </small>
    </div>
    <hr class="text-white">

    <?php if ($_SESSION['role'] == 'admin' && $_SESSION['role'] == 'guru') : ?>
        <a href="../dashboard/" class="nav-link">🏠 Dashboard</a>
    <?php endif; ?>


    <?php if ($_SESSION['role'] == 'admin') : ?>
        <a href="../tambah-kelas/" class="nav-link">🏫 Data Kelas</a>
        <a href="../tambah-siswa/" class="nav-link">👥 Data Siswa</a>
        <a href="../absensi/" class="nav-link">📝 Catat Absensi</a>
        <a href="../pelanggaran/" class="nav-link">⚠️ Pelanggaran Siswa</a>
        <a href="../admin/" class="nav-link">⚙ Data Login Akun</a>
    <?php endif; ?>

    <?php if ($_SESSION['role'] == 'guru' ) : ?>
        <a href="../absensi/" class="nav-link">📝 Catat Absensi</a>
        <a href="../pelanggaran/" class="nav-link">⚠️ Pelanggaran Siswa</a>
    <?php endif; ?>

    <?php if ($_SESSION['role'] == 'wali' ) : ?>
        <a href="../" class="nav-link">⚠️ Pelanggaran Anak</a>
        <a href="absensi-anak/" class="nav-link">📝 Lihat Absensi Anak</a>
    <?php endif; ?>

    <a href="../logout.php" class="text-danger mt-5 fw-bold">🚪 Keluar</a>
</div>
