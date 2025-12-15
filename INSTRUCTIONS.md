# Panduan Pengembangan Sistem Prediksi Kelayakan Donor Darah

Dokumen ini berisi informasi mengenai struktur project, persyaratan sistem, instalasi, dan panduan untuk mengembangkan sistem prediksi kelayakan donor darah ini.

## Deskripsi Project

Sistem ini adalah aplikasi berbasis web yang bertujuan untuk memprediksi apakah seorang calon pendonor darah layak untuk mendonorkan darahnya atau tidak.
Sistem ini menggunakan algoritma **Machine Learning (Random Forest)** yang diimplementasikan dengan **Python**, dan antarmuka web yang dibangun dengan **PHP**.

### Teknologi yang Digunakan
- **Backend & Frontend**: Native PHP
- **Machine Learning**: Python (scikit-learn, pandas, numpy)
- **Database**: MySQL
- **Model Format**: Pickle (.pkl)

## Struktur Folder

```
blood_donation/
├── api/                    # Backend API PHP
│   ├── config.php          # Konfigurasi database & path Python
│   ├── db.php              # Class koneksi database
│   ├── add_donor.php       # Endpoint untuk menambah data donor & prediksi
│   ├── run_prediction.php  # Endpoint khusus untuk testing prediksi
│   └── ...
├── ml/                     # Machine Learning Component
│   ├── predict.py          # Script Python untuk load model & prediksi
│   ├── blood_donor_model.pkl # Model Random Forest yang sudah dilatih
│   ├── encoders_final.pkl  # Encoder untuk data kategorikal
│   └── feature_names.pkl   # Daftar fitur yang digunakan model
├── assets/                 # File statis (CSS, JS, Images)
├── index.php               # Halaman utama
└── app_instruction.md      # Panduan ini
```

## Persyaratan Sistem

Sebelum menjalankan project, pastikan sistem Anda memiliki:

1.  **Web Server & PHP**: XAMPP (Direkomendasikan) atau sejenisnya.
2.  **Database**: MySQL (bawaan XAMPP).
3.  **Python**: Versi 3.x terinstall dan terdaftar di PATH system (bisa dipanggil via terminal dengan perintah `python`).
4.  **Library Python**: Install library yang dibutuhkan:

    ```bash
    pip install pandas numpy scikit-learn joblib
    ```

## Instalasi & Konfigurasi

### 1. Konfigurasi Database

1.  Buka **phpMyAdmin** (biasanya di `http://localhost/phpmyadmin`).
2.  Buat database baru dengan nama: `blood_donation`.
3.  Jalankan query SQL berikut untuk membuat tabel-tabel yang diperlukan:

### Tabel `donors`
Menyimpan data pendaftar donor darah.
```sql
CREATE TABLE `donors` (
  `donor_id` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `city` varchar(50) DEFAULT 'Jakarta',
  `blood_group` varchar(5) NOT NULL,
  `availability` varchar(10) DEFAULT 'Yes',
  `months_since_first_donation` int(11) DEFAULT 0,
  `number_of_donation` int(11) DEFAULT 0,
  `created_at` date NOT NULL,
  `usia` int(11) NOT NULL,
  `berat_badan` int(11) NOT NULL,
  `hb_level` decimal(4,1) NOT NULL,
  `riwayat_penyakit` varchar(50) DEFAULT 'Tidak',
  `jarak_ke_rs_km` decimal(5,1) DEFAULT 0.0,
  `status_layak` tinyint(1) DEFAULT 0,
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`donor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### Tabel `blood_requests`
Menyimpan data permintaan darah.
```sql
CREATE TABLE `blood_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `request_id` varchar(50) DEFAULT NULL,
  `requester_name` varchar(100) DEFAULT NULL,
  `hospital_name` varchar(100) DEFAULT NULL,
  `blood_type` varchar(5) DEFAULT NULL,
  `urgency_level` varchar(20) DEFAULT NULL,
  `blood_bags` int(11) DEFAULT NULL,
  `search_radius` int(11) DEFAULT NULL,
  `request_date` datetime DEFAULT current_timestamp(),
  `status` enum('pending','processing','completed','cancelled') DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `request_id` (`request_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### Tabel `recommendations`
Menyimpan history rekomendasi donor.
```sql
CREATE TABLE `recommendations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `request_id` varchar(50) DEFAULT NULL,
  `donor_id` varchar(50) DEFAULT NULL,
  `match_score` decimal(5,2) DEFAULT NULL,
  `distance` decimal(5,2) DEFAULT NULL,
  `recommended_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `request_id` (`request_id`),
  KEY `donor_id` (`donor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### 2. Konfigurasi Aplikasi

Buka file `api/config.php` dan pastikan konfigurasi sudah sesuai dengan environment Anda.

```php
// Konfigurasi database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); // Sesuaikan jika ada password
define('DB_NAME', 'blood_donation');

// Konfigurasi Python
// Jika python tidak di PATH, ganti dengan full path, misal: "C:\\Python39\\python.exe"
define('PYTHON_PATH', 'python'); 
```

## Alur Kerja Sistem

Sistem ini memiliki dua modul utama:

### 1. Pendaftaran Donor & Prediksi Kelayakan (input_donor.php)
Halaman ini (`input_donor.php`) digunakan oleh calon donor untuk mendaftar.
1.  **User Input**: User mengisi form data diri dan medis.
2.  **API Call**: Data dikirim ke `api/add_donor.php`.
3.  **ML Prediction**: Backend memanggil script Python untuk memprediksi kelayakan (Layak/Tidak) berdasarkan data medis (Hb, Usia, Berat Badan, Penyakit).
4.  **Database**: Data donor disimpan dengan status kelayakannya.
5.  **Feedback**: User mendapat notifikasi apakah mereka layak donor atau tidak.

### 2. Pencarian Donor (index.php)
Halaman utama (`index.php`) digunakan oleh pencari darah / RS.
1.  **Request**: User memasukkan kebutuhan darah (Gol. Darah, Lokasi, Urgensi).
2.  **Search**: Sistem mencari donor di database yang:
    *   Golongan darah sesuai/kompatibel.
    *   Berstatus **LAYAK** (hasil prediksi ML).
    *   Lokasi terjangkau (dalam radius km).
3.  **Result**: Menampilkan daftar donor rekomendasi yang memenuhi kriteria.

## Panduan Pengembangan

### Mengubah Model ML
Jika Anda melatih ulang model:
1.  Pastikan format input (fitur) sama dengan yang diharapkan di `ml/predict.py`.
2.  Simpan model baru ke `ml/blood_donor_model.pkl`.
3.  Jika encoders berubah, update `ml/encoders_final.pkl`.

### Mengubah Logika Data
*   File `ml/predict.py`: Mengatur logika preprocessing data sebelum masuk ke model (One-Hot Encoding, Feature Engineering).
*   File `api/add_donor.php`: Mengatur validasi input dari user sebelum dikirim ke Python.

### Troubleshooting
Jika prediksi gagal:
1.  Cek apakah Python bisa dijalankan lewat CMD/Terminal.
2.  Pastikan library scikit-learn terinstall.
3.  Jika muncul error di PHP, cek `api/config.php` bagian `PYTHON_PATH`.
