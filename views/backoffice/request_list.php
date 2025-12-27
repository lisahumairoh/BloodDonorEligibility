<?php
require_once '../../layouts/header.php';
?>

<div class="main-content">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <h2 style="color: #c62828; border-left: 5px solid #c62828; padding-left: 15px;">Daftar Permintaan Darah</h2>
        <a href="req_donorform.php" class="add-btn">
            <i class="fas fa-plus"></i> Buat Permintaan Baru
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
        
        .request-card {
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
        th.sort-asc i { opacity: 1; transform: rotate(180deg); }
        th.sort-desc i { opacity: 1; }

        td { padding: 15px; border-bottom: 1px solid #eee; color: #333; vertical-align: middle; }
        tr:hover { background-color: #fffde7; }
        
        .badge { padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: 700; display: inline-block; }
        .badge-pending { background: #fff3e0; color: #ef6c00; }
        .badge-processing { background: #e3f2fd; color: #1565c0; }
        .badge-completed { background: #e8f5e9; color: #2e7d32; }
        .badge-cancelled { background: #ffebee; color: #c62828; }
        
        .urgency-badge { padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; text-transform: uppercase; }
        .urgency-tinggi { background: #ffebee; color: #c62828; border: 1px solid #ffcdd2; }
        .urgency-sedang { background: #fff3e0; color: #ef6c00; border: 1px solid #ffe0b2; }
        .urgency-rendah { background: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; }
        
        .action-btn { 
            color: #c62828; 
            border: 1px solid #c62828; 
            padding: 6px 12px; 
            border-radius: 6px; 
            text-decoration: none; 
            font-size: 13px; 
            font-weight: 600; 
            transition: all 0.2s;
            display: inline-flex; align-items: center; gap: 5px;
        }
        .action-btn:hover { background: #c62828; color: white; }
    </style>

    <div class="request-card">
        <div style="overflow-x: auto;">
            <table id="requestsTable">
                <thead>
                    <tr>
                        <th onclick="sortTable('request_id')">ID Request <i class="fas fa-sort"></i></th>
                        <th onclick="sortTable('requester_name')">Pemohon / RS <i class="fas fa-sort"></i></th>
                        <th onclick="sortTable('blood_type')">Gol. Darah <i class="fas fa-sort"></i></th>
                        <th onclick="sortTable('blood_bags')">Jumlah <i class="fas fa-sort"></i></th>
                        <th onclick="sortTable('urgency_level')">Urgensi <i class="fas fa-sort"></i></th>
                        <th onclick="sortTable('status')">Status <i class="fas fa-sort"></i></th>
                        <th onclick="sortTable('request_date')">Tanggal <i class="fas fa-sort"></i></th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 30px;">
                            <i class="fas fa-spinner fa-spin"></i> Memuat data...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
let allRequests = [];
let currentSort = { column: 'request_date', direction: 'desc' };

document.addEventListener('DOMContentLoaded', loadRequests);

async function loadRequests() {
    try {
        const response = await fetch('../../api/get_requests.php');
        const result = await response.json();
        
        if (result.success) {
            allRequests = result.data;
            // Initial Sort
            sortData(currentSort.column, currentSort.direction);
            renderTable();
        } else {
             document.getElementById('tableBody').innerHTML = `
                <tr>
                    <td colspan="8" style="text-align: center; padding: 40px; color: #777;">
                        Belum ada data.
                    </td>
                </tr>
            `;
        }
    } catch (error) {
        console.error(error);
        document.getElementById('tableBody').innerHTML = `
            <tr>
                <td colspan="8" style="text-align: center; color: #c62828; padding: 20px;">
                    <i class="fas fa-exclamation-circle"></i> Gagal memuat data.
                </td>
            </tr>
        `;
    }
}

function sortTable(column) {
    // Toggle direction if clicking same column
    if (currentSort.column === column) {
        currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
    } else {
        currentSort.column = column;
        currentSort.direction = 'asc';
    }
    
    // Update Icons
    updateHeaderIcons();
    
    // Sort Data
    sortData(column, currentSort.direction);
    
    // Render
    renderTable();
}

function updateHeaderIcons() {
    // Reset all headers
    document.querySelectorAll('th').forEach(th => {
        th.classList.remove('sort-asc', 'sort-desc');
        const icon = th.querySelector('i');
        if(icon) icon.className = 'fas fa-sort';
    });
    
    // Set active header
    const headers = document.querySelectorAll('th');
    let activeIndex = -1;
    
    if(currentSort.column === 'request_id') activeIndex = 0;
    if(currentSort.column === 'requester_name') activeIndex = 1;
    if(currentSort.column === 'blood_type') activeIndex = 2;
    if(currentSort.column === 'blood_bags') activeIndex = 3;
    if(currentSort.column === 'urgency_level') activeIndex = 4;
    if(currentSort.column === 'status') activeIndex = 5;
    if(currentSort.column === 'request_date') activeIndex = 6;
    
    if (activeIndex > -1) {
        const th = headers[activeIndex];
        th.classList.add(currentSort.direction === 'asc' ? 'sort-asc' : 'sort-desc');
        th.querySelector('i').className = currentSort.direction === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down';
    }
}

function sortData(column, direction) {
    allRequests.sort((a, b) => {
        let valA = a[column];
        let valB = b[column];
        
        // Handle numbers
        if (column === 'blood_bags') {
            valA = parseInt(valA);
            valB = parseInt(valB);
        }
        
        if (valA < valB) return direction === 'asc' ? -1 : 1;
        if (valA > valB) return direction === 'asc' ? 1 : -1;
        return 0;
    });
}

function renderTable() {
    const tbody = document.getElementById('tableBody');
    
    if (allRequests.length > 0) {
        tbody.innerHTML = allRequests.map(req => {
            // Determine CSS classes
            let statusClass = 'badge-pending';
            if(req.status === 'processing') statusClass = 'badge-processing';
            if(req.status === 'completed') statusClass = 'badge-completed';
            if(req.status === 'cancelled') statusClass = 'badge-cancelled';
            
            let urgencyClass = 'urgency-rendah'; // Default
            if(req.urgency_level === 'tinggi') urgencyClass = 'urgency-tinggi';
            if(req.urgency_level === 'sedang') urgencyClass = 'urgency-sedang';
            
            // Format Date
            const date = new Date(req.request_date).toLocaleString('id-ID', { 
                day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit'
            });
            
            return `
                <tr>
                    <td><small style="font-family: monospace; font-size: 13px;">${req.request_id}</small></td>
                    <td style="font-weight: 600;">${req.requester_name}</td>
                    <td>
                        <span style="font-weight: bold; color: #c62828;">${req.blood_type}</span>
                    </td>
                    <td>${req.blood_bags} Kantong</td>
                    <td><span class="urgency-badge ${urgencyClass}">${req.urgency_level}</span></td>
                    <td><span class="badge ${statusClass}">${req.status}</span></td>
                    <td style="font-size: 13px; color: #666;">${date}</td>
                    <td>
                        <a href="search_results.php?request_id=${req.request_id}" class="action-btn">
                            <i class="fas fa-search-location"></i> Cari Donor
                        </a>
                    </td>
                </tr>
            `;
        }).join('');
    } else {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" style="text-align: center; padding: 40px; color: #777;">
                    <i class="fas fa-inbox" style="font-size: 32px; margin-bottom: 10px; display: block; opacity: 0.3;"></i>
                    Belum ada permintaan darah yang masuk.
                </td>
            </tr>
        `;
    }
}
</script>

<?php require_once '../../layouts/footer.php'; ?>
