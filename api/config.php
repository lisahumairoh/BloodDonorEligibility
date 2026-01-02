<?php
// Konfigurasi database
// Menggunakan getenv() agar bisa membaca Environment Variables dari Railway/Docker
// Jika tidak ada ENV (Localhost), gunakan default value
define('DB_HOST', getenv('DB_HOST') ? getenv('DB_HOST') : 'turntable.proxy.rlwy.net');
define('DB_USER', getenv('DB_USER') ? getenv('DB_USER') : 'root');
define('DB_PASS', getenv('DB_PASS') ? getenv('DB_PASS') : 'UlzjivQnCWTBJFeyPbcBgXVAcFgQmOPV');
define('DB_NAME', getenv('DB_NAME') ? getenv('DB_NAME') : 'railway');
define('DB_PORT', getenv('DB_PORT') ? getenv('DB_PORT') : '50755');

// Konfigurasi Python
// Deteksi path python secara otomatis
if (getenv('PYTHON_PATH')) {
    define('PYTHON_PATH', getenv('PYTHON_PATH'));
} elseif (file_exists('/usr/bin/python3')) {
    // Path standar di Linux / Docker Container
    define('PYTHON_PATH', '/usr/bin/python3');
} else {
    // Path standar di Windows / XAMPP
    define('PYTHON_PATH', 'python');
}

define('ML_SCRIPT_PATH', dirname(__FILE__) . '/../ml/predict.py');


// CORS headers moved to individual API files
// header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
// header("Access-Control-Allow-Headers: Content-Type");
// header("Content-Type: application/json; charset=UTF-8");