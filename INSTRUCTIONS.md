# Panduan Teknis & Logika Sistem Donor Darah

Dokumen ini berisi dokumentasi mendalam mengenai alur logika, algoritma, dan arsitektur teknis dari proyek Sistem Rekomendasi Donor Darah. Gunakan dokumen ini sebagai referensi untuk pemeliharaan dan pengembangan lanjutan.

## 1. Arsitektur Sistem

Sistem dibangun dengan arsitektur **Hybrid Monolithic**, di mana:
- **Frontend & Backend**: Digabung menggunakan PHP Native.
- **Intelligence Layer**: Terpisah menggunakan Python (Machine Learning), yang dipanggil oleh PHP via `exec` cmd.

### Struktur Modul
1. **Module Registrasi (`input_donor.php`)**: Interface pendaftaran donor.
2. **Module Pencarian (`index.php`)**: Interface pencari darah.
3. **Module API (`api/`)**: Menangani logika bisnis dan komunikasi database.
4. **Module ML (`ml/`)**: Menangani prediksi cerdas.

---

## 2. Alur Logika Utama

### A. Alur Pendaftaran & Validasi Donor (Hybrid logic)

Sistem menggunakan pendekatan **Hybrid Validation** untuk menentukan status `status_layak` (Eligible) seorang donor.

**Flowchart:**
`User Input` -> `API (api/add_donor.php)` -> `Coba ML Prediction` -> `Jika Error, Gunakan Fallback Manual` -> `Save ke Database`.

#### 1. Validasi Frontend (UI Level)
Aturan interaktif di `views/public/index.php` sebelum data dikirim:
- **Rule Dependensi Jumlah Donor**: 
  - Jika `Jumlah Donor > 0`, maka kolom `Terakhir Donor` **WAJIB DIISI** dan **NILAI > 0**. 
  - Jika user mengisi 0, akan muncul *Javascript Alert* dan input di-reset.
  - Jika `Jumlah Donor == 0`, kolom `Terakhir Donor` otomatis **Disabled** dan bernilai 0.

#### 2. Machine Learning Check (Prioritas Utama)
- **File**: `ml/predict.py`
- **Trigger**: Dipanggil oleh `api/add_donor.php`.
- **Input Features**:
  - `hb_level`: Kadar hemoglobin.
  - `berat_badan`: Berat badan (kg).
  - `usia`: Usia donor.
  - `riwayat_penyakit`: (One-hot encoded).
  - **Engineered Features** (Dihitung internal oleh script):
    - `health_score`: Skor komposit dari HB, Berat, dan Penyakit.
    - `frekuensi_donor`: Konsistensi donor (Jumlah donor / Lama jadi donor).
    - `kategori_hb`: Kategorisasi (Rendah/Normal/Tinggi).
- **Output**: Probabilitas (0.0 - 1.0) dan Status Layak (0/1). A

#### 2. Manual Rule-Based Fallback (Cadangan)
Jika script Python gagal dijalankan (misal error library/path), PHP akan mengambil alih dengan aturan baku (`check_eligibility_manual` di `api/add_donor.php`):

| Parameter | Syarat Layak |
|-----------|--------------|
| Usia | 17 - 60 tahun |
| Berat Badan | Min 45 kg |
| HB Level | Pria: ≥ 13.5, Wanita: ≥ 12.5 |
| Penyakit | Bebas Hepatitis & Jantung |
| Ketersediaan | Harus "Yes" |

---

### B. Alur Pencarian & Rekomendasi 

Logika bagaimana sistem menemukan donor yang tepat untuk suatu permintaan.

**File**: `api/request_blood.php`

#### 1. Logika Pencarian Golongan Darah (SQL Matching)
Sistem menggunakan `LIKE` matching untuk menangani kecocokan Rhesus.
- **Request "O"**: Akan mencari `blood_group LIKE 'O%'`.
- **Hasil**: Menemukan donor **O+** dan **O-**.
- **Request "A"**: Akan mencari `blood_group LIKE 'A%'` TAPI `NOT LIKE 'AB%'` (untuk mencegah tercampur dengan AB).

#### 2. Algoritma Ranking (Scoring System)
Setiap donor yang cocok akan diberi nilai (`match_score`) 1.0 - 5.0 berdasarkan:

1. **Jarak (Logistik)**:
   - < 2km: +0.5 poin
   - < 5km: +0.3 poin
2. **Kesehatan Fisik**:
   - HB Prima (14-16): +0.3 poin
   - Berat Badan > 65kg: +0.2 poin
3. **Rekam Jejak**:
   - Donor Veteran (>10x): +0.2 poin
4. **Penalty**:
   - Jarang aktif (>24 bulan vakum): -0.2 poin

---

## 3. Database Schema

### Tabel `donors`
Menyimpan profil pendonor.
- `blood_group`: Disimpan lengkap dengan rhesus (contoh: 'A+', 'B-'). *PENTING: Jangan ubah format ini karena ML dan Search Logic bergantung padanya.*
- `status_layak`: 1 (Layak) atau 0 (Tidak). Hasil dari ML/Manual check saat register.
- `jarak_ke_rs_km`: Disimpan statis saat register (dalam implementasi nyata, ini harusnya dihitung dinamis menggunakan Geolocation API).

### Tabel `recommendations`
Menyimpan hasil pencarian agar tidak perlu hitung ulang.
- `match_score`: Nilai kecocokan final.
- `distance`: Jarak saat rekomendasi dibuat.

---

## 4. Troubleshooting Umum

**Masalah 1: Probabilitas Kelayakan Selalu 10%**
- **Penyebab**: Script Python gagal berjalan, sistem masuk ke mode Fallback Manual.
- **Solusi**: Cek instalasi library python (`pip install pandas scikit-learn`), cek path python di `api/config.php`, atau cek data input (misal nama feature tidak cocok dengan model).

**Masalah 2: Pencarian Kosong (Padahal Gol. Darah Cocok)**
- **Penyebab**: Logika SQL terlalu ketat (misal `WHERE blood_group = 'O'`) padahal data di DB `O+`.
- **Solusi**: Gunakan `LIKE 'O%'` seperti yang sudah diimplementasikan di `api/request_blood.php`.

**Masalah 3: UI Slider/Radio Tidak Bisa Diklik**
- **Penyebab**: Fungsi Javascript `setupRadiusSlider()` atau `setupRadioButtons()` hilang atau error sebelum dipanggil.
- **Solusi**: Pastikan block `<script>` di `index.php` lengkap dan tidak ada syntax error.



Berdasarkan analisis file 
ml/predict.py
, berikut adalah parameter yang dipelajari dan menentukan keputusan AI:

1. Parameter Input Langsung (Direct Features) AI menganalisis data mentah yang Anda masukkan:

Profil Medis: Kadar Hemoglobin (HB), Berat Badan, Usia.
Riwayat Penyakit: Hipertensi, Diabetes, Jantung, Hepatitis (AI memberi bobot negatif berat pada ini).
Golongan Darah: Termasuk Rhesus (+/-).
Rekam Jejak: Jumlah donor sebelumnya dan berapa bulan sejak donor pertama.
2. Pola Tersembunyi yang Dipelajari (Engineered Features) Di sinilah letak "kecerdasan" model ini. Selain membaca angka mentah, script 
predict.py
 menghitung indikator kesehatan yang lebih dalam:



Berdasarkan analisa kode di input_donor.php dan api/add_donor.php, sistem ini menggunakan Pendekatan Hybrid (Cerdas & Manual) untuk menentukan kelayakan donor:

Prioritas Utama: Machine Learning (AI) Sistem akan mencoba menjalankan script cerdas (ml/predict.py) terlebih dahulu. Model ini mempelajari pola dari data historis untuk memprediksi probabilitas kelayakan seseorang.
Fallback: Rule-Based (SOP Standar) Jika sistem AI tidak bisa dijalankan (misal Python bermasalah), sistem otomatis beralih ke pengecekan manual standar PMI yang tertulis di kode PHP (check_eligibility_manual), yaitu:
Usia: Wajib 17 - 65 tahun.
Berat Badan: Minimal 45 kg.
HB Level: Minimal 12.5 (Wanita) atau 13.5 (Pria).
Riwayat Penyakit: Tidak boleh ada Hepatitis atau Jantung.
Ketersediaan: Status harus "Yes".
Jadi sistem ini cukup robust; ia mencoba cara "pintar" dulu, tapi tetap punya standar baku sebagai pengaman.


Konsistensi Donor (frekuensi_donor): Rasio antara jumlah donor dibagi lama waktu jadi pendonor. AI belajar bahwa pendonor yang rutin (frekuensi stabil) biasanya lebih sehat dan darahnya lebih aman dibanding yang jarang-jarang.
Skor Kesehatan Komposit (health_score): Rumus khusus yang menggabungkan HB + Berat Badan + Status Penyakit menjadi satu nilai tunggal.
Rumus di code: 
(HB/17 * 4) + (Bonus Berat Badan) + (Bonus Sehat)
.
Kategorisasi Risiko: AI tidak hanya melihat angka "25 tahun", tapi mengelompokkannya ke kategori risiko (Muda, Dewasa, Tua) dan level HB (Rendah, Normal, Tinggi) untuk mencocokkan dengan pola statistik kelayakan medis.
Ringkasan Cara Kerjanya: AI ini telah dilatih (.pkl file) dengan data historis. Ia "tahu" kombinasi mana yang beresiko.

Contoh: Seseorang berat 50kg (batas bawah) tapi HB-nya sangat bagus (16) dan masih muda, mungkin diprediksi LAYAK karena skor kesehatannya tinggi.
Sedangkan yang berat 80kg tapi HB rendah dan punya riwayat hipertensi akan diprediksi TIDAK LAYAK.

## 5. Interpretasi Skor & Logika Pengukuran

Sistem ini menampilkan dua jenis angka yang berbeda konteksnya. Berikut cara membacanya:

### A. Skor Kelayakan Donor (0% - 100%)
Muncul pada saat **Pendaftaran Donor** (`input_donor.php`).
- **Makna**: Seberapa yakin AI bahwa donor ini sehat dan memenuhi syarat medis.
- **Sumber**: Output `predict_proba()` dari model Machine Learning (Random Forest).
- **Interpretasi Nilai**:
  - `> 50%`: Diprediksi **LAYAK**. Semakin tinggi (mendekati 100%), semakin ideal kondisi fisiknya (HB optimal, usia produktif, dll).
  - `< 50%`: Diprediksi **TIDAK LAYAK**. Semakin rendah (mendekati 0%), semakin berisiko (misal punya penyakit berat atau HB anjlok).
  - *Catatan: Jika nilai 10% muncul terus menerus untuk kondisi tidak layak, itu adalah tanda Fallback Manual aktif.*

### B. Match Score (1.0 - 5.0)
Muncul pada saat **Pencarian / Rekomendasi** (`index.php`).
- **Makna**: Seberapa "cocok" donor tersebut untuk kebutuhan spesifik saat ini.
- **Sumber**: Perhitungan heuristik di PHP (`api/request_blood.php`).
- **Komponen Penilaian**:
  | Komponen | Bobot Skor |
  |----------|------------|
  | **Base Score** | 3.0 (Modal awal jika layak) |
  | **Jarak Lokasi** | +1.0 (<2km), +0.7 (<5km), +0.4 (<10km) |
  | **Kesehatan** | +0.4 (HB 14-16), +0.2 (Berat >65kg), +0.1 (Riwayat Aman) |
  | **Track Record** | +0.8 (>20x), +0.5 (10-20x), +0.3 (3-9x), +0.1 (<3x) |
  | **Penalty** | *Dihapus* (Digantikan dengan tier track record) |
- **Nilai Maksimal**: 5.0 (Sempurna - Dekat, Sehat, Veteran).

---

## 6. Status Kelayakan Detail (Logic 0, 1, 2)

Sistem menggunakan 3 status kelayakan yang ditentukan oleh kombinasi **Strict Rules** (aturan mutlak) dan **Prediksi ML**.

| Status | Kode | Logic / Kriteria | Keterangan untuk User |
|:---|:---:|:---|:---|
| **TIDAK LAYAK** | `0` | **Strict Rules:**<br>• Berat Badan < 45 kg<br>• Usia < 17 atau > 60 tahun<br>• Ada Riwayat Penyakit (Hipertensi, Diabetes, Jantung, Hepatitis)<br>• HB < 10.0 (Anemia Berat)<br>• HB > 17.0 (Darah Kental) | **Merah (Ditolak)**<br>User dilarang donor karena alasan medis fatal/mutlak. |
| **DITANGGUHKAN** | `2` | **Logic:**<br>• HB 10.0 - < 12.5 (Wanita)<br>• HB 10.0 - < 13.5 (Pria)<br>• Interval Donor < 2 bulan (jika bukan donor baru) | **Kuning (Warning)**<br>User sehat tapi kondisi saat ini belum optimal. Disarankan kembali setelah perbaikan nutrisi/waktu. |
| **LAYAK** | `1` | **Logic:**<br>• Lolos semua Strict Rules (0)<br>• Lolos semua Logic Penangguhan (2)<br>• Probabilitas ML > 50% | **Hijau (Success)**<br>Kondisi prima dan siap donor. |

---

## 7. Log Perubahan Harian (27 Desember 2025)

Berikut adalah ringkasan teknis pekerjaan yang diselesaikan hari ini:

### A. Frontend & UI/UX
1.  **Fixed Public Footer (`views/public/index.php`)**:
    *   Memperbaiki struktur HTML. Footer sebelumnya terjebak dalam `.container` form sehingga lebarnya terbatas. Menambahkan tag penutup `</div>` yang hilang.
2.  **Dashboard Chart (`views/backoffice/index.php`)**:
    *   Mengimplementasikan **Chart.js Doughnut Chart** untuk memvisualisasikan data real-time status kelayakan donor (Layak, Ditangguhkan, Tidak Layak).
    *   Memperbaiki layout dashboard yang sempat rusak (overwrite content).
3.  **Simplified Widget Data Donor (`views/backoffice/data_donor.php`)**:
    *   Mengubah widget stok darah menjadi desain flat yang lebih sederhana dan bersih.
    *   Menambahkan total count header.

### B. Fitur & Fungsionalitas
1.  **Pagination Data Donor**:
    *   Mengganti teks statis "Menampilkan 20 data" dengan sistem **Pagination Dinamis**.
    *   Limit diset ke **20 data per halaman**.
    *   Menambahkan tombol navigasi (Next/Prev) dan indikator halaman.
2.  **Inline Status Update (`views/backoffice/request_list.php`)**:
    *   Mengubah kolom status menjadi **Dropdown Menu**.
    *   Membuat API endpoint baru `api/update_request_status.php` untuk menangani update status secara asynchronous (tanpa reload).
    *   Status visual update otomatis (warna badge berubah sesuai status: OPEN/FULFILLED/dll).

### C. Backend & Logic
1.  **Fix Infinite Loading (`data_donor.php`)**:
    *   Menemukan dan memperbaiki syntax error Javascript (extra `}`) yang menyebabkan tabel gagal memuat data.
2.  **Investigasi Logic HB**:
        *   Wanita: HB 10.0 - 12.4
        *   Pria: HB 10.0 - 13.4
        *   Interval Donor < 2 bulan.

## 8. Log Perubahan (1 Januari 2026)

### A. Frontend & Validation `views/public/index.php`
1. **Dynamic Logic "Terakhir Donor"**:
   - Jika **Jumlah Donor = 0**: Kolom "Terakhir Donor" otomatis disable dan set ke 0.
   - Jika **Jumlah Donor > 0**: 
     - Kolom "Terakhir Donor" **Wajib Diisi (Required)**.
     - Nilai **TIDAK BOLEH 0**. Jika user mengisi 0, system akan menolak dan memberikan *alert* peringatan.
   - Tujuan: Mencegah inkonsistensi data (User mengaku pernah donor tapi jarak terakhirnya 0 bulan/tidak valid).
