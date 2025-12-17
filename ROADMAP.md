# Roadmap Pengembangan & Analisis Kekurangan Sistem

Dokumen ini berisi analisis teknis mengenai kekurangan (*limitations*) yang ada pada sistem saat ini, serta rencana pengembangan (*roadmap*) untuk menjadikan aplikasi ini siap produksi (*production-ready*).

## 1. Analisis Kekurangan (Current Limitations)

### A. Keamanan (Security) - **KRITIKAL**
1.  **Tidak Ada Autentikasi**: File `index.php` (Pencarian Donor) terbuka untuk publik. Data donor yang berisi NIK/No.HP/Alamat bisa diakses oleh siapa saja (Risiko penyalahgunaan data).
2.  **API Terbuka**: `api/request_blood.php` dan `api/add_donor.php` tidak memiliki mekanisme token/API Key. Pihak luar bisa melakukan spamming data palsu ke database.
3.  **Cross-Origin (CORS)**: Konfigurasi `header('Access-Control-Allow-Origin: *')` terlalu longgar.

### B. Arsitektur & Performa
1.  **Synchronous Blocking**: Backend PHP memanggil Python menggunakan `proc_open` secara sinkron (menunggu selesai).
    *   *Dampak*: Jika model ML lambat atau hang, halaman web user akan loading terus menerus (timeout).
2.  **Ketergantungan Path**: Sistem menggunakan `exec`/command line untuk Python. Ini sering bermasalah di hosting (shared hosting sering memblokir fungsi `exec`).
3.  **Hardcoded Distance**: Kolom `jarak_ke_rs_km` diisi manual oleh user.
    *   *Masalah*: User bisa bohong atau salah isi. Jarak "5 km" menurut user belum tentu akurat secara geospasial.

### C. Fitur & Validasi
1.  **Validasi Email/No.HP**: Belum ada verifikasi OTP. User bisa mendaftar dengan nomor palsu.
2.  **Ketersediaan Statis**: Jika donor sudah mendonorkan darahnya kemarin, status di sistem tidak otomatis berubah.

---

## 2. Saran Pengembangan (Development Roadmap)

### Fase 1: Hardening & Security (Prioritas Tinggi)
- [ ] **Implementasi Login System**: Buat halaman login khusus untuk Petugas Palang Merah/Rumah Sakit. Batasi akses pencarian donor hanya untuk user login.
- [ ] **API Security**: Tambahkan validasi API Key atau JWT (JSON Web Token) untuk setiap request ke API.
- [ ] **Input Sanitization**: Perketat validasi input di `input_donor.php` (misal: validasi format email/nomor HP yang ketat).

### Fase 2: Modernisasi Arsitektur
- [ ] **Microservice Python**: Ubah skrip `predict.py` menjadi web service menggunakan **Flask** atau **FastAPI**.
    *   *Benefit*: PHP cukup kirim HTTP Request ke API Python (lebih cepat, stabil, dan bisa dipisah servernya).
- [ ] **Geolocation API**: Integrasikan Google Maps API / Leaflet.
    *   User input alamat -> API convert ke Lat/Long.
    *   Jarak dihitung otomatis by system (bukan input user).

### Fase 3: User Experience (UX)
- [ ] **Notifikasi WhatsApp**: Gunakan Twilio/Wablas. Saat RS request darah, kirim WA otomatis ke 10 donor teratas.
- [ ] **Dashboard Admin**: Halaman grafik untuk memantau stok darah, tren pendaftaran, dan sebaran wilayah donor.
- [ ] **Sistem Feedback**: Setelah donor terjadi, RS bisa update status request jadi "Completed", dan donor mendapat poin/lencana.

## 3. Kesimpulan Teknikal

Sistem saat ini sudah berfungsi baik sebagai **Prototipe / MVP (Minimum Viable Product)**. Logika intinya (Hybrid ML + Manual Fallback) sudah solid. Namun, untuk implementasi nyata di lapangan, aspek **Keamanan Data** dan **Validasi User** wajib diperbaiki terlebih dahulu sebelum fitur lainnya.
