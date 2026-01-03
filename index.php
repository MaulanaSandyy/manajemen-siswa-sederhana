<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMK WIJAYA - Sistem Informasi Terpadu</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="#">SMK WIJAYA</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link mx-2" href="#beranda">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link mx-2" href="#tentang">Tentang</a></li>
                    <li class="nav-item"><a class="nav-link mx-2" href="#fitur">Fitur</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <section id="beranda" class="hero-section" style="
        background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
        url('assets/gambar/back.png'); 
        background-size: cover; 
        background-position: center; 
        background-attachment: fixed;">
        
        <div class="container text-center">
            <h1 class="display-3 fw-bold mb-4" data-aos="fade-down" style="letter-spacing: 2px;">
                Membangun Masa Depan <br> 
                <span class="text-primary">Dengan Kedisiplinan</span>
            </h1>
            
            <p class="lead mb-5" data-aos="fade-up" data-aos-delay="200" style="font-size: 1.5rem; font-weight: 300;">
            </p>
            
            <div data-aos="zoom-in" data-aos-delay="400">
                <a href="login/" class="btn btn-primary btn-sm px-5 py-3 fw-bold shadow-lg mt-3">
                    LOGIN SISTEM
                </a>
            </div>
        </div>
    </section>

    <section id="tentang" class="section-padding">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <img src="assets/gambar/back.png" class="img-fluid rounded-4 shadow" alt="Sekolah">
                </div>
                <div class="col-lg-6 mt-4 mt-lg-0" data-aos="fade-left">
                    <h2 class="fw-bold mb-4">Mengenal SMK BISA</h2>
                    <p class="text-secondary">Kami adalah institusi pendidikan kejuruan yang berfokus pada pengembangan karakter dan kompetensi siswa di era digital. Dengan fasilitas modern dan tenaga pendidik profesional, kami mencetak lulusan yang siap kerja dan berintegritas tinggi.</p>
                    <p class="text-secondary">Sistem manajemen ini merupakan bentuk komitmen kami dalam transparansi data pendidikan bagi guru, siswa, maupun orang tua.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="fitur" class="section-padding bg-light">
        <div class="container text-center">
            <h2 class="fw-bold mb-5" data-aos="fade-up">Keunggulan Sistem Kami</h2>
            <div class="row">
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card card-feature p-4 h-100">
                        <div class="mb-3 text-primary">
                            <h3>📅</h3>
                        </div>
                        <h5 class="fw-bold">Absensi Digital</h5>
                        <p class="text-muted small">Pencatatan kehadiran harian siswa secara cepat dan akurat oleh guru mata pelajaran atau wali kelas.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card card-feature p-4 h-100">
                        <div class="mb-3 text-danger">
                            <h3>⚠️</h3>
                        </div>
                        <h5 class="fw-bold">Poin Pelanggaran</h5>
                        <p class="text-muted small">Monitoring kedisiplinan siswa melalui sistem poin yang transparan dan dapat dipantau orang tua.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="card card-feature p-4 h-100">
                        <div class="mb-3 text-success">
                            <h3>📱</h3>
                        </div>
                        <h5 class="fw-bold">Akses Multi-User</h5>
                        <p class="text-muted small">Dashboard khusus untuk Admin, Guru, Siswa, hingga Wali Murid untuk melihat perkembangan siswa.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="py-4 bg-dark text-white text-center">
        <div class="container">
            <small>&copy; 2026 SMK BISA.</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Inisialisasi Animasi AOS
        AOS.init({
            once: true // Animasi hanya berjalan sekali saat di-scroll
        });
    </script>
</body>
</html>