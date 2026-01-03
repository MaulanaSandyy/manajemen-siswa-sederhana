<?php
include '../config/koneksi.php';
session_start();

if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'guru' && $_SESSION['role'] != 'admin')) {
    header("location: ../login/");
    exit;
}

$kelas_id = isset($_GET['pilih_kelas']) ? $_GET['pilih_kelas'] : '';

if (isset($_POST['tombol_simpan'])) {
    $siswa_id = $_POST['siswa_id'];
    $tanggal = date('Y-m-d');
    $jenis = $_POST['jenis_pelanggaran'];
    $poin = $_POST['poin'];
    $catatan = $_POST['catatan'];

    mysqli_query($koneksi, "INSERT INTO pelanggaran (siswa_id, tanggal, jenis_pelanggaran, poin, catatan) VALUES ('$siswa_id', '$tanggal', '$jenis', '$poin', '$catatan')");
    header("location: ../pelanggaran/?pilih_kelas=$kelas_id");
}

if (isset($_POST['tombol_hapus_konfirmasi'])) {
    $id = $_POST['id_pelanggaran'];
    $alasan = $_POST['alasan_hapus'];
    $oleh = $_SESSION['nama'];

    $data = mysqli_query($koneksi, "SELECT p.*, s.nama_siswa FROM pelanggaran p JOIN siswa s ON p.siswa_id=s.id WHERE p.id='$id'");
    $d = mysqli_fetch_array($data);
    
    if($d) {
        $nama_siswa = $d['nama_siswa'];
        $pelanggaran = $d['jenis_pelanggaran'];

        mysqli_query($koneksi, "INSERT INTO log_penghapusan (nama_siswa, pelanggaran_dihapus, alasan_hapus, tanggal_hapus, oleh_user) VALUES ('$nama_siswa', '$pelanggaran', '$alasan', NOW(), '$oleh')");

        mysqli_query($koneksi, "DELETE FROM pelanggaran WHERE id='$id'");
    }
    header("location: ../pelanggaran/?pilih_kelas=$kelas_id");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Pelanggaran - SMK WIJAYA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <?php include '../layout/sidebar.php'; ?>

    <div class="main-content">
        <h3 class="fw-bold mb-4">Monitoring Pelanggaran Siswa</h3>

        <div class="row">
            <div class="col-md-4">
                <div class="card card-custom p-4 shadow-sm mb-4">
                    <form method="GET" class="mb-3">
                        <label class="form-label fw-bold">Pilih Kelas</label>
                        <select name="pilih_kelas" class="form-select" onchange="this.form.submit()">
                            <option value="">Pilih Kelas</option>
                            <?php
                            $kelas = mysqli_query($koneksi, "SELECT * FROM kelas");
                            while($k = mysqli_fetch_array($kelas)){
                                $selected = ($kelas_id == $k['id']) ? 'selected' : '';
                                echo "<option value='".$k['id']."' $selected>".$k['nama_kelas']."</option>";
                            }
                            ?>
                        </select>
                    </form>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nama Siswa</label>
                            <select name="siswa_id" class="form-select" required <?php echo ($kelas_id == '') ? 'disabled' : ''; ?>>
                                <option value="">Pilih Siswa</option>
                                <?php
                                if($kelas_id != ''){
                                    $siswa = mysqli_query($koneksi, "SELECT * FROM siswa WHERE kelas_id='$kelas_id' ORDER BY nama_siswa");
                                    while($s = mysqli_fetch_array($siswa)){
                                        echo "<option value='".$s['id']."'>".$s['nama_siswa']."</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <input type="text" name="jenis_pelanggaran" class="form-control mb-2" placeholder="Jenis Pelanggaran" required>
                        <input type="number" name="poin" class="form-control mb-3" placeholder="Poin" required>
                        <textarea name="catatan" class="form-control mb-3" placeholder="Catatan"></textarea>
                        <button type="submit" name="tombol_simpan" class="btn btn-danger w-100 fw-bold" <?php echo ($kelas_id == '') ? 'disabled' : ''; ?>>Simpan</button>
                    </form>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card card-custom p-4 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold">Daftar Poin Siswa</h5>
                        
                        <form method="GET" class="d-flex">
                            <input type="hidden" name="pilih_kelas" value="<?php echo $kelas_id; ?>">
                            <div class="input-group">
                                <input type="text" name="cari" class="form-control" placeholder="Cari siswa..." value="<?php echo isset($_GET['cari']) ? $_GET['cari'] : ''; ?>">
                                <button class="btn btn-secondary" type="submit">Cari</button>
                                <?php if(isset($_GET['cari']) && $_GET['cari'] != ''): ?>
                                    <a href="pelanggaran.php?pilih_kelas=<?php echo $kelas_id; ?>" class="btn btn-outline-danger">Reset</a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Nama Siswa</th>
                                    <th class="text-center">Total Poin</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $cari = isset($_GET['cari']) ? $_GET['cari'] : '';
                                
                                $where = [];
                                if ($kelas_id != '') $where[] = "siswa.kelas_id = '$kelas_id'";
                                if ($cari != '') $where[] = "siswa.nama_siswa LIKE '%$cari%'";
                                
                                $where_sql = "";
                                if (count($where) > 0) $where_sql = "WHERE " . implode(" AND ", $where);

                                $sql = "SELECT siswa.id, siswa.nama_siswa, SUM(pelanggaran.poin) as total FROM siswa LEFT JOIN pelanggaran ON siswa.id = pelanggaran.siswa_id $where_sql GROUP BY siswa.id ORDER BY total DESC";
                                $data = mysqli_query($koneksi, $sql);
                                
                                if(mysqli_num_rows($data) == 0){
                                    echo "<tr><td colspan='4' class='text-center text-muted'>Tidak ada data</td></tr>";
                                }

                                while($row = mysqli_fetch_array($data)){
                                    $poin = $row['total'] ?? 0;

                                    if($poin >= 71) { 
                                        $warna = "bg-dark"; $status = "DO"; 
                                    } elseif($poin >= 40) { 
                                        $warna = "bg-danger"; $status = "Peringatan"; 
                                    } else { 
                                        $warna = "bg-success"; $status = "Aman"; 
                                    }
                                ?>
                                <tr>
                                    <td><strong><?php echo $row['nama_siswa']; ?></strong></td>
                                    <td class="text-center fw-bold"><?php echo $poin; ?></td>
                                    <td class="text-center"><span class="badge <?php echo $warna; ?>"><?php echo $status; ?></span></td>
                                    <td class="text-center">
                                        <?php if($poin > 0): ?>
                                        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalDetail<?php echo $row['id']; ?>">Detail</button>
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

        <div class="row mt-4">
            <div class="col-12">
                <div class="card card-custom p-4 shadow-sm">
                    <h5 class="fw-bold mb-3">Riwayat Penghapusan Poin</h5>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Siswa</th>
                                    <th>Pelanggaran</th>
                                    <th>Alasan</th>
                                    <th>Oleh</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $log = mysqli_query($koneksi, "SELECT * FROM log_penghapusan ORDER BY tanggal_hapus DESC");
                                
                                if(mysqli_num_rows($log) == 0){
                                    echo "<tr><td colspan='5' class='text-center text-muted'>Belum ada riwayat</td></tr>";
                                }

                                while($l = mysqli_fetch_array($log)){
                                ?>
                                <tr>
                                    <td><?php echo date('d/m/Y H:i', strtotime($l['tanggal_hapus'])); ?></td>
                                    <td><?php echo $l['nama_siswa']; ?></td>
                                    <td><?php echo $l['pelanggaran_dihapus']; ?></td>
                                    <td><?php echo $l['alasan_hapus']; ?></td>
                                    <td><?php echo $l['oleh_user']; ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    mysqli_data_seek($data, 0);
    while($row = mysqli_fetch_array($data)){
        $sid = $row['id'];
    ?>
    <div class="modal fade" id="modalDetail<?php echo $sid; ?>">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Detail: <?php echo $row['nama_siswa']; ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Pelanggaran</th>
                        <th>Poin</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $detail = mysqli_query($koneksi, "SELECT * FROM pelanggaran WHERE siswa_id='$sid' ORDER BY id DESC");
                    while($d = mysqli_fetch_array($detail)){
                    ?>
                    <tr>
                        <td><?php echo $d['tanggal']; ?></td>
                        <td><?php echo $d['jenis_pelanggaran']; ?></td>
                        <td><span class="badge bg-danger"><?php echo $d['poin']; ?></span></td>
                        <td>
                            <form method="POST" onsubmit="return confirm('Hapus poin ini?')">
                                <input type="hidden" name="id_pelanggaran" value="<?php echo $d['id']; ?>">
                                <div class="input-group input-group-sm">
                                    <input type="text" name="alasan_hapus" class="form-control" placeholder="Alasan" required>
                                    <button type="submit" name="tombol_hapus_konfirmasi" class="btn btn-danger">Hapus</button>
                                </div>
                            </form>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <?php } ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>