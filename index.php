<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Rekomendasi Donor Darah</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        
        header {
            background-color: #c62828;
            color: white;
            padding: 25px 30px;
            border-bottom: 5px solid #b71c1c;
        }
        
        h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        
        .subtitle {
            font-size: 16px;
            opacity: 0.9;
        }
        
        .main-content {
            display: flex;
            flex-wrap: wrap;
            padding: 0;
        }
        
        .request-section {
            flex: 1;
            min-width: 300px;
            padding: 30px;
            border-right: 1px solid #eee;
            background-color: #f9f9f9;
        }
        
        .recommendation-section {
            flex: 1;
            min-width: 300px;
            padding: 30px;
            background-color: white;
        }
        
        .section-title {
            color: #c62828;
            font-size: 22px;
            margin-bottom: 20px;
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
        
        .input-group label {
            margin-bottom: 8px;
        }
        
        .input-field {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        .input-field:focus {
            border-color: #c62828;
            outline: none;
            box-shadow: 0 0 0 3px rgba(198, 40, 40, 0.1);
        }
        
        .select-field {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            background-color: white;
            cursor: pointer;
            transition: border-color 0.3s;
        }
        
        .select-field:focus {
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
        }
        
        .radio-button {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 2px solid #c62828;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .radio-button.selected::after {
            content: '';
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #c62828;
        }
        
        .radius-selection {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 8px;
        }
        
        .radius-slider {
            flex: 1;
            height: 6px;
            background-color: #e0e0e0;
            border-radius: 3px;
            position: relative;
        }
        
        .slider-thumb {
            position: absolute;
            width: 20px;
            height: 20px;
            background-color: #c62828;
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
            left: 50%;
            cursor: pointer;
        }
        
        .radius-value {
            font-weight: 700;
            color: #c62828;
            min-width: 60px;
        }
        
        .divider {
            height: 1px;
            background-color: #e0e0e0;
            margin: 25px 0;
        }
        
        .donor-list {
            margin-top: 20px;
        }
        
        .donor-item {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
            border-left: 4px solid #c62828;
        }
        
        .donor-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .donor-name {
            font-weight: 700;
            font-size: 18px;
            color: #333;
        }
        
        .donor-blood {
            background-color: #c62828;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
        }
        
        .donor-details {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 10px;
        }
        
        .detail-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #666;
            font-size: 14px;
        }
        
        .detail-item i {
            color: #c62828;
        }
        
        .stats-container {
            display: flex;
            gap: 20px;
            margin-top: 25px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
        }
        
        .stat-item {
            text-align: center;
            flex: 1;
        }
        
        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #c62828;
        }
        
        .stat-label {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }
        
        .error-message {
            background-color: #ffebee;
            border: 1px solid #ef9a9a;
            color: #c62828;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .error-message i {
            font-size: 20px;
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
            margin-top: 10px;
        }
        
        .search-button:hover {
            background-color: #b71c1c;
        }
        
        .form-info {
            background-color: #e8f5e9;
            border: 1px solid #c8e6c9;
            border-radius: 8px;
            padding: 15px;
            margin-top: 25px;
            color: #2e7d32;
            font-size: 14px;
        }
        
        .form-info i {
            margin-right: 8px;
        }
        
        @media (max-width: 768px) {
            .main-content {
                flex-direction: column;
            }
            
            .request-section {
                border-right: none;
                border-bottom: 1px solid #eee;
            }
            
            .stats-container {
                flex-direction: column;
                gap: 15px;
            }
            
            .radio-group {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Sistem Rekomendasi Donor Darah</h1>
            <p class="subtitle">Cari Donor Darah Terdekat</p>
            <p class="subtitle">Masukkan kebutuhan darah untuk mendapatkan rekomendasi donor terbaik</p>
        </header>
        
        <div class="main-content">
            <div class="request-section">
                <h2 class="section-title">Permintaan Darah</h2>
                
                <form id="bloodRequestForm">
                    <div class="form-group">
                        <div class="form-row">
                            <div class="form-col">
                                <div class="input-group">
                                    <label for="hospitalName">Nama</label>
                                    <input id="hospitalName" placeholder="Nama Pencari / Rumah Sakit" class="select-field" required >
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
                        Form ini akan mengirim permintaan darah ke sistem untuk mencari donor yang sesuai
                    </div>
                </form>
            </div>
            
            <div class="recommendation-section">
                <h2 class="section-title">Donor yang Direkomendasikan</h2>
                
                <div class="error-message" id="errorMessage">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div>
                        <strong>Gagal mengambil data.</strong> Pastikan backend berjalan di port 8000.
                    </div>
                </div>
                
                <div class="donor-list" id="donorList">
                    <!-- Data donor akan ditampilkan di sini -->
                </div>
                
                <div class="stats-container">
                    <div class="stat-item">
                        <div class="stat-value" id="avgScore">4.8</div>
                        <div class="stat-label">Rata-rata Skor</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" id="avgDistance">3.2 km</div>
                        <div class="stat-label">Km Rata-rata</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" id="eligibleDonors">5</div>
                        <div class="stat-label">Donor Layak</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- <script>
        // Data donor contoh untuk simulasi
        const sampleDonors = [
            {
                name: "Andi Wijaya",
                bloodType: "O+",
                age: 28,
                distance: "2.1 km",
                lastDonation: "3 bulan lalu",
                score: 4.9
            },
            {
                name: "Sari Dewi",
                bloodType: "O+",
                age: 32,
                distance: "3.5 km",
                lastDonation: "5 bulan lalu",
                score: 4.7
            },
            {
                name: "Budi Santoso",
                bloodType: "O+",
                age: 35,
                distance: "1.8 km",
                lastDonation: "2 bulan lalu",
                score: 4.8
            },
            {
                name: "Rina Melati",
                bloodType: "O+",
                age: 25,
                distance: "4.2 km",
                lastDonation: "6 bulan lalu",
                score: 4.5
            },
            {
                name: "Dian Prasetyo",
                bloodType: "O+",
                age: 30,
                distance: "2.9 km",
                lastDonation: "4 bulan lalu",
                score: 4.6
            }
        ]; -->
        
        // Fungsi untuk menampilkan data donor
        function displayDonors() {
            const donorList = document.getElementById('donorList');
            donorList.innerHTML = '';
            
            sampleDonors.forEach(donor => {
                const donorElement = document.createElement('div');
                donorElement.className = 'donor-item';
                donorElement.innerHTML = `
                    <div class="donor-header">
                        <div class="donor-name">${donor.name}</div>
                        <div class="donor-blood">${donor.bloodType}</div>
                    </div>
                    <div class="donor-details">
                        <div class="detail-item">
                            <i class="fas fa-user"></i>
                            <span>${donor.age} tahun</span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>${donor.distance} dari RS</span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-tint"></i>
                            <span>Donor terakhir: ${donor.lastDonation}</span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-star"></i>
                            <span>Skor: ${donor.score}</span>
                        </div>
                    </div>
                `;
                donorList.appendChild(donorElement);
            });
        }
        
        // Fungsi untuk menyembunyikan pesan error
        function hideError() {
            document.getElementById('errorMessage').style.display = 'none';
        }
        
        // Fungsi untuk mengatur pilihan radio (rhesus dan urgensi)
        function setupRadioButtons() {
            const radioOptions = document.querySelectorAll('.radio-option');
            
            radioOptions.forEach(option => {
                option.addEventListener('click', function() {
                    const group = this.closest('.radio-group');
                    const hiddenInput = group.nextElementSibling || group.parentElement.querySelector('input[type="hidden"]');
                    
                    // Hapus seleksi dari semua opsi dalam grup yang sama
                    group.querySelectorAll('.radio-option').forEach(opt => {
                        opt.querySelector('.radio-button').classList.remove('selected');
                    });
                    
                    // Tambahkan seleksi pada opsi yang diklik
                    this.querySelector('.radio-button').classList.add('selected');
                    
                    // Update nilai input tersembunyi
                    if (hiddenInput) {
                        hiddenInput.value = this.getAttribute('data-value');
                    }
                });
            });
        }
        
        // Fungsi untuk mengatur slider radius
        function setupRadiusSlider() {
            const sliderThumb = document.querySelector('.slider-thumb');
            const radiusSlider = document.querySelector('.radius-slider');
            const radiusValue = document.querySelector('.radius-value');
            const radiusHidden = document.getElementById('searchRadius');
            
            let isDragging = false;
            
            // Fungsi untuk memperbarui posisi slider dan nilai
            function updateSliderPosition(newX) {
                const sliderRect = radiusSlider.getBoundingClientRect();
                
                // Batasi posisi slider dalam area
                if (newX < 0) newX = 0;
                if (newX > sliderRect.width) newX = sliderRect.width;
                
                // Posisikan thumb
                sliderThumb.style.left = `${newX}px`;
                
                // Hitung nilai radius (5-30 km)
                const percent = newX / sliderRect.width;
                const radius = Math.round(1 + percent * 25);
                radiusValue.textContent = `${radius} km`;
                radiusHidden.value = radius;
            }
            
            sliderThumb.addEventListener('mousedown', () => {
                isDragging = true;
                document.addEventListener('mousemove', handleMouseMove);
                document.addEventListener('mouseup', () => {
                    isDragging = false;
                    document.removeEventListener('mousemove', handleMouseMove);
                });
            });
            
            function handleMouseMove(e) {
                if (!isDragging) return;
                
                const sliderRect = radiusSlider.getBoundingClientRect();
                const newX = e.clientX - sliderRect.left;
                updateSliderPosition(newX);
            }
            
            // Klik langsung pada slider
            radiusSlider.addEventListener('click', (e) => {
                const sliderRect = radiusSlider.getBoundingClientRect();
                const newX = e.clientX - sliderRect.left;
                updateSliderPosition(newX);
            });
            
            // Set posisi awal slider (15 km)
            const sliderRect = radiusSlider.getBoundingClientRect();
            const initialPercent = (15 - 5) / 25; // 15 km adalah 40% dari 5-30 km
            const initialX = sliderRect.width * initialPercent;
            updateSliderPosition(initialX);
        }
        
        // Fungsi untuk mengumpulkan data form
        function getFormData() {
            return {
                hospitalName: document.getElementById('hospitalName').value,
                bloodBags: document.getElementById('bloodBags').value,
                bloodType: document.getElementById('bloodType').value,
                rhesus: document.getElementById('rhesus').value,
                urgencyLevel: document.getElementById('urgencyLevel').value,
                searchRadius: document.getElementById('searchRadius').value
            };
        }
        
        // Fungsi untuk menampilkan data form yang dikirim
        function showFormData(formData) {
            const donorList = document.getElementById('donorList');
            const formInfo = document.createElement('div');
            formInfo.className = 'donor-item';
            formInfo.style.backgroundColor = '#e3f2fd';
            formInfo.style.borderLeftColor = '#1976d2';
            formInfo.innerHTML = `
                <div class="donor-header">
                    <div class="donor-name">Detail Permintaan</div>
                    <div class="donor-blood" style="background-color: #1976d2;">
                        ${formData.bloodType}${formData.rhesus === 'positif' ? '+' : '-'}
                    </div>
                </div>
                <div class="donor-details">
                    <div class="detail-item">
                        <i class="fas fa-hospital" style="color: #1976d2;"></i>
                        <span>RS: ${formData.hospitalName}</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-medkit" style="color: #1976d2;"></i>
                        <span>${formData.bloodBags} kantong darah</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-exclamation-triangle" style="color: #1976d2;"></i>
                        <span>Urgensi: ${formData.urgencyLevel}</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-search-location" style="color: #1976d2;"></i>
                        <span>Radius: ${formData.searchRadius} km</span>
                    </div>
                </div>
            `;
            donorList.prepend(formInfo);
        }
        
        // Event listener untuk form submission
        // Update bagian form submission
document.getElementById('bloodRequestForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    // Sembunyikan pesan error
    hideError();
    
    // Dapatkan data form
    const formData = getFormData();
    
    // Tampilkan loading
    const searchButton = document.getElementById('searchButton');
    const originalText = searchButton.innerHTML;
    searchButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
    searchButton.disabled = true;
    
    try {
        // Kirim request ke backend
        const response = await fetch('api/request_blood.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData)
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Tampilkan data form
            showFormData(formData);
            
            // Tampilkan rekomendasi
            displayRecommendations(result.recommendations);
            
            // Update statistik
            updateStatistics(result.recommendations);
            
            // Tampilkan pesan sukses
            showSuccessMessage(result.recommendations.length);
        } else {
            showErrorMessage(result.message);
        }
        
    } catch (error) {
        console.error('Error:', error);
        showErrorMessage('Terjadi kesalahan pada server');
    } finally {
        // Restore button
        searchButton.innerHTML = originalText;
        searchButton.disabled = false;
    }
});

// Fungsi untuk menampilkan rekomendasi
function displayRecommendations(recommendations) {
    const donorList = document.getElementById('donorList');
    donorList.innerHTML = '';
    
    recommendations.forEach(donor => {
        const donorElement = document.createElement('div');
        donorElement.className = 'donor-item';
        donorElement.innerHTML = `
            <div class="donor-header">
                <div class="donor-name">${donor.name}</div>
                <div class="donor-blood">${donor.blood_type}</div>
            </div>
            <div class="donor-details">
                <div class="detail-item">
                    <i class="fas fa-user"></i>
                    <span>${donor.age} tahun</span>
                </div>
                <div class="detail-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>${donor.distance}</span>
                </div>
                <div class="detail-item">
                    <i class="fas fa-tint"></i>
                    <span>Donor terakhir: ${donor.last_donation}</span>
                </div>
                <div class="detail-item">
                    <i class="fas fa-star"></i>
                    <span>Skor: ${donor.score}/5.0</span>
                </div>
                <div class="detail-item">
                    <i class="fas fa-phone"></i>
                    <span>${donor.contact}</span>
                </div>
            </div>
        `;
        donorList.appendChild(donorElement);
    });
}
    </script>
    <script>
document.addEventListener('DOMContentLoaded', () => {
    setupRadiusSlider();
    setupRadioButtons();
    displayDonors(); // kalau mau auto tampil
});
</script>
</body>
</html>