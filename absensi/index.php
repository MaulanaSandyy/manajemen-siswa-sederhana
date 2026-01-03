<?php
include '../config/koneksi.php';
session_start();

if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'guru' && $_SESSION['role'] != 'admin')) {
    header("location: ../login/");
    exit;
}

$hari_ini = date('Y-m-d');
$id_kelas = isset($_GET['pilih_kelas']) ? $_GET['pilih_kelas'] : '';

if (isset($_POST['tombol_simpan'])) {
    foreach ($_POST['id_siswa'] as $id_siswa) {
        $status = $_POST['status'][$id_siswa];
        
        $cek = mysqli_query($koneksi, "SELECT * FROM absensi WHERE siswa_id='$id_siswa' AND tanggal='$hari_ini'");
        
        if(mysqli_num_rows($cek) > 0) {
            mysqli_query($koneksi, "UPDATE absensi SET status='$status' WHERE siswa_id='$id_siswa' AND tanggal='$hari_ini'");
        } else {
            mysqli_query($koneksi, "INSERT INTO absensi (siswa_id, tanggal, status) VALUES ('$id_siswa', '$hari_ini', '$status')");
        }
    }
    header("location: ../absensi/?pilih_kelas=$id_kelas");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Absensi Siswa - SMK WIJAYA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    
<?php include '../layout/sidebar.php'; ?>

<div class="main-content">
    <h3 class="fw-bold mb-4">Absensi Siswa</h3>
    
    <form method="GET" class="mb-3">
        <select name="pilih_kelas" class="form-select w-50" onchange="this.form.submit()">
            <option value="">Pilih Kelas</option>
            <?php
            $kelas = mysqli_query($koneksi, "SELECT * FROM kelas");
            while($k = mysqli_fetch_array($kelas)){
                $selected = ($id_kelas == $k['id']) ? 'selected' : '';
                echo "<option value='".$k['id']."' $selected>".$k['nama_kelas']."</option>";
            }
            ?>
        </select>
    </form>

    <?php if ($id_kelas != ''): ?>
    
    <div class="card card-custom p-3 mb-3 bg-light">
        <div class="row align-items-center">
            <div class="col-md-4">
                <p class="mb-0 fw-bold">Set status untuk siswa yang dipilih:</p>
            </div>
            <div class="col-md-8 text-end">
                <button type="button" onclick="setStatus('H')" class="btn btn-success btn-sm">Hadir</button>
                <button type="button" onclick="setStatus('S')" class="btn btn-primary btn-sm">Sakit</button>
                <button type="button" onclick="setStatus('I')" class="btn btn-info btn-sm">Izin</button>
                <button type="button" onclick="setStatus('A')" class="btn btn-danger btn-sm">Alpha</button>
            </div>
        </div>
    </div>

    <form method="POST">
        <div class="card card-custom p-4 shadow-sm">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th width="50"><input type="checkbox" onchange="pilihSemua(this)"></th>
                        <th>Nama Siswa</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $siswa = mysqli_query($koneksi, "SELECT * FROM siswa WHERE kelas_id='$id_kelas' ORDER BY nama_siswa");
                    while ($row = mysqli_fetch_array($siswa)) {
                        $id_siswa = $row['id'];
                        $cek = mysqli_query($koneksi, "SELECT status FROM absensi WHERE siswa_id='$id_siswa' AND tanggal='$hari_ini'");
                        $data = mysqli_fetch_array($cek);
                        $status = isset($data['status']) ? $data['status'] : 'Hadir';
                    ?>
                    <tr>
                        <td><input type="checkbox" name="pilih_siswa[]" value="<?php echo $id_siswa; ?>"></td>
                        <td><strong><?php echo $row['nama_siswa']; ?></strong></td>
                        <td class="text-center">
                            <input type="hidden" name="id_siswa[]" value="<?php echo $id_siswa; ?>">
                            <div class="btn-group">
                                <input type="radio" class="btn-check" name="status[<?php echo $id_siswa; ?>]" id="H<?php echo $id_siswa; ?>" value="Hadir" <?php echo ($status == 'Hadir') ? 'checked' : ''; ?>>
                                <label class="btn btn-outline-success btn-sm" for="H<?php echo $id_siswa; ?>">H</label>

                                <input type="radio" class="btn-check" name="status[<?php echo $id_siswa; ?>]" id="S<?php echo $id_siswa; ?>" value="Sakit" <?php echo ($status == 'Sakit') ? 'checked' : ''; ?>>
                                <label class="btn btn-outline-primary btn-sm" for="S<?php echo $id_siswa; ?>">S</label>

                                <input type="radio" class="btn-check" name="status[<?php echo $id_siswa; ?>]" id="I<?php echo $id_siswa; ?>" value="Izin" <?php echo ($status == 'Izin') ? 'checked' : ''; ?>>
                                <label class="btn btn-outline-info btn-sm" for="I<?php echo $id_siswa; ?>">I</label>

                                <input type="radio" class="btn-check" name="status[<?php echo $id_siswa; ?>]" id="A<?php echo $id_siswa; ?>" value="Alpha" <?php echo ($status == 'Alpha') ? 'checked' : ''; ?>>
                                <label class="btn btn-outline-danger btn-sm" for="A<?php echo $id_siswa; ?>">A</label>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

            <button type="submit" name="tombol_simpan" class="btn btn-primary w-100 py-3 fw-bold mt-3">Simpan Absensi</button>
        </div>
    </form>
    <?php endif; ?>
</div>

<script>
function pilihSemua(ele) {
    var semua = document.querySelectorAll('input[name="pilih_siswa[]"]');
    for (var i = 0; i < semua.length; i++) {
        semua[i].checked = ele.checked;
    }
}

function setStatus(status) {
    var dipilih = document.querySelectorAll('input[name="pilih_siswa[]"]:checked');
    for (var i = 0; i < dipilih.length; i++) {
        var id = dipilih[i].value;
        var radio = document.getElementById(status + id);
        if (radio) radio.checked = true;
    }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>