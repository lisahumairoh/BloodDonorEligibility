<?php
// Removed session check as per user request
require_once '../layouts/header.php';
?>

<style>
    /* Styling for Search Results Page */
    .main-content {
        padding: 30px;
    }
    
    .results-header {
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .back-btn {
        display: inline-block;
        color: #c62828;
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 20px;
        background-color: white;
        padding: 8px 15px;
        border-radius: 8px;
        border: 1px solid #eee;
        transition: all 0.3s;
    }
    
    .back-btn:hover {
        background-color: #ffebee;
        border-color: #ef5350;
    }
    
    .back-btn i {
        margin-right: 5px;
    }
    
    .donor-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 25px;
        margin-bottom: 30px;
    }
    
    .donor-item {
        border: 1px solid #eee;
        border-radius: 12px;
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
        background: white;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .donor-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    }
    
    .donor-header {
        background-color: #fff8e1; /* Warm background for potential matches */
        padding: 15px 20px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .donor-name {
        font-weight: 700;
        font-size: 17px;
        color: #333;
    }
    
    .donor-blood {
        background-color: #c62828;
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 16px;
        box-shadow: 0 3px 6px rgba(198, 40, 40, 0.3);
    }
    
    .donor-details {
        padding: 20px;
        flex: 1;
    }
    
    .detail-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 12px;
        font-size: 14px;
        color: #555;
    }
    
    .detail-item:last-child {
        margin-bottom: 0;
    }
    
    .detail-item i {
        width: 25px;
        color: #c62828;
        opacity: 0.8;
        margin-top: 3px;
    }

    .load-more-container {
        text-align: center;
        margin-top: 30px;
        margin-bottom: 40px;
    }
    
    .load-more-btn {
        background-color: white; 
        color: #c62828; 
        border: 1px solid #c62828;
        padding: 12px 35px;
        border-radius: 30px;
        cursor: pointer;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.3s;
        box-shadow: 0 3px 6px rgba(0,0,0,0.05);
    }
    
    .load-more-btn:hover {
        background-color: #c62828;
        color: white;
        box-shadow: 0 5px 15px rgba(198, 40, 40, 0.2);
    }
    
    .load-more-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* Animation */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .empty-state {
        grid-column: 1/-1; 
        text-align: center; 
        padding: 60px 20px; 
        color: #888;
        background: white;
        border-radius: 12px;
        border: 1px dashed #ddd;
    }
</style>

<div class="main-content">
    <a href="req_donorform.php" class="back-btn"><i class="fas fa-arrow-left"></i> Kembali ke Pencarian</a>
    
    <div class="results-header">
        <h2 style="color: #c62828; border-left: 4px solid #c62828; padding-left: 12px;">Hasil Pencarian Donor Darah</h2>
        <div id="requestInfoBadge" style="background-color: #e3f2fd; color: #1565c0; padding: 8px 15px; border-radius: 20px; font-size: 14px; font-weight: 500;">
           <!-- Request info will be injected here -->
           <i class="fas fa-filter"></i> Menampilkan hasil pencarian
        </div>
        
        <!-- View Toggle Buttons -->
        <div class="view-toggle">
            <button class="toggle-btn active" data-view="table" title="Tampilan Tabel"><i class="fas fa-table"></i> Tabel</button>
            <button class="toggle-btn" data-view="list" title="Tampilan List"><i class="fas fa-th-large"></i> Kartu</button>
        </div>
    </div>
    
    <style>
        .view-toggle { display: flex; background: white; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; }
        .toggle-btn { border: none; background: none; padding: 8px 15px; cursor: pointer; color: #555; transition: all 0.2s; }
        .toggle-btn:hover { background: #f5f5f5; }
        .toggle-btn.active { background: #c62828; color: white; }
        
        /* Table Styles */
        .donor-table-container { overflow-x: auto; background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .donor-table { width: 100%; border-collapse: collapse; min-width: 600px; }
        .donor-table th { background: #f8f9fa; color: #444; font-weight: 600; padding: 15px; text-align: left; border-bottom: 2px solid #eee; }
        .donor-table td { padding: 15px; border-bottom: 1px solid #eee; color: #333; vertical-align: middle; }
        .donor-table tr:hover { background-color: #fffde7; }
        
        .table-blood-badge { display: inline-block; width: 30px; height: 30px; line-height: 30px; text-align: center; background: #c62828; color: white; border-radius: 50%; font-weight: bold; font-size: 12px; }
        .table-action-btn { color: #1565c0; text-decoration: none; font-weight: 500; font-size: 14px; }
        .table-action-btn:hover { text-decoration: underline; }
    </style>

    <!-- Container for dynamic content -->
    <div id="donorList" class="donor-list">
        <div class="empty-state">
            <i class="fas fa-circle-notch fa-spin" style="font-size: 48px; margin-bottom: 20px; color: #c62828; opacity: 0.5;"></i>
            <p style="font-size: 18px;">Sedang mencari donor potensial...</p>
        </div>
    </div>
    

     
    <div id="errorMessage" style="display: none; background-color: #ffebee; color: #c62828; padding: 15px; border-radius: 8px; margin-top: 20px; align-items: center;">
        <i class="fas fa-exclamation-circle" style="margin-right: 10px; font-size: 20px;"></i>
        <div id="errorText"></div>
    </div>
</div>

<script>
    // Inject request ID from URL PHP parameter
    const REQUEST_ID = "<?php echo isset($_GET['request_id']) ? htmlspecialchars($_GET['request_id']) : ''; ?>";
    
    // Display stored request info from localStorage
    document.addEventListener('DOMContentLoaded', () => {
        const storedData = localStorage.getItem('bloodRequestData');
        if (storedData) {
            try {
                const data = JSON.parse(storedData);
                const badge = document.getElementById('requestInfoBadge');
                if (badge && data.bloodType) {
                    badge.innerHTML = `<i class="fas fa-tint"></i> Golongan ${data.bloodType}${data.rhesus === 'positif' ? '+' : '-'} • ${data.searchRadius} km`;
                }
            } catch (e) { console.log("Error parsing storage"); }
        }
    });
</script>
<script src="../assets/js/donor_search.js"></script>
<script>
    // Initialize results page specific logic
    document.addEventListener('DOMContentLoaded', () => {
        if (REQUEST_ID) {
            // Manually trigger load for the first page
            currentPage = 0; // Reset to 0 so loadMore increments to 1
            currentRequestId = REQUEST_ID;
            loadMoreDonors();
        } else {
            document.getElementById('donorList').innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-search" style="font-size: 48px; margin-bottom: 15px; opacity: 0.3;"></i>
                    <p>Request ID tidak ditemukan.</p>
                    <a href="req_donorform.php" style="color: #c62828; font-weight: bold; margin-top: 10px; display: inline-block;">Buat Permintaan Baru</a>
                </div>
            `;
        }
    });
</script>

<?php require_once '../layouts/footer.php'; ?>
