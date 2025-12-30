<?php
require_once '../../layouts/header.php';
require_once '../../api/db.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
 // 3. Blood type (Eligible Donors)
    $blood_stock_query = "SELECT blood_group, COUNT(*) as count FROM donors WHERE status_layak = 1 GROUP BY blood_group";
    $blood_stock_result = $conn->query($blood_stock_query);
    $blood_stock = [];
    while($row = $blood_stock_result->fetch_assoc()) {
        $blood_stock[$row['blood_group']] = $row['count'];
    }
    
    // Ensure all types are present
    $all_blood_types = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
    foreach($all_blood_types as $bt) {
        if (!isset($blood_stock[$bt])) {
            $blood_stock[$bt] = 0;
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>

<div class="main-content">

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
<!-- Blood Type Widget -->
<!-- Blood Type Widget -->
<div style="background: white; border: 1px solid #ddd; border-radius: 8px; padding: 20px; margin-bottom: 30px;">
    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #c62828; padding-bottom: 10px; margin-bottom: 15px;">
        <h3 style="color: #333; margin: 0; font-size: 18px;">Pendonor Layak</h3>
        <span style="font-weight: bold; background: #c62828; color: white; padding: 5px 10px; border-radius: 5px; font-size: 14px;">Total: <?php echo array_sum($blood_stock); ?></span>
    </div>
    
    <div style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: space-between;">
        <?php foreach($all_blood_types as $bt): ?>
        <div style="background: #fdfdfd; border: 1px solid #eee; border-radius: 6px; padding: 10px; text-align: center; flex: 1; min-width: 60px;">
            <div style="color: #c62828; font-weight: bold; font-size: 16px;">
                <?php echo $bt; ?>
            </div>
            <div style="font-size: 15px; color: #555; margin-top: 2px;">
                <?php echo number_format($blood_stock[$bt]); ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <h2 style="color: #c62828; border-left: 5px solid #c62828; padding-left: 15px;">Data Donor Darah</h2>
        <a href="input_donor.php" class="add-btn">
            <i class="fas fa-plus"></i> Tambah Donor Baru
        </a>
    </div>
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
        <!-- Pagination Controls -->
        <div class="pagination-container" style="display: flex; justify-content: space-between; align-items: center; padding: 15px; border-top: 1px solid #eee;">
            <div id="paginationInfo" style="color: #666; font-size: 14px;">
                Menuat data...
            </div>
            <div style="display: flex; align-items: center; gap: 10px;">
                <button id="prevBtn" class="page-btn" disabled onclick="changePage(-1)">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <span id="pageIndicator" style="font-weight: 600; color: #333; min-width: 60px; text-align: center;">Page 1</span>
                <button id="nextBtn" class="page-btn" disabled onclick="changePage(1)">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
        
        <style>
            .page-btn {
                background: white;
                border: 1px solid #ddd;
                width: 36px;
                height: 36px;
                border-radius: 6px;
                cursor: pointer;
                color: #555;
                font-size: 14px;
                transition: all 0.2s;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .page-btn:hover:not(:disabled) {
                background: #f5f5f5;
                border-color: #ccc;
                color: #c62828;
            }
            .page-btn:disabled {
                background: #f9f9f9;
                color: #ccc;
                cursor: not-allowed;
                border-color: #eee;
            }
        </style>
    </div>
</div>

<script>
let allDonors = [];
let currentPage = 1;
const limit = 20;
let totalPages = 1;

document.addEventListener('DOMContentLoaded', () => loadDonors(1));

async function loadDonors(page) {
    currentPage = page;
    updatePaginationUI(true); // Loading state

    try {
        const response = await fetch(`../../api/add_donor.php?page=${page}&limit=${limit}`, {
            method: 'GET'
        });
        const result = await response.json();
        
        if (result.success) {
            allDonors = result.donors;
            if(result.pagination) totalPages = result.pagination.pages;
            
            renderTable();
            updatePaginationUI(false, result.pagination);
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
            
            const waMessage = `Halo ${donor.name},\nSaat ini kami membutuhkan donor darah golongan ${donor.blood_group}.\nMohon kesediaannya untuk datang ke PMI (Kota Depok).\nBantuan Anda sangat berarti. Terima kasih`;
            const waUrl = `https://wa.me/${donor.contact_number.replace(/^0/, '62')}?text=${encodeURIComponent(waMessage)}`;

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
                        <a href="${waUrl}" target="_blank" class="action-btn">
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


function changePage(delta) {
    if ((delta < 0 && currentPage > 1) || (delta > 0 && currentPage < totalPages)) {
        loadDonors(currentPage + delta);
    }
}

function updatePaginationUI(isLoading, paginationData = null) {
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const pageIndicator = document.getElementById('pageIndicator');
    const infoDiv = document.getElementById('paginationInfo');
    
    if (isLoading) {
        prevBtn.disabled = true;
        nextBtn.disabled = true;
        infoDiv.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memuat...';
        return;
    }
    
    if (paginationData) {
        totalPages = paginationData.pages;
        currentPage = parseInt(paginationData.page);
        
        pageIndicator.textContent = `Page ${currentPage} / ${totalPages}`;
        
        const start = (currentPage - 1) * limit + 1;
        const end = Math.min(currentPage * limit, paginationData.total);
        infoDiv.textContent = `Menampilkan ${start}-${end} dari ${paginationData.total} data`;
        
        prevBtn.disabled = (currentPage <= 1);
        nextBtn.disabled = (currentPage >= totalPages);
    }
}
</script>

<?php require_once '../../layouts/footer.php'; ?>
