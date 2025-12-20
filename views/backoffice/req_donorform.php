<?php
// Session check removed
require_once '../../layouts/header.php';
?>

<style>
    .form-section {
        padding: 30px;
        background-color: white;
        border-radius: 10px;
        max-width: 800px;
        margin: 0 auto;
    }
    
    .section-title {
        color: #c62828;
        font-size: 22px;
        margin-bottom: 25px;
        padding-bottom: 10px;
        border-bottom: 2px solid #ffebee;
    }
    
    .form-group {
        margin-bottom: 25px;
    }
    
    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #555;
    }
    
    .form-row {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }
    
    .form-col {
        flex: 1;
        min-width: 200px;
    }
    
    .input-group {
        margin-bottom: 20px;
    }
    
    .input-field, .select-field {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 16px;
        transition: border-color 0.3s;
    }
    
    .input-field:focus, .select-field:focus {
        border-color: #c62828;
        outline: none;
        box-shadow: 0 0 0 3px rgba(198, 40, 40, 0.1);
    }
    
    .radio-group {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-top: 8px;
    }
    
    .radio-option {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        padding: 10px 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        transition: all 0.2s;
    }
    
    .radio-option:hover {
        background-color: #f9f9f9;
    }
    
    .radio-button {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 2px solid #bbb;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }
    
    .radio-button.selected {
        border-color: #c62828;
    }
    
    .radio-button.selected::after {
        content: '';
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: #c62828;
        position: absolute;
    }
    
    .radius-selection {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-top: 8px;
    }
        
    .radius-slider {
        flex: 1;
        height: 6px;
        background-color: #e0e0e0;
        border-radius: 3px;
        position: relative;
        cursor: pointer;
    }
    
    .slider-thumb {
        position: absolute;
        width: 24px;
        height: 24px;
        background-color: #c62828;
        border-radius: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        left: 50%;
        cursor: grab;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    
    .radius-value {
        font-weight: 700;
        color: #c62828;
        min-width: 60px;
        text-align: right;
    }
    
    .search-button {
        background-color: #c62828;
        color: white;
        border: none;
        padding: 14px 30px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        transition: background-color 0.3s;
        margin-top: 20px;
    }
    
    .search-button:hover {
        background-color: #b71c1c;
    }
    
    .search-button:disabled {
        background-color: #ef9a9a;
        cursor: not-allowed;
    }
    
    .form-info {
        background-color: #e8f5e9;
        border: 1px solid #c8e6c9;
        border-radius: 8px;
        padding: 15px;
        margin-top: 25px;
        color: #2e7d32;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    @media (max-width: 768px) {
        .form-row {
            flex-direction: column;
            gap: 0;
        }
    }
</style>

<div class="form-section">
    <h2 class="section-title"><i class="fas fa-search"></i> Cari Donor</h2>
    
    <form id="bloodRequestForm">
        <div class="form-group">
            <div class="form-row">
                <div class="form-col">
                    <div class="input-group">
                        <label for="hospitalName">Nama Pencari / Rumah Sakit</label>
                        <input type="text" id="hospitalName" placeholder="Nama Pencari / Rumah Sakit" class="input-field" required>
                    </div>
                </div>
                
                <div class="form-col">
                    <div class="input-group">
                        <label for="bloodBags">Jumlah Kantong Darah</label>
                        <input type="number" id="bloodBags" class="input-field" min="1" max="10" value="1" required>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <div class="form-row">
                <div class="form-col">
                    <div class="input-group">
                        <label for="bloodType">Golongan Darah</label>
                        <select id="bloodType" class="select-field" required>
                            <option value="">Pilih golongan darah</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="AB">AB</option>
                            <option value="O" selected>O</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-col">
                    <div class="input-group">
                        <label for="rhesus">Rhesus</label>
                        <div class="radio-group">
                            <div class="radio-option" data-value="positif">
                                <div class="radio-button selected"></div>
                                <span>Positif (+)</span>
                            </div>
                            <div class="radio-option" data-value="negatif">
                                <div class="radio-button"></div>
                                <span>Negatif (-)</span>
                            </div>
                        </div>
                        <input type="hidden" id="rhesus" value="positif">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <div class="input-group">
                <label>Tingkat Urgensi</label>
                <div class="radio-group">
                    <div class="radio-option" data-value="rendah">
                        <div class="radio-button"></div>
                        <span>Rendah</span>
                    </div>
                    <div class="radio-option" data-value="sedang">
                        <div class="radio-button selected"></div>
                        <span>Sedang</span>
                    </div>
                    <div class="radio-option" data-value="tinggi">
                        <div class="radio-button"></div>
                        <span>Tinggi</span>
                    </div>
                </div>
                <input type="hidden" id="urgencyLevel" value="sedang">
            </div>
        </div>
        
        <div class="form-group">
            <div class="input-group">
                <label>Radius Pencarian (km)</label>
                <div class="radius-selection">
                    <div class="radius-slider">
                        <div class="slider-thumb"></div>
                    </div>
                    <div class="radius-value">15 km</div>
                </div>
                <input type="hidden" id="searchRadius" value="15">
            </div>
        </div>
        
        <button type="submit" class="search-button" id="searchButton">
            <i class="fas fa-search"></i>
            Cari Donor Terdekat
        </button>
        
        <div class="form-info">
            <i class="fas fa-info-circle"></i>
            <div>Form ini akan mengirim permintaan darah ke sistem untuk mencari donor yang sesuai.</div>
        </div>
    </form>
</div>

<!-- Use external JS logic -->
<script src="../../assets/js/donor_search.js"></script>

<?php require_once '../../layouts/footer.php'; ?>