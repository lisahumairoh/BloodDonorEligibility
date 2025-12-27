<?php
require_once '../../layouts/header.php';
require_once '../../api/db.php';

// Fetch Statistics
try {
    $db = new Database();
    $conn = $db->getConnection();
    
    // 1. Total Donors
    $query_total = "SELECT COUNT(*) as total FROM donors";
    $result_total = $conn->query($query_total);
    $total_donors = $result_total->fetch_assoc()['total'];
    
    // 2. Breakdown Prediction Status
    // Status 1: Layak, 0: Tidak Layak, 2: Ditangguhkan
    $query_status = "
        SELECT 
            SUM(CASE WHEN status_layak = 1 THEN 1 ELSE 0 END) as layak,
            SUM(CASE WHEN status_layak = 0 THEN 1 ELSE 0 END) as tidak_layak,
            SUM(CASE WHEN status_layak = 2 THEN 1 ELSE 0 END) as ditangguhkan
        FROM donors
    ";
    $result_status = $conn->query($query_status);
    $status_counts = $result_status->fetch_assoc();
    
    $layak = $status_counts['layak'] ?? 0;
    $tidak_layak = $status_counts['tidak_layak'] ?? 0;
    $ditangguhkan = $status_counts['ditangguhkan'] ?? 0;
    $layak = $status_counts['layak'] ?? 0;
    $tidak_layak = $status_counts['tidak_layak'] ?? 0;
    $ditangguhkan = $status_counts['ditangguhkan'] ?? 0;
    
    // 3. Requests by Blood Type
    $query_requests = "
        SELECT 
            blood_type, 
            COUNT(*) as total 
        FROM blood_requests 
        GROUP BY blood_type
    ";
    $result_requests = $conn->query($query_requests);
    
    $request_labels = [];
    $request_data = [];
    
    while($row = $result_requests->fetch_assoc()) {
        $request_labels[] = $row['blood_type'];
        $request_data[] = $row['total'];
    }
   
    
    $conn->close();
    
} catch (Exception $e) {
    $total_donors = 0;
    $layak = 0;
    $tidak_layak = 0;
    $ditangguhkan = 0;
    // Log error silently or show alert
}
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
        border-color: #bbdefb;
    }
    
    .card-icon {
        width: 80px;
        height: 80px;
        background-color: #e3f2fd;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        color: #1565c0;
        font-size: 32px;
    }
    
    .card-title {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 10px;
        color: #1565c0;
    }
    
    .card-desc {
        color: #666;
        font-size: 15px;
        line-height: 1.5;
    }
    
    .welcome-banner {
        background-color: #fcfcfcff;
        color: #444;
        padding: 40px;
        border-radius: 12px;
        margin-bottom: 40px;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 20px;
        width: 100%;
        margin-top: 10px;
    }
    
    .stat-box {
        background-color: rgba(29, 29, 29, 0.2);
        padding: 20px;
        padding-left: 10px;
        padding-right: 10px;
        color: white;
        border-radius: 10px;
        text-align: center;
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255,255,255,0.2);
    }
    
    .stat-number {
        font-size: 32px;
        font-weight: 800;
        margin-bottom: 5px;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
    }
    
    .stat-label {
        font-size: 14px;
        opacity: 0.9;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .badge-prediction {
        font-size: 12px;
        padding: 4px 8px;
        border-radius: 12px;
        margin-top: 5px;
        display: inline-block;
        font-weight: 600;
    }
    
    @media (min-width: 768px) {
        .welcome-banner {
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
        }
        .stats-container {
            width: 60%;
        }
    }
</style>

<div class="welcome-banner">
    <div style="flex: 1;">
        <h2 style="font-size: 28px; margin-bottom: 10px;">Dashboard Admin</h2>
        <p style="opacity: 0.9; max-width: 400px;">Ringkasan data donor darah dan status kelayakan berdasarkan prediksi AI.</p>
    </div>

    <div class="stats-container">
        <div class="stat-box">
            <div class="stat-number"><?php echo number_format($total_donors); ?></div>
            <div class="stat-label">Total Donor</div>
        </div>
        
        <div class="stat-box" style="background-color: rgba(60, 180, 56, 0.7);">
            <div class="stat-number"><?php echo number_format($layak); ?></div>
            <div class="stat-label"><i class="fas fa-check-circle"></i> Layak</div>
        </div>
        
        <div class="stat-box" style="background-color: rgba(255, 153, 0, 0.77);">
            <div class="stat-number"><?php echo number_format($ditangguhkan); ?></div>
            <div class="stat-label"><i class="fas fa-exclamation-triangle"></i> Ditangguhkan</div>
        </div>
        
        <div class="stat-box" style="background-color: rgba(218, 93, 84, 1);">
            <div class="stat-number"><?php echo number_format($tidak_layak); ?></div>
            <div class="stat-label"><i class="fas fa-times-circle"></i> Tidak Layak</div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 30px; margin-bottom: 40px;">
    <!-- Prediction Stats Chart -->
    <div style="background-color: white; border-radius: 12px; padding: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border: 1px solid #eee;">
        <h3 style="margin-bottom: 25px; color: #444; border-left: 5px solid #1565c0; padding-left: 15px;">Statistik Prediksi Kelayakan</h3>
        <div style="position: relative; height: 300px; width: 100%; display: flex; justify-content: center;">
            <canvas id="predictionChart"></canvas>
        </div>
    </div>

    <!-- Request Stats Chart -->
    <div style="background-color: white; border-radius: 12px; padding: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border: 1px solid #eee;">
        <h3 style="margin-bottom: 25px; color: #444; border-left: 5px solid #c62828; padding-left: 15px;">Permintaan Darah per Golongan</h3>
        <div style="position: relative; height: 300px; width: 100%; display: flex; justify-content: center;">
            <canvas id="requestChart"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<h3 style="margin-bottom: 20px; color: #444; border-left: 5px solid #1565c0; padding-left: 15px;">Menu Utama</h3>

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
        <div class="card-icon" style="background-color: #e0f2f1; color: #00897b;">
            <i class="fas fa-list-alt"></i>
        </div>
        <div class="card-title" style="color: #00897b;">Daftar Permintaan</div>
        <div class="card-desc">
            Lihat semua histori permintaan darah yang masuk, pantau status, dan cari donor untuk permintaan lama.
        </div>
    </a>
    
    <a href="input_donor.php" class="dashboard-card">
        <div class="card-icon" style="background-color: #f3e5f5; color: #b61f1fff;">
            <i class="fas fa-user-plus"></i>
        </div>
        <div class="card-title" style="color: #b61f1fff;">Registrasi Donor</div>
        <div class="card-desc">
            Daftarkan diri Anda atau orang lain sebagai pendonor baru. Sistem akan mengecek kelayakan medis secara otomatis.
        </div>
    </a>

    <a href="data_donor.php" class="dashboard-card">
        <div class="card-icon" style="background-color: #e8f5e9; color: #2e7d32;">
            <i class="fas fa-database"></i>
        </div>
        <div class="card-title" style="color: #2e7d32;">Data Donor</div>
        <div class="card-desc">
            Lihat, cari, dan kelola data lengkap semua pendonor terdaftar beserta status kelayakan dan info kontaknya.
        </div>
    </a>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('predictionChart').getContext('2d');
        
        // Data from PHP
        const layak = <?php echo $layak; ?>;
        const ditangguhkan = <?php echo $ditangguhkan; ?>;
        const tidakLayak = <?php echo $tidak_layak; ?>;
        
        const predictionChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Layak', 'Ditangguhkan', 'Tidak Layak'],
                datasets: [{
                    label: 'Jumlah Donor',
                    data: [layak, ditangguhkan, tidakLayak],
                    backgroundColor: [
                        'rgba(60, 180, 56, 0.8)',   // Layak - Green
                        'rgba(255, 153, 0, 0.8)',   // Ditangguhkan - Orange
                        'rgba(218, 93, 84, 0.8)'    // Tidak Layak - Red
                    ],
                    borderColor: [
                        'rgba(60, 180, 56, 1)',
                        'rgba(255, 153, 0, 1)',
                        'rgba(218, 93, 84, 1)'
                    ],
                    borderWidth: 1,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            font: {
                                size: 14,
                                family: "'Segoe UI', sans-serif"
                            },
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14
                        },
                        bodyFont: {
                            size: 14
                        },
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.raw || 0;
                                let total = context.chart._metasets[context.datasetIndex].total;
                                let percentage = Math.round((value / total) * 100) + '%';
                                return label + ': ' + value + ' (' + percentage + ')';
                            }
                        }
                    }
                },
                layout: {
                    padding: 20
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        });
    });

    // Request Chart
    document.addEventListener('DOMContentLoaded', function() {
        const ctxRequests = document.getElementById('requestChart').getContext('2d');
        
        const requestChart = new Chart(ctxRequests, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($request_labels); ?>,
                datasets: [{
                    label: 'Jumlah Permintaan',
                    data: <?php echo json_encode($request_data); ?>,
                    backgroundColor: function(context) {
                        const label = context.chart.data.labels[context.dataIndex];
                        if (label.includes('A') && !label.includes('B')) return 'rgba(239, 83, 80, 0.7)'; 
                        if (label.includes('B') && !label.includes('A')) return 'rgba(66, 165, 245, 0.7)';
                        if (label.includes('AB')) return 'rgba(76, 175, 80, 0.7)'; 
                        if (label.includes('O')) return 'rgba(255, 167, 38, 0.7)'; 
                        return 'rgba(158, 158, 158, 0.7)'; // Grey for others
                    },
                    borderColor: function(context) {
                        const label = context.chart.data.labels[context.dataIndex];
                        if (label.includes('A') && !label.includes('B')) return 'rgba(239, 83, 80, 1)';
                        if (label.includes('B') && !label.includes('A')) return 'rgba(66, 165, 245, 1)';
                        if (label.includes('AB')) return 'rgba(76, 175, 80, 1)';
                        if (label.includes('O')) return 'rgba(255, 167, 38, 1)';
                        return 'rgba(158, 158, 158, 1)';
                    },
                    borderWidth: 1,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: { size: 14 },
                        bodyFont: { size: 14 }
                    }
                }
            }
        });
    });
</script>
<?php require_once '../../layouts/footer.php'; ?>
