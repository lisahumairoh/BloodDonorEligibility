<?php
error_reporting(0);
ini_set('display_errors', 0);
header('Content-Type: application/json');

require_once 'db.php';
require_once 'config.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database Error: ' . $e->getMessage()
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari POST request
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validasi data yang diperlukan
    $required_fields = ['name', 'email', 'contact_number', 'blood_group', 'usia', 'berat_badan', 'hb_level'];
    foreach ($required_fields as $field) {
        if (empty($data[$field])) {
            echo json_encode([
                'success' => false,
                'message' => "Field $field harus diisi"
            ]);
            exit;
        }
    }
    
    // Generate donor_id unik
    $donor_id = 'DONOR_' . date('Ymd') . '_' . strtoupper(substr(md5(uniqid()), 0, 6));
    
    // Set nilai default untuk field opsional
    $availability = isset($data['availability']) ? $data['availability'] : 'Yes';
    $months_since_first_donation = isset($data['months_since_first_donation']) ? $data['months_since_first_donation'] : 0;
    $number_of_donation = isset($data['number_of_donation']) ? $data['number_of_donation'] : 0;
    $riwayat_penyakit = isset($data['riwayat_penyakit']) ? $data['riwayat_penyakit'] : 'Tidak';
    $jarak_ke_rs_km = isset($data['jarak_ke_rs_km']) ? $data['jarak_ke_rs_km'] : 10.0;
    $city = isset($data['city']) ? $data['city'] : 'Jakarta';
    
    // Siapkan data untuk prediksi ML
    $ml_data = [
        'name' => $data['name'],
        // Keep rhesus sign (+/-) for ML model because encoders expects it (e.g. 'A+', 'O-')
        'blood_group' => $data['blood_group'],
        'availability' => $availability,
        'months_since_first_donation' => $months_since_first_donation,
        'number_of_donation' => $number_of_donation,
        'usia' => $data['usia'],
        'berat_badan' => $data['berat_badan'],
        'hb_level' => $data['hb_level'],
        'riwayat_penyakit' => $riwayat_penyakit,
        'jarak_ke_rs_km' => $jarak_ke_rs_km
    ];
    
    try {
        // Jalankan prediksi kelayakan menggunakan ML model
        $prediction = run_ml_prediction($ml_data);
        $status_layak = $prediction['status_layak'] ?? 0;
        
        // Insert data donor ke database
        $stmt = $conn->prepare("INSERT INTO donors (
            donor_id, name, email, contact_number, city, blood_group, 
            availability, months_since_first_donation, number_of_donation, 
            created_at, usia, berat_badan, hb_level, riwayat_penyakit, 
            jarak_ke_rs_km, status_layak
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, CURDATE(), ?, ?, ?, ?, ?, ?)");
        
        $stmt->bind_param(
            "sssssssiiiddsdi",
            $donor_id,
            $data['name'],
            $data['email'],
            $data['contact_number'],
            $city,
            $data['blood_group'],
            $availability,
            $months_since_first_donation,
            $number_of_donation,
            $data['usia'],
            $data['berat_badan'],
            $data['hb_level'],
            $riwayat_penyakit,
            $jarak_ke_rs_km,
            $status_layak
        );
        
        if ($stmt->execute()) {
            $response = [
                'success' => true,
                'message' => 'Data donor berhasil ditambahkan',
                'donor_id' => $donor_id,
                'status_layak' => $status_layak,
                'prediction_probability' => $prediction['probability'] ?? null
            ];
            
            // Jika donor tidak layak, berikan alasan
            if ($status_layak == 0) {
                $response['warning'] = 'Donor tidak memenuhi kriteria kelayakan berdasarkan analisis ML';
                $response['suggestion'] = get_eligibility_suggestions($ml_data);
            }
            
            echo json_encode($response);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal menyimpan data donor: ' . $stmt->error
            ]);
        }
        
        $stmt->close();
        
    } catch (Throwable $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
    
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Endpoint untuk mendapatkan semua donor
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 20;
    $offset = ($page - 1) * $limit;
    
    // Filter opsional
    $blood_group = isset($_GET['blood_group']) ? $_GET['blood_group'] : null;
    $city = isset($_GET['city']) ? $_GET['city'] : null;
    $status_layak = isset($_GET['status_layak']) ? $_GET['status_layak'] : null;
    
    // Build query dengan filter
    $query = "SELECT * FROM donors WHERE 1=1";
    $params = [];
    $types = "";
    
    if ($blood_group) {
        $query .= " AND blood_group = ?";
        $params[] = $blood_group;
        $types .= "s";
    }
    
    if ($city) {
        $query .= " AND city LIKE ?";
        $params[] = "%$city%";
        $types .= "s";
    }
    
    if ($status_layak !== null) {
        $query .= " AND status_layak = ?";
        $params[] = $status_layak;
        $types .= "i";
    }
    
    $query .= " ORDER BY last_update DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;
    $types .= "ii";
    
    // Execute query
    $stmt = $conn->prepare($query);
    
    if ($params) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $donors = [];
    while ($row = $result->fetch_assoc()) {
        // Format data untuk response
        $row['last_update'] = date('d/m/Y H:i', strtotime($row['last_update']));
        $row['created_at'] = date('d/m/Y', strtotime($row['created_at']));
        $donors[] = $row;
    }
    
    // Hitung total untuk pagination
    $count_query = "SELECT COUNT(*) as total FROM donors WHERE 1=1";
    if ($blood_group) $count_query .= " AND blood_group = '$blood_group'";
    if ($city) $count_query .= " AND city LIKE '%$city%'";
    if ($status_layak !== null) $count_query .= " AND status_layak = $status_layak";
    
    $count_result = $conn->query($count_query);
    $total = $count_result->fetch_assoc()['total'];
    
    echo json_encode([
        'success' => true,
        'donors' => $donors,
        'pagination' => [
            'page' => $page,
            'limit' => $limit,
            'total' => $total,
            'pages' => ceil($total / $limit)
        ]
    ]);
    
    $stmt->close();
}

function run_ml_prediction($data) {
    // Path ke script Python
    $python_script = dirname(__FILE__) . '/../ml/predict.py';
    
    if (!file_exists($python_script)) {
        return ['status_layak' => 0, 'error' => 'ML model tidak ditemukan'];
    }
    
    // Encode data ke JSON
    $json_data = json_encode($data);
    
    // Eksekusi Python script
    $command = PYTHON_PATH . " " . escapeshellarg($python_script);
    $descriptorspec = [
        0 => ["pipe", "r"], // stdin
        1 => ["pipe", "w"], // stdout
        2 => ["pipe", "w"]  // stderr
    ];
    
    $process = proc_open($command, $descriptorspec, $pipes);
    
    if (is_resource($process)) {
        // Kirim data ke Python
        fwrite($pipes[0], $json_data);
        fclose($pipes[0]);
        
        // Baca output
        $output = stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        
        $error = stream_get_contents($pipes[2]);
        fclose($pipes[2]);
        
        proc_close($process);
        
        // Parse hasil
        $result = json_decode($output, true);
        
        if ($result && !isset($result['error'])) {
            return $result;
        } else {
            // Fallback: gunakan rule-based checking jika ML gagal
            return check_eligibility_manual($data);
        }
    }
    
    return ['status_layak' => 0, 'error' => 'Gagal menjalankan prediksi'];
}

function check_eligibility_manual($data) {
    // Rule-based checking sebagai fallback
    $layak = 1;
    
    // Rule 1: Usia (17-65 tahun)
    if ($data['usia'] < 17 || $data['usia'] > 65) {
        $layak = 0;
    }
    
    // Rule 2: Berat badan (min 45 kg)
    if ($data['berat_badan'] < 45) {
        $layak = 0;
    }
    
    // Rule 3: HB Level (pria min 13.5, wanita min 12.5)
    // Asumsi: cek dari nama untuk menentukan gender
    $is_wanita = preg_match('/(mrs|ms|miss|female|woman|perempuan)/i', $data['name']);
    $hb_min = $is_wanita ? 12.5 : 13.5;
    
    if ($data['hb_level'] < $hb_min) {
        $layak = 0;
    }
    
    // Rule 4: Riwayat penyakit kritis
    if (in_array($data['riwayat_penyakit'], ['Hepatitis', 'Jantung'])) {
        $layak = 0;
    }
    
    // Rule 5: Availability
    if (strtolower($data['availability']) === 'no') {
        $layak = 0;
    }
    
    return [
        'status_layak' => $layak,
        'probability' => $layak ? 0.9 : 0.1,
        'fallback' => true
    ];
}

function get_eligibility_suggestions($data) {
    $suggestions = [];
    
    if ($data['usia'] < 17) {
        $suggestions[] = 'Usia minimal donor adalah 17 tahun';
    } elseif ($data['usia'] > 65) {
        $suggestions[] = 'Usia maksimal donor adalah 65 tahun';
    }
    
    if ($data['berat_badan'] < 45) {
        $suggestions[] = 'Berat badan minimal 45 kg';
    }
    
    $is_wanita = preg_match('/(mrs|ms|miss|female|woman|perempuan)/i', $data['name']);
    $hb_min = $is_wanita ? 12.5 : 13.5;
    
    if ($data['hb_level'] < $hb_min) {
        $suggestions[] = "Level HB minimal $hb_min g/dL";
    }
    
    if (in_array($data['riwayat_penyakit'], ['Hepatitis', 'Jantung'])) {
        $suggestions[] = "Riwayat {$data['riwayat_penyakit']} tidak diperbolehkan untuk donor darah";
    }
    
    if (strtolower($data['availability']) === 'no') {
        $suggestions[] = 'Donor sedang tidak tersedia';
    }
    
    return empty($suggestions) ? ['Tidak memenuhi kriteria kelayakan donor darah'] : $suggestions;
}

$db->close();