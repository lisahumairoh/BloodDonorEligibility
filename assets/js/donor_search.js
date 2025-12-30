/**
 * Logika Pencarian Donor dan Pagination
 */

// State Variables
let currentPage = 1;
let currentRequestId = null;
let isLoading = false;
let allDonors = []; // Store all fetched donors
let currentView = 'table'; // Default to table

// Fungsi untuk menampilkan rekomendasi (updated to handle data accumulation)
function displayRecommendations(newRecommendations, append = false) {
    if (!append) {
        allDonors = []; // Reset if not appending (new search)
    }

    // Add new items to main list
    allDonors = [...allDonors, ...newRecommendations];

    // Render current state
    renderDonors();
}

// Core Render Function
function renderDonors() {
    const donorList = document.getElementById('donorList');
    donorList.innerHTML = '';

    if (allDonors.length === 0) return;

    if (currentView === 'table') {
        renderTableView(donorList);
    } else {
        renderListView(donorList);
    }
}

let currentSort = { column: 'score', direction: 'desc' }; // Default sort by score desc

function sortTable(column) {
    // Toggle direction
    if (currentSort.column === column) {
        currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
    } else {
        currentSort.column = column;
        currentSort.direction = 'asc';
    }

    // Sort array
    allDonors.sort((a, b) => {
        let valA = a[column];
        let valB = b[column];

        // Special handling
        if (column === 'distance') {
            // "1.1 km" -> 1.1
            valA = parseFloat(valA.replace(' km', ''));
            valB = parseFloat(valB.replace(' km', ''));
        } else if (column === 'age' || column === 'score' || column === 'hb_level') {
            valA = parseFloat(valA);
            valB = parseFloat(valB);
        }

        if (valA < valB) return currentSort.direction === 'asc' ? -1 : 1;
        if (valA > valB) return currentSort.direction === 'asc' ? 1 : -1;
        return 0;
    });

    renderDonors();
}

function renderTableView(container) {
    // Ubah container style untuk table
    container.style.display = 'block';

    // Helper to get icon class
    const getSortIcon = (col) => {
        if (currentSort.column !== col) return 'fa-sort';
        return currentSort.direction === 'asc' ? 'fa-sort-up' : 'fa-sort-down';
    };

    // Helper to get th class
    const getSortClass = (col) => {
        if (currentSort.column !== col) return '';
        return currentSort.direction === 'asc' ? 'sort-asc' : 'sort-desc';
    };

    const tableHTML = `
        <div class="donor-table-container">
            <table class="donor-table">
                <thead>
                    <tr>
                        <th onclick="sortTable('name')" class="${getSortClass('name')}">Nama <i class="fas ${getSortIcon('name')}"></i></th>
                        <th onclick="sortTable('gender')" class="${getSortClass('gender')}">Gender <i class="fas ${getSortIcon('gender')}"></i></th>
                        <th onclick="sortTable('blood_type')" class="${getSortClass('blood_type')}">Gol. <i class="fas ${getSortIcon('blood_type')}"></i></th>
                        <th onclick="sortTable('hb_level')" class="${getSortClass('hb_level')}">HB <i class="fas ${getSortIcon('hb_level')}"></i></th>
                        <th onclick="sortTable('age')" class="${getSortClass('age')}">Usia <i class="fas ${getSortIcon('age')}"></i></th>
                        <th onclick="sortTable('city')" class="${getSortClass('city')}">Kota <i class="fas ${getSortIcon('city')}"></i></th>
                        <th onclick="sortTable('distance')" class="${getSortClass('distance')}">Jarak <i class="fas ${getSortIcon('distance')}"></i></th>
                        <th onclick="sortTable('score')" class="${getSortClass('score')}">Skor <i class="fas ${getSortIcon('score')}"></i></th>
                        <th>Kontak</th>
                    </tr>
                </thead>
                <tbody>
                    ${allDonors.map(donor => {
        const waMessage = `Halo ${donor.name},\nSaat ini kami membutuhkan donor darah golongan ${donor.blood_type}.\nMohon kesediaannya untuk datang ke PMI (Kota Depok).\nBantuan Anda sangat berarti. Terima kasih`;
        const waUrl = `https://wa.me/${donor.contact.replace(/^0/, '62')}?text=${encodeURIComponent(waMessage)}`;

        return `
                        <tr>
                            <td>
                                <div style="font-weight: bold;">${donor.name}</div>
                                <div style="font-size: 12px; color: #666;">Last: ${donor.last_donation}</div>
                            </td>
                            <td>${donor.gender}</td>
                            <td><span class="table-blood-badge">${donor.blood_type}</span></td>
                            <td>${donor.hb_level}</td>
                            <td>${donor.age} thn</td>
                            <td>${donor.city}</td>
                            <td><i class="fas fa-map-marker-alt" style="color: #c62828;"></i> ${donor.distance}</td>
                            <td><i class="fas fa-star" style="color: #ffb300;"></i> ${donor.score}</td>
                            <td>
                                <a href="${waUrl}" target="_blank" class="table-action-btn">
                                    <i class="fab fa-whatsapp"></i> Hubungi
                                </a>
                            </td>
                        </tr>
                    `}).join('')}
                </tbody>
            </table>
        </div>
    `;
    container.innerHTML = tableHTML;
}

function renderListView(container) {
    // Restore grid layout
    container.style.display = 'grid';
    container.style.gridTemplateColumns = 'repeat(auto-fill, minmax(320px, 1fr))';

    allDonors.forEach(donor => {
        const donorElement = document.createElement('div');
        donorElement.className = 'donor-item';
        donorElement.style.animation = 'fadeIn 0.5s';

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
        container.appendChild(donorElement);
    });
}

// Fungsi untuk Load More
async function loadMoreDonors() {
    if (isLoading || !currentRequestId) return;

    const loadMoreBtn = document.getElementById('loadMoreBtn');
    if (loadMoreBtn) {
        loadMoreBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memuat...';
        loadMoreBtn.disabled = true;
        loadMoreBtn.parentElement.style.display = 'block';
    }

    isLoading = true;

    // Safety Timeout: If loading takes > 8 seconds, force stop and show error
    const safetyTimeout = setTimeout(() => {
        if (isLoading) {
            console.error("Loading timeout.");
            isLoading = false;
            const donorList = document.getElementById('donorList');
            if (donorList && donorList.innerHTML.includes('fa-spin')) {
                donorList.innerHTML = `
                    <div class="empty-state" style="border-color: #ffe0b2; background-color: #fff3e0; color: #e65100;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 48px; margin-bottom: 15px;"></i>
                        <p>Waktu Habis.</p>
                        <small>Memuat data terlalu lama. Silakan coba lagi.</small>
                    </div>
                `;
            }
            if (loadMoreBtn) loadMoreBtn.innerHTML = 'Coba Lagi';
        }
    }, 8000);

    try {
        currentPage++;
        console.log(`Fetching page ${currentPage} for request ${currentRequestId}`);

        // NOTE: Path needs to be correct relative to where this script is called.
        const response = await fetch(`../../api/get_recommendations.php?request_id=${currentRequestId}&page=${currentPage}`);

        let text = await response.text();

        // Remove processing timeout
        clearTimeout(safetyTimeout);

        let result;

        try {
            result = JSON.parse(text);
        } catch (e) {
            console.error('Invalid JSON response:', text);
            throw new Error('Respon server tidak valid');
        }

        if (result.success) {
            if (result.recommendations && result.recommendations.length > 0) {
                // Should append is slightly different logic now since we manage state in allDonors
                // But displayRecommendations handles the append logic with the flag
                const shouldAppend = currentPage > 1;
                displayRecommendations(result.recommendations, shouldAppend);

                // Update button visibility
                if (loadMoreBtn) {
                    if (!result.has_more && result.recommendations.length < 10) {
                        loadMoreBtn.parentElement.style.display = 'none';
                    } else {
                        loadMoreBtn.innerHTML = 'Muat Lebih Banyak';
                        loadMoreBtn.disabled = false;
                    }
                }
            } else {
                // If NO recommendations found at all
                if (currentPage === 1) {
                    console.log("No donors found on first page.");
                    const donorList = document.getElementById('donorList');
                    // Explicitly clear spinner
                    donorList.innerHTML = '';

                    donorList.innerHTML = `
                        <div class="empty-state">
                            <i class="fas fa-search" style="font-size: 48px; margin-bottom: 15px; opacity: 0.3;"></i>
                            <p>Belum ada donor yang cocok ditemukan.</p>
                            <small>Cobalah perluas radius pencarian.</small>
                        </div>
                    `;
                }
                if (loadMoreBtn) loadMoreBtn.parentElement.style.display = 'none';
            }
        } else {
            throw new Error(result.message || 'Gagal memuat data');
        }
    } catch (error) {
        clearTimeout(safetyTimeout); // Ensure timeout cleared on error too
        console.error('Error loading more:', error);

        const donorList = document.getElementById('donorList');
        // Show error in the list if it's the first load
        if (currentPage <= 1 || donorList.children.length <= 1) {
            donorList.innerHTML = `
                <div class="empty-state" style="background-color: #ffebee; border-color: #ffcdd2; color: #c62828;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 48px; margin-bottom: 15px;"></i>
                    <p>Terjadi Kesalahan</p>
                    <small>${error.message}</small>
                    <button onclick="location.reload()" style="margin-top: 15px; padding: 8px 15px; border: 1px solid #c62828; color: #c62828; background: white; border-radius: 5px; cursor: pointer;">Coba Lagi</button>
                </div>
            `;
            if (loadMoreBtn) loadMoreBtn.parentElement.style.display = 'none';
        } else {
            if (loadMoreBtn) {
                loadMoreBtn.innerHTML = 'Gagal';
                alert('Gagal: ' + error.message);
            }
        }
    } finally {
        isLoading = false;
    }
}

// Setup logic for Search Form Page
function setupSearchFormLogic() {
    // Setup slider logic
    const sliderThumb = document.querySelector('.slider-thumb');
    const radiusSlider = document.querySelector('.radius-slider');
    const radiusValue = document.querySelector('.radius-value');
    const radiusHidden = document.getElementById('searchRadius');

    if (radiusSlider && sliderThumb) {
        let isDragging = false;

        function updateSliderPosition(newX) {
            const sliderRect = radiusSlider.getBoundingClientRect();
            if (newX < 0) newX = 0;
            if (newX > sliderRect.width) newX = sliderRect.width;

            sliderThumb.style.left = `${newX}px`;
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

        radiusSlider.addEventListener('click', (e) => {
            const sliderRect = radiusSlider.getBoundingClientRect();
            const newX = e.clientX - sliderRect.left;
            updateSliderPosition(newX);
        });

        // Initial position
        const sliderRect = radiusSlider.getBoundingClientRect();
        if (sliderRect.width > 0) {
            const initialPercent = (15 - 5) / 25;
            const initialX = sliderRect.width * initialPercent;
            updateSliderPosition(initialX);
        }
    }

    // Setup Form Submit
    const form = document.getElementById('bloodRequestForm');
    if (form) {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            const searchButton = document.getElementById('searchButton');
            const originalText = searchButton.innerHTML;
            searchButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
            searchButton.disabled = true;

            const formData = {
                hospitalName: document.getElementById('hospitalName').value,
                bloodBags: document.getElementById('bloodBags').value,
                bloodType: document.getElementById('bloodType').value,
                rhesus: document.getElementById('rhesus').value,
                urgencyLevel: document.getElementById('urgencyLevel').value,
                searchRadius: document.getElementById('searchRadius').value
            };

            try {
                // Adjust path to API based on location
                const response = await fetch('../../api/request_blood.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();

                if (result.success) {
                    window.location.href = `search_results.php?request_id=${result.request_id}`;
                } else {
                    alert('Gagal: ' + result.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan koneksi');
            } finally {
                searchButton.innerHTML = originalText;
                searchButton.disabled = false;
            }
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    // Detect if we are on search form page or results page
    if (document.getElementById('bloodRequestForm')) {
        setupSearchFormLogic();

        // Setup Radio buttons logic
        document.querySelectorAll('.radio-option').forEach(option => {
            option.addEventListener('click', function () {
                const group = this.parentElement;
                const hiddenInput = group.nextElementSibling || group.parentElement.querySelector('input[type="hidden"]');

                group.querySelectorAll('.radio-button').forEach(btn => btn.classList.remove('selected'));
                this.querySelector('.radio-button').classList.add('selected');

                if (hiddenInput) {
                    hiddenInput.value = this.getAttribute('data-value');
                }
            });
        });
    }

    // Load More Button Logic (already defined above loadMoreDonors)
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', loadMoreDonors);
    }

    // View Toggle Logic
    const toggleBtns = document.querySelectorAll('.toggle-btn');
    if (toggleBtns.length > 0) {
        toggleBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Update active state
                toggleBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                // Update view mode
                currentView = btn.getAttribute('data-view');
                renderDonors();
            });
        });
    }
});
