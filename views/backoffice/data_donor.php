<?php
require_once '../../layouts/header.php';
?>

<div class="main-content">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <h2 style="color: #c62828; border-left: 5px solid #c62828; padding-left: 15px;">Data Donor Darah</h2>
        <a href="input_donor.php" class="add-btn">
            <i class="fas fa-plus"></i> Tambah Donor Baru
        </a>
    </div>

    <style>
        .add-btn {
            background-color: #c62828;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background 0.3s;
        }
        .add-btn:hover { background-color: #b71c1c; }
        
        .donor-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        
        table { width: 100%; border-collapse: collapse; }
        th { 
            background: #f8f9fa; 
            color: #555; 
            font-weight: 700; 
            padding: 15px; 
            text-align: left; 
            border-bottom: 2px solid #eee;
            cursor: pointer;
            transition: background 0.2s;
            user-select: none;
        }
        th:hover { background-color: #eee; }
        th i { margin-left: 5px; opacity: 0.3; }
        
        td { padding: 15px; border-bottom: 1px solid #eee; color: #333; vertical-align: middle; }
        tr:hover { background-color: #fffde7; }
        
        .badge { padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: 700; display: inline-block; }
        .badge-layak { background: #e8f5e9; color: #2e7d32; }
        .badge-tidak { background: #ffebee; color: #c62828; }
        .badge-tangguh { background: #fff3e0; color: #ef6c00; }
        
        .blood-badge {
            background: #c62828;
            color: white;
            font-weight: bold;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 14px;
        }

        .action-btn { 
            color: #1565c0; 
            background: #e3f2fd;
            padding: 6px 12px; 
            border-radius: 6px; 
            text-decoration: none; 
            font-size: 13px; 
            font-weight: 600; 
            transition: all 0.2s;
            display: inline-flex; align-items: center; gap: 5px;
        }
        .action-btn:hover { background: #1565c0; color: white; }
    </style>

    <div class="donor-card">
        <div style="overflow-x: auto;">
            <table id="donorsTable">
                <thead>
                    <tr>
                        <th width="20%">Nama Lengkap</th>
                        <th width="15%">Kota</th>
                        <th width="10%">Gol. Darah</th>
                        <th width="8%">Usia</th>
                        <th width="8%">Berat</th>
                        <th width="8%">HB</th>
                        <th width="15%">Status Kelayakan</th>
                        <th width="16%">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 30px;">
                            <i class="fas fa-spinner fa-spin"></i> Memuat data donor...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Simple Pagination Info (Optional) -->
        <div style="padding: 15px; text-align: right; color: #666; font-size: 13px; border-top: 1px solid #eee;">
            Menampilkan 20 data terbaru
        </div>
    </div>
</div>

<script>
let allDonors = [];

document.addEventListener('DOMContentLoaded', loadDonors);

async function loadDonors() {
    try {
        // Use the existing API for filtering (default pagination is 20)
        const response = await fetch('../../api/add_donor.php?page=1&limit=50', {
            method: 'GET'
        });
        const result = await response.json();
        
        if (result.success) {
            allDonors = result.donors;
            renderTable();
        } else {
             document.getElementById('tableBody').innerHTML = `
                <tr>
                    <td colspan="8" style="text-align: center; padding: 40px; color: #777;">
                        Belum ada data donor.
                    </td>
                </tr>
            `;
        }
    } catch (error) {
        console.error(error);
        document.getElementById('tableBody').innerHTML = `
            <tr>
                <td colspan="8" style="text-align: center; color: #c62828; padding: 20px;">
                    <i class="fas fa-exclamation-circle"></i> Gagal memuat data. <br>
                    <small>Pastikan API endpoint benar.</small>
                </td>
            </tr>
        `;
    }
}

function renderTable() {
    const tbody = document.getElementById('tableBody');
    
    if (allDonors.length > 0) {
        tbody.innerHTML = allDonors.map(donor => {
            // Determine Status Badge
            // 0: Tidak Layak, 1: Layak, 2: Ditangguhkan
            let statusBadge = '';
            let statusText = '';
            
            // Convert status_layak to number if string
            const status = parseInt(donor.status_layak);
            
            if (status === 1) {
                statusBadge = 'badge-layak';
                statusText = 'Layak';
            } else if (status === 2) {
                statusBadge = 'badge-tangguh';
                statusText = 'Ditangguhkan';
            } else {
                statusBadge = 'badge-tidak';
                statusText = 'Tidak Layak';
            }
            
            return `
                <tr>
                    <td>
                        <div style="font-weight: 600;">${donor.name}</div>
                        <div style="font-size: 12px; color: #666;"><i class="fas fa-phone"></i> ${donor.contact_number}</div>
                    </td>
                    <td>${donor.city}</td>
                    <td><span class="blood-badge">${donor.blood_group}</span></td>
                    <td>${donor.usia} thn</td>
                    <td>${donor.berat_badan} kg</td>
                    <td>${donor.hb_level}</td>
                    <td><span class="badge ${statusBadge}">${statusText}</span></td>
                    <td>
                        <a href="https://wa.me/${donor.contact_number.replace(/^0/, '62')}" target="_blank" class="action-btn">
                            <i class="fab fa-whatsapp"></i> Hubungi
                        </a>
                    </td>
                </tr>
            `;
        }).join('');
    } else {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" style="text-align: center; padding: 40px; color: #777;">
                    <i class="fas fa-users-slash" style="font-size: 32px; margin-bottom: 10px; display: block; opacity: 0.3;"></i>
                    Belum ada data donor yang terdaftar.
                </td>
            </tr>
        `;
    }
}
</script>

<?php require_once '../../layouts/footer.php'; ?>
