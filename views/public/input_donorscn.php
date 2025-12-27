<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Rekomendasi Donor Darah - Pendaftaran</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #f8f9fa; color: #333; line-height: 1.6; padding: 20px; }
        
        /* Layout & Container */
        .container { max-width: 1200px; margin: 0 auto; background-color: white; border-radius: 15px; box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08); overflow: hidden; }
        header { background-color: #c62828; color: white; padding: 25px 30px; border-bottom: 5px solid #b71c1c; }
        h1 { font-size: 28px; font-weight: 700; margin-bottom: 8px; }
        .subtitle { font-size: 16px; opacity: 0.9; }
        
        .main-content { display: flex; flex-wrap: wrap; }
        .request-section { flex: 1.2; min-width: 400px; padding: 30px; border-right: 1px solid #eee; background-color: #f9f9f9; }
        .recommendation-section { flex: 0.8; min-width: 300px; padding: 30px; background-color: white; }
        
        .section-title { color: #c62828; font-size: 22px; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #ffebee; }
        
        /* Form Elements */
        .form-group { margin-bottom: 25px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: #555; }
        .required::after { content: " *"; color: #c62828; }
        
        .form-row { display: flex; flex-wrap: wrap; gap: 20px; }
        .form-col { flex: 1; min-width: 200px; }
        
        .input-group { margin-bottom: 20px; }
        .input-group label { margin-bottom: 8px; }
        
        .input-field, .select-field { width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 16px; transition: border-color 0.3s; background-color: white; }
        .input-field:focus, .select-field:focus { border-color: #c62828; outline: none; box-shadow: 0 0 0 3px rgba(198, 40, 40, 0.1); }
        
        /* Radio Buttons */
        .radio-group { display: flex; flex-wrap: wrap; gap: 20px; margin-top: 8px; }
        .radio-option { display: flex; align-items: center; gap: 8px; cursor: pointer; }
        .radio-button { width: 20px; height: 20px; border-radius: 50%; border: 2px solid #c62828; display: flex; align-items: center; justify-content: center; position: relative; }
        .radio-button.selected::after { content: ''; width: 10px; height: 10px; border-radius: 50%; background-color: #c62828; position: absolute; }
        
        /* Action Button */
        .search-button { background-color: #c62828; color: white; border: none; padding: 14px 30px; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px; width: 100%; transition: background-color 0.3s; margin-top: 20px; }
        .search-button:hover { background-color: #b71c1c; }
        
        /* Form Info */
        .form-info { background-color: #e8f5e9; border: 1px solid #c8e6c9; border-radius: 8px; padding: 15px; margin-top: 25px; color: #2e7d32; font-size: 14px; display: flex; gap: 10px; align-items: center; }
        
        /* Result Box */
        .result-card { margin-top: 20px; padding: 25px; border-radius: 12px; opacity: 0; transform: translateY(10px); transition: all 0.5s ease; }
        .result-card.active { opacity: 1; transform: translateY(0); }
        .result-card.success { background-color: #e8f5e9; border-left: 5px solid #2e7d32; }
        .result-card.warning { background-color: #fff8e1; border-left: 5px solid #ffca28; }
        .result-card.error { background-color: #ffebee; border-left: 5px solid #c62828; }
        
        .result-header { font-size: 20px; font-weight: 700; margin-bottom: 15px; display: flex; align-items: center; gap: 10px; }
        .result-detail { margin-bottom: 10px; font-size: 15px; }
        .result-detail strong { color: #555; }
        
        .eligibility-badge { display: inline-block; padding: 5px 12px; border-radius: 20px; font-weight: 700; font-size: 14px; color: white; margin-left: 5px; }
        .badge-success { background-color: #2e7d32; }
        .badge-danger { background-color: #c62828; }
        
        .info-card { background-color: #e3f2fd; border-left: 5px solid #1976d2; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        .info-title { font-weight: 700; color: #1565c0; margin-bottom: 10px; font-size: 16px; }
        .info-text { font-size: 14px; color: #555; margin-bottom: 8px; }
        
        @media (max-width: 900px) {
            .main-content { flex-direction: column; }
            .request-section { border-right: none; border-bottom: 1px solid #eee; }
        }

        /* Tambahan CSS untuk bagian kontak */
    .contact-card {
    background: linear-gradient(135deg, #e8f5e9 0%, #f1f8e9 100%);
    border-left: 4px solid #2e7d32;
    padding: 20px;
    border-radius: 10px;
    margin: 15px 0;
    box-shadow: 0 3px 10px rgba(46, 125, 50, 0.1);
}

    .contact-header {
    font-weight: 700;
    color: #2e7d32;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 16px;
}

    .contact-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
    margin-bottom: 15px;
}

    .contact-item {
    background: white;
    padding: 12px;
    border-radius: 6px;
    border: 1px solid #c8e6c9;
}

    .contact-label {
    font-weight: 600;
    color: #555;
    font-size: 13px;
    margin-bottom: 5px;
    display: block;
}

    .contact-value {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
}

    .action-buttons {
    display: flex;
    gap: 10px;
    margin-top: 15px;
}

    .btn-whatsapp {
    background: #25D366;
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-weight: 600;
    flex: 1;
    transition: background 0.3s;
}

    .btn-whatsapp:hover {
    background: #1da851;
}

    .btn-map {
    background: #4285F4;
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-weight: 600;
    flex: 1;
    transition: background 0.3s;
}

    .btn-map:hover {
    background: #3367d6;
}

    .btn-copy {
    background: #757575;
    color: white;
    border: none;
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 11px;
    cursor: pointer;
    margin-left: auto;
    transition: background 0.3s;
}

    .btn-copy:hover {
    background: #616161;
}
    /* Layout & Container */
    .register-container { 
        display: flex; 
        flex-wrap: wrap; 
        gap: 30px;
    }
    
    .request-section { 
        flex: 1.2; 
        min-width: 400px; 
        padding: 30px; 
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .recommendation-section { 
        flex: 0.8; 
        min-width: 300px; 
        padding: 30px; 
        background-color: white;
        border-radius: 10px;
        height: fit-content;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .section-title { 
        color: #c62828; 
        font-size: 22px; 
        margin-bottom: 20px; 
        padding-bottom: 10px; 
        border-bottom: 2px solid #ffebee; 
    }
    
    /* Form Elements */
    .form-group { margin-bottom: 25px; }
    label { display: block; margin-bottom: 8px; font-weight: 600; color: #555; }
    .required::after { content: " *"; color: #c62828; }
    
    .form-row { display: flex; flex-wrap: wrap; gap: 20px; }
    .form-col { flex: 1; min-width: 200px; }
    
    .input-group { margin-bottom: 20px; }
    
    .input-field, .select-field { 
        width: 100%; 
        padding: 12px 15px; 
        border: 1px solid #ddd; 
        border-radius: 8px; 
        font-size: 16px; 
        transition: border-color 0.3s; 
        background-color: white; 
    }
    .input-field:focus, .select-field:focus { 
        border-color: #c62828; 
        outline: none; 
        box-shadow: 0 0 0 3px rgba(198, 40, 40, 0.1); 
    }
    
    /* Radio Buttons */
    .radio-group { display: flex; flex-wrap: wrap; gap: 20px; margin-top: 8px; }
    .radio-option { display: flex; align-items: center; gap: 8px; cursor: pointer; }
    .radio-button { 
        width: 20px; 
        height: 20px; 
        border-radius: 50%; 
        border: 2px solid #c62828; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        position: relative; 
    }
    .radio-button.selected::after { 
        content: ''; 
        width: 10px; 
        height: 10px; 
        border-radius: 50%; 
        background-color: #c62828; 
        position: absolute; 
    }
    
    /* Action Button */
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
    .search-button:hover { background-color: #b71c1c; }
    
    /* Form Info */
    .form-info { 
        background-color: #e8f5e9; 
        border: 1px solid #c8e6c9; 
        border-radius: 8px; 
        padding: 15px; 
        margin-top: 25px; 
        color: #2e7d32; 
        font-size: 14px; 
        display: flex; 
        gap: 10px; 
        align-items: center; 
    }
    
    /* Result Box */
    .result-card { margin-top: 20px; padding: 25px; border-radius: 12px; opacity: 0; transform: translateY(10px); transition: all 0.5s ease; }
    .result-card.active { opacity: 1; transform: translateY(0); }
    .result-card.success { background-color: #e8f5e9; border-left: 5px solid #2e7d32; }
    .result-card.warning { background-color: #fff8e1; border-left: 5px solid #ffca28; }
    .result-card.error { background-color: #ffebee; border-left: 5px solid #c62828; }
    
    .result-header { font-size: 20px; font-weight: 700; margin-bottom: 15px; display: flex; align-items: center; gap: 10px; }
    .result-detail { margin-bottom: 10px; font-size: 15px; }
    .result-detail strong { color: #555; }
    
    .info-card { background-color: #e3f2fd; border-left: 5px solid #1976d2; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
    .info-title { font-weight: 700; color: #1565c0; margin-bottom: 10px; font-size: 16px; }
    .info-text { font-size: 14px; color: #555; margin-bottom: 8px; }
    
    @media (max-width: 900px) {
        .register-container { flex-direction: column; }
    }
</style>

<div class="register-container">
    <!-- Left Column: Form -->
    <div class="request-section">
        <h2 class="section-title"><i class="fas fa-user-plus"></i> Form Pendaftaran Donor</h2>
        
        <form id="donorForm">
            <!-- Data Pribadi -->
            <h3 style="margin-bottom: 15px; font-size: 18px; color: #444;">Data Pribadi</h3>
            <div class="form-row">
                <div class="form-col">
                    <div class="input-group">
                        <label class="required" for="name">Nama Lengkap</label>
                        <input type="text" id="name" class="input-field" placeholder="Nama sesuai KTP" required>
                    </div>
                </div>
                <div class="form-col">
                        <div class="input-group">
                        <label class="required" for="city">Kota Domisili</label>
                        <input type="text" id="city" class="input-field" value="Jakarta" required>
                    </div>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-col">
                    <div class="input-group">
                        <label class="required" for="email">Email</label>
                        <input type="email" id="email" class="input-field" placeholder="email@contoh.com" required>
                    </div>
                </div>
                <div class="form-col">
                    <div class="input-group">
                        <label class="required" for="contact_number">No. Telepon / WhatsApp</label>
                        <input type="tel" id="contact_number" class="input-field" placeholder="08xxxxxxxxxx" required>
                    </div>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-col">
                    <div class="input-group">
                        <label class="required" for="gender">Jenis Kelamin</label>
                        <select id="gender" class="select-field" required>
                            <option value="">Pilih</option>
                            <option value="L">Laki - Laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                </div>
                    <div class="form-col">
                        <div class="input-group">
                        <label for="jarak_ke_rs_km">Jarak ke PMI (km)</label>
                        <input type="number" id="jarak_ke_rs_km" class="input-field" min="0" step="0.1" value="10.0" placeholder="Estimasi jarak">
                        </div>
                    </div>
            </div>

            <hr style="margin: 25px 0; border: 0; border-top: 1px solid #eee;">
            
            <!-- Data Medis -->
            <h3 style="margin-bottom: 15px; font-size: 18px; color: #444;">Data Medis & Kondisi Fisik</h3>
            
            <div class="form-row">
                <div class="form-col">
                    <div class="input-group">
                        <label class="required" for="blood_group">Golongan Darah</label>
                        <select id="blood_group" class="select-field" required>
                            <option value="">Pilih</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="AB">AB</option>
                            <option value="O" selected>O</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-col">
                    <div class="input-group">
                        <label class="required">Rhesus</label>
                        <div class="radio-group" id="rhesusGroup">
                            <div class="radio-option" onclick="selectRadio('rhesus', 'positif', this)">
                                <div class="radio-button selected"></div>
                                <span>Positif (+)</span>
                            </div>
                            <div class="radio-option" onclick="selectRadio('rhesus', 'negatif', this)">
                                <div class="radio-button"></div>
                                <span>Negatif (-)</span>
                            </div>
                        </div>
                        <input type="hidden" id="rhesusValue" value="+">
                    </div>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-col">
                    <div class="input-group">
                        <label class="required" for="usia">Usia (Tahun)</label>
                        <input type="number" id="usia" class="input-field" placeholder="17" required>
                        <small style="color: #666; font-size: 12px;">17 - 60 tahun</small>
                    </div>
                </div>
                <div class="form-col">
                    <div class="input-group">
                        <label class="required" for="berat_badan">Berat Badan (kg)</label>
                        <input type="number" id="berat_badan" class="input-field" placeholder="60" required>
                        <small style="color: #666; font-size: 12px;">Min: 45 kg</small>
                    </div>
                </div>
                <div class="form-col">
                    <div class="input-group">
                        <label class="required" for="hb_level">HB Level (g/dL)</label>
                        <input type="number" id="hb_level" class="input-field" step="0.1" placeholder="13.5" required>
                        <small style="color: #666; font-size: 12px;">Normal: 12.5 - 17.0</small>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                    <label class="required" for="riwayat_penyakit">Riwayat Penyakit</label>
                    <select id="riwayat_penyakit" class="select-field">
                    <option value="Tidak" selected>Tidak ada (Sehat)</option>
                    <option value="Hipertensi">Hipertensi (Darah Tinggi)</option>
                    <option value="Diabetes">Diabetes</option>
                    <option value="Jantung">Penyakit Jantung</option>
                    <option value="Hepatitis">Hepatitis B / C</option>
                    <option value="Anemia">Anemia Kronis</option>
                </select>
            </div>
            
            <div class="form-row">
                <div class="form-col">
                        <div class="input-group">
                        <label for="number_of_donation">Jumlah Donor Sebelumnya</label>
                        <input type="number" id="number_of_donation" class="input-field" min="0" value="0">
                        </div>
                </div>
                <div class="form-col">
                        <div class="input-group">
                        <label for="months_since_first_donation">Terakhir Donor (bulan)</label>
                        <input type="number" id="months_since_first_donation" class="input-field" min="0" value="0">
                        <small style="color: #666; font-size: 12px;">Min: 2 bulan</small>
                        </div>
                </div>
            </div>
            
            <div class="form-group">
                <label>Ketersediaan untuk Dihubungi</label>
                    <div class="radio-group" id="availabilityGroup">
                    <div class="radio-option" onclick="selectRadio('availability', 'Yes', this)">
                        <div class="radio-button selected"></div>
                        <span>Ya, Bersedia</span>
                    </div>
                    <div class="radio-option" onclick="selectRadio('availability', 'No', this)">
                        <div class="radio-button"></div>
                        <span>Tidak Sementara Ini</span>
                    </div>
                </div>
                <input type="hidden" id="availabilityValue" value="Yes">
            </div>

            <button type="submit" class="search-button" id="submitBtn">
                <i class="fas fa-paper-plane"></i> Daftar & Cek Kelayakan
            </button>
            
                <div class="form-info">
                <i class="fas fa-info-circle"></i>
                <span>Data Anda akan diproses oleh sistem Machine Learning kami untuk memverifikasi kelayakan donor secara otomatis.</span>
            </div>
            
            <!-- Auto-fill button for demo -->
            <button type="button" onclick="fillTestData()" style="margin-top: 15px; background: none; border: 1px dashed #ccc; color: #666; width: 100%; padding: 10px; cursor: pointer; border-radius: 8px;">
                <i class="fas fa-magic"></i> Isi Data Contoh (Demo)
            </button>
        </form>
    </div>
    
    <!-- Right Column: Results & Info -->
    <div class="recommendation-section">
        <h2 class="section-title"><i class="fas fa-clipboard-check"></i> Hasil Prediksi</h2>
        
        <div class="info-card">
            <div class="info-title"><i class="fas fa-info-circle"></i> Syarat Utama Donor Darah</div>
            <ul style="list-style: none; padding: 0; margin-top: 10px;">
                <li style="margin-bottom: 8px; display: flex; align-items: start; gap: 8px;">
                    <i class="fas fa-check-circle" style="color: #2e7d32; margin-top: 3px;"></i> 
                    <span>Usia 17 - 60 tahun</span>
                </li>
                <li style="margin-bottom: 8px; display: flex; align-items: start; gap: 8px;">
                    <i class="fas fa-check-circle" style="color: #2e7d32; margin-top: 3px;"></i> 
                    <span>Berat badan minimal 45 kg</span>
                </li>
                <li style="margin-bottom: 8px; display: flex; align-items: start; gap: 8px;">
                    <i class="fas fa-check-circle" style="color: #2e7d32; margin-top: 3px;"></i> 
                    <span>Kadar Hemoglobin 12.5 - 17.0 g/dL</span>
                </li>
                <li style="margin-bottom: 8px; display: flex; align-items: start; gap: 8px; margin-left: 20px; font-size: 0.9em; color: #555;">
                    <!-- <i class="fas fa-venus" style="color: #e91e63; margin-top: 3px;"></i>  -->
                    <span>Perempuan: Min 12.5 g/dL</span>
                </li>
                <li style="margin-bottom: 8px; display: flex; align-items: start; gap: 8px; margin-left: 20px; font-size: 0.9em; color: #555;">
                    <!-- <i class="fas fa-mars" style="color: #1976d2; margin-top: 3px;"></i>  -->
                    <span>Laki-laki: Min 13.5 g/dL</span>
                </li>
                <li style="margin-bottom: 8px; display: flex; align-items: start; gap: 8px;">
                    <i class="fas fa-check-circle" style="color: #2e7d32; margin-top: 3px;"></i> 
                    <span>Sehat jasmani dan rohani</span>
                </li>
            </ul>
        </div>
        
        <div id="resultContainer">
            <div style="text-align: center; color: #999; padding: 40px 0;">
                <i class="fas fa-file-medical-alt" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;"></i>
                <p>Silakan isi form di samping untuk melihat hasil prediksi kelayakan Anda.</p>
            </div>
        </div>
    </div>
</div>

<script>
    // Handle Radio Button Selection UI
    function selectRadio(groupName, value, element) {
        // Update UI
        const group = element.closest('.radio-group');
        group.querySelectorAll('.radio-button').forEach(btn => btn.classList.remove('selected'));
        element.querySelector('.radio-button').classList.add('selected');
        
        // Update Hidden Value
        if(groupName === 'rhesus') {
                document.getElementById('rhesusValue').value = (value === 'positif') ? '+' : '-';
        } else if (groupName === 'availability') {
                document.getElementById('availabilityValue').value = value;
        }
    }

    document.getElementById('donorForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const resultContainer = document.getElementById('resultContainer');
        const submitBtn = document.getElementById('submitBtn');
        const originalBtnText = submitBtn.innerHTML;
        
        // Loading State
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
        submitBtn.disabled = true;
        resultContainer.innerHTML = '<div style="text-align: center; padding: 30px;"><i class="fas fa-spinner fa-spin fa-3x" style="color: #c62828;"></i><p style="margin-top: 15px;">Menganalisis data medis...</p></div>';
        
        // Kumpulkan data dari form
        const rhesusVal = document.getElementById('rhesusValue').value;
        const donorData = {
            name: document.getElementById('name').value,
            email: document.getElementById('email').value,
            contact_number: document.getElementById('contact_number').value,
            gender: document.getElementById('gender').value,
            city: document.getElementById('city').value,
            blood_group: document.getElementById('blood_group').value + rhesusVal, // Concat Blood Group + Rhesus
            usia: parseInt(document.getElementById('usia').value),
            berat_badan: parseInt(document.getElementById('berat_badan').value),
            hb_level: parseFloat(document.getElementById('hb_level').value),
            riwayat_penyakit: document.getElementById('riwayat_penyakit').value,
            number_of_donation: parseInt(document.getElementById('number_of_donation').value) || 0,
            months_since_first_donation: parseInt(document.getElementById('months_since_first_donation').value) || 0,
            availability: document.getElementById('availabilityValue').value,
            jarak_ke_rs_km: parseFloat(document.getElementById('jarak_ke_rs_km').value) || 10.0
        };
        
        try {
            // Kirim ke API - Adjust path to go up one level then to api
            // Kirim ke API - Adjust path to go up two levels for views/public/
            const response = await fetch('../../api/add_donor.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(donorData)
            });
            
            const result = await response.json();
            
            // Tampilkan Hasil
            if (result.success) {
                const status = parseInt(result.status_layak);
                
                let resultHTML = '';
                
                if (status === 1) {
                    // LAYAK (Green)
                    resultHTML = `
                        <div class="result-card active eligible">
                            <div class="result-header" style="color: #2e7d32;">
                                <i class="fas fa-check-circle"></i> LAYAK DONOR
                            </div>
                            <div style="margin-bottom: 15px;">
                                <p style="color: #2e7d32; font-size: 15px; font-weight: 500; margin-bottom: 10px;">
                                    Data medis menunjukkan bahwa Anda dalam kondisi prima untuk melakukan donor darah.
                                </p>
                            </div>
                            
                            <div style="background-color: #f0f7ff; border-radius: 8px; padding: 15px; border-left: 4px solid #2e7d32; font-size: 13.5px; color: #444;">
                                <strong style="display:block; margin-bottom:8px; color:#2e7d32;"><i class="fas fa-info-circle"></i> Catatan Penting:</strong>
                                <ul style="padding-left: 15px; margin: 0; line-height: 1.5;">
                                    <li style="margin-bottom: 5px;">Hasil di atas merupakan prediksi awal berdasarkan data yang Anda masukkan.</li>
                                    <li style="margin-bottom: 5px;">Kelayakan donor sesungguhnya akan diputuskan oleh Dokter/Petugas Medis melalui pemeriksaan fisik di tempat.</li>
                                    <li style="margin-bottom: 5px;">Pastikan Anda dalam kondisi prima (tidur minimal 5 jam dan sudah makan) sebelum mendonor.</li>
                                    <li>Harap membawa identitas diri (KTP, SIM, Paspor).</li>
                                </ul>
                            </div>
                        </div>
                    `;
                } else if (status === 2) {
                    // DITANGGUHKAN (Yellow)
                    resultHTML = `
                        <div class="result-card active" style="border-left: 5px solid #ff9800; background: #fff3e0;">
                            <div class="result-header" style="color: #ef6c00;">
                                <i class="fas fa-exclamation-triangle"></i> DITANGGUHKAN
                            </div>
                            
                            <div style="margin-bottom: 15px;">
                                <p style="color: #ef6c00; font-size: 15px; font-weight: 500;">
                                    Mohon maaf, Anda belum bisa donor saat ini tapi bisa mencoba lagi nanti.
                                </p>
                            </div>
                            
                            <div style="background: rgba(255, 255, 255, 0.6); padding: 10px; border-radius: 5px;">
                                <strong style="color: #d84315;">Alasan Penangguhan:</strong><br>
                                ${result.warning || 'Kondisi kesehatan perlu perbaikan sedikit.'}
                                ${result.suggestion ? `<ul style="margin-left: 20px; margin-top: 5px; font-size: 14px;">${result.suggestion.map(s => `<li>${s}</li>`).join('')}</ul>` : ''}
                            </div>
                            
                            <div style="margin-top: 15px; font-size: 13px; color: #555;">
                                <i class="fas fa-info-circle"></i> Silakan perbaiki nutrisi (zat besi/vitamin) dan kembali setelah kondisi membaik.
                            </div>
                        </div>
                    `;
                } else {
                    // TIDAK LAYAK (Red)
                    resultHTML = `
                        <div class="result-card active not-eligible">
                            <div class="result-header" style="color: #c62828;">
                                <i class="fas fa-times-circle"></i> DI TOLAK
                            </div>
                            
                            <hr style="margin: 15px 0; border: 0; border-top: 1px dashed #ccc;">
                            
                            <div style="background: rgba(255, 255, 255, 0.6); padding: 10px; border-radius: 5px;">
                                <strong style="color: #c62828;">Alasan Medis:</strong><br>
                                ${result.warning || 'Statistik kesehatan belum memenuhi syarat.'}
                                ${result.suggestion ? `<ul style="margin-left: 20px; margin-top: 5px; font-size: 14px;">${result.suggestion.map(s => `<li>${s}</li>`).join('')}</ul>` : ''}
                            </div>
                        </div>
                    `;
                }
                
                resultContainer.innerHTML = resultHTML;
            } else {
                    resultContainer.innerHTML = `
                    <div class="result-card active error">
                        <div class="result-header" style="color: #c62828;">
                            <i class="fas fa-times-circle"></i> GAGAL MENDAFTAR
                        </div>
                        <p>${result.message}</p>
                        ${result.error ? `<small>${result.error}</small>` : ''}
                    </div>
                `;
            }
            
        } catch (error) {
            resultContainer.innerHTML = `
                <div class="result-card active error">
                    <div class="result-header" style="color: #c62828;">
                        <i class="fas fa-network-wired"></i> KONEKSI ERROR
                    </div>
                    <p>Pastikan server backend berjalan.</p>
                    <small>${error.message}</small>
                </div>
            `;
        } finally {
            submitBtn.innerHTML = originalBtnText;
            submitBtn.disabled = false;
        }
    });
    
    // Auto-fill for testing
    function fillTestData() {
        document.getElementById('name').value = 'Budi Santoso';
        document.getElementById('email').value = 'budi@example.com';
        document.getElementById('contact_number').value = '08123456789';
        document.getElementById('city').value = 'Jakarta';
        document.getElementById('blood_group').value = 'O';
        document.getElementById('usia').value = '28';
        document.getElementById('berat_badan').value = '65';
        document.getElementById('hb_level').value = '14.5';
        
        // Trigger radio update manually if needed or just let visual stay default
    }
</script>

