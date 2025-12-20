<?php
require_once '../../layouts/header.php';
?>

<style>
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        margin-top: 20px;
    }
    
    .dashboard-card {
        background-color: white;
        border-radius: 12px;
        padding: 30px;
        text-align: center;
        border: 1px solid #eee;
        transition: all 0.3s;
        text-decoration: none;
        color: #333;
        display: flex;
        flex-direction: column;
        align-items: center;
        height: 100%;
    }
    
    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        border-color: #ffcdd2;
    }
    
    .card-icon {
        width: 80px;
        height: 80px;
        background-color: #ffebee;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        color: #c62828;
        font-size: 32px;
    }
    
    .card-title {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 10px;
        color: #c62828;
    }
    
    .card-desc {
        color: #666;
        font-size: 15px;
        line-height: 1.5;
    }
    
    .welcome-banner {
        background: linear-gradient(135deg, #c62828, #b71c1c);
        color: white;
        padding: 40px;
        border-radius: 12px;
        margin-bottom: 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .stats-container {
        display: flex;
        gap: 30px;
    }
    
    .stat-box {
        background-color: rgba(255,255,255,0.2);
        padding: 15px 25px;
        border-radius: 10px;
        text-align: center;
    }
    
    .stat-number {
        font-size: 24px;
        font-weight: 800;
    }
    
    .stat-label {
        font-size: 13px;
        opacity: 0.9;
    }
</style>

<!-- <div class="welcome-banner">
    <div>
        <h2 style="font-size: 28px; margin-bottom: 10px;">Selamat Datang di BloodMatch AI</h2>
        <p style="opacity: 0.9;">Sistem cerdas penghubung pendonor dan pasien membutuhkan darah.</p>
    </div>
    <div class="stats-container">
        <div class="stat-box">
            <div class="stat-number">10k+</div>
            <div class="stat-label">Donor Terdaftar</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">24/7</div>
            <div class="stat-label">Layanan Aktif</div>
        </div>
    </div>
</div> -->

<h3 style="margin-bottom: 20px; color: #444; border-left: 5px solid #c62828; padding-left: 15px;">Menu Utama</h3>

<div class="dashboard-grid">
    <a href="req_donorform.php" class="dashboard-card">
        <div class="card-icon">
            <i class="fas fa-search"></i>
        </div>
        <div class="card-title">Cari Donor Darah</div>
        <div class="card-desc">
            Buat permintaan darah baru dan temukan donor yang paling cocok menggunakan algoritma sistem rekomendasi kami.
        </div>
    </a>
    
    <a href="request_list.php" class="dashboard-card">
        <div class="card-icon" style="background-color: #e3f2fd; color: #1565c0;">
            <i class="fas fa-list-alt"></i>
        </div>
        <div class="card-title" style="color: #1565c0;">Daftar Permintaan</div>
        <div class="card-desc">
            Lihat semua histori permintaan darah yang masuk, pantau status, dan cari donor untuk permintaan lama.
        </div>
    </a>
    
    <a href="input_donor.php" class="dashboard-card">
        <div class="card-icon">
            <i class="fas fa-user-plus"></i>
        </div>
        <div class="card-title">Registrasi Donor</div>
        <div class="card-desc">
            Daftarkan diri Anda atau orang lain sebagai pendonor baru. Sistem akan mengecek kelayakan medis secara otomatis.
        </div>
    </a>
    
    <!-- <a href="#" class="dashboard-card" style="opacity: 0.7; cursor: not-allowed;">
        <div class="card-icon" style="background-color: #f5f5f5; color: #999;">
            <i class="fas fa-chart-line"></i>
        </div>
        <div class="card-title" style="color: #777;">Laporan Statistik</div>
        <div class="card-desc">
            Lihat statistik stok darah, distribusi donor, dan riwayat permintaan. (Segera Hadir)
        </div>
    </a> -->
</div>

<?php require_once '../../layouts/footer.php'; ?>
