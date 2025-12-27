<?php
error_reporting(0);
ini_set('display_errors', 0);
header('Content-Type: application/json');

require_once 'db.php';

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

if ($_SERVER['REQUEST_METHOD'] === 'GET' || $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Bisa support GET atau POST untuk request_id
    $request_id = $_REQUEST['request_id'] ?? null;
    
    if (!$request_id) {
        echo json_encode([
            'success' => false,
            'message' => 'Request ID diperlukan'
        ]);
        exit;
    }

    // 1. Ambil detail request dari database
    $stmt = $conn->prepare("SELECT blood_type, search_radius FROM blood_requests WHERE request_id = ?");
    $stmt->bind_param("s", $request_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Request tidak ditemukan'
        ]);
        exit;
    }
    
    $request_data = $result->fetch_assoc();
    $blood_type = $request_data['blood_type'];
    $search_radius = $request_data['search_radius'];
    $stmt->close();
    
    // 2. Cari donor yang cocok
    $donors = find_matching_donors($conn, $blood_type, $search_radius);
    
    // 3. Bersihkan rekomendasi lama jika ada (agar tidak duplikat saat reload)
    $del_stmt = $conn->prepare("DELETE FROM recommendations WHERE request_id = ?");
    $del_stmt->bind_param("s", $request_id);
    $del_stmt->execute();
    $del_stmt->close();

    // 4. Generate & Simpan rekomendasi baru
    $recommendations = generate_recommendations($conn, $request_id, $donors);
    
    echo json_encode([
        'success' => true,
        'request_id' => $request_id,
        'recommendations' => $recommendations
    ]);
    
    $conn->close();
}

function find_matching_donors($conn, $blood_type_request, $radius) {
    // Gunakan exact match
    $query = "
        SELECT *
        FROM donors
        WHERE blood_group = ?
          AND status_layak = 1
          AND availability = 'Yes'
          AND jarak_ke_rs_km <= ?
        ORDER BY jarak_ke_rs_km ASC
        LIMIT 10
    ";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sd", $blood_type_request, $radius);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $donors = [];
    
    while ($row = $result->fetch_assoc()) {
        $donors[] = $row;
    }
    
    return $donors;
}

function generate_recommendations($conn, $request_id, $donors) {
    $recommendations = [];
    
    foreach ($donors as $donor) {
        // Hitung match score
        $match_score = calculate_match_score($donor);
        
        $distance = $donor['jarak_ke_rs_km'];
        
        $stmt = $conn->prepare("INSERT INTO recommendations 
                                (request_id, donor_id, match_score, distance) 
                                VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssdd", $request_id, $donor['donor_id'], 
                        $match_score, $distance);
        $stmt->execute();
        
        $recommendations[] = [
            'name' => $donor['name'],
            'blood_type' => $donor['blood_group'],
            'age' => $donor['usia'],
            'distance' => round($distance, 1) . ' km',
            'last_donation' => ($donor['months_since_first_donation'] ?? 0) . ' bulan lalu',
            'score' => number_format($match_score, 1),
            'contact' => $donor['contact_number'],
            'hb_level' => $donor['hb_level'],
            'gender' => $donor['gender'],
            'city' => $donor['city']
        ];
    }
    
    return $recommendations;
}

function calculate_match_score($donor) {
    // Base Score untuk donor layak = 4.0
    $score = 4.0;
    
    // 1. Factor Kesehatan (Hb Level)
    if ($donor['hb_level'] >= 14 && $donor['hb_level'] <= 16) {
        $score += 0.3;
    } elseif ($donor['hb_level'] >= 13) {
        $score += 0.1;
    }
    
    // 2. Factor Berat Badan
    if ($donor['berat_badan'] > 65) {
        $score += 0.2;
    }
    
    // 3. Factor Jarak
    $dist = $donor['jarak_ke_rs_km'];
    if ($dist <= 2.0) {
        $score += 0.5;
    } elseif ($dist <= 5.0) {
        $score += 0.3;
    } elseif ($dist <= 10.0) {
        $score += 0.1;
    }
    
    // 4. Factor Pengalaman
    if ($donor['number_of_donation'] > 10) {
        $score += 0.2;
    }
    
    // 5. Usia Produktif
    if ($donor['usia'] >= 20 && $donor['usia'] <= 40) {
        $score += 0.1;
    }
    
    // Penalty
    if ($donor['months_since_first_donation'] > 24 && $donor['number_of_donation'] < 3) {
        $score -= 0.2;
    }
    
    return min(5.0, max(1.0, $score));
}

