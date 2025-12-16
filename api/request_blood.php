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

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $requester_name = $data['hospitalName'];
    $blood_type = $data['bloodType'] . ($data['rhesus'] === 'positif' ? '+' : '-');
    $blood_bags = $data['bloodBags'];
    $urgency_level = $data['urgencyLevel'];
    $search_radius = $data['searchRadius'];
    
    // Generate unique request ID
    $request_id = 'REQ_' . date('YmdHis') . '_' . rand(1000, 9999);
    
    // Simpan ke tabel blood_requests
    $stmt = $conn->prepare("INSERT INTO blood_requests 
                            (request_id, requester_name, blood_type, blood_bags, 
                             urgency_level, search_radius, status) 
                            VALUES (?, ?, ?, ?, ?, ?, 'pending')");
    $stmt->bind_param("sssiss", $request_id, $requester_name, $blood_type, 
                     $blood_bags, $urgency_level, $search_radius);
    
    if ($stmt->execute()) {
        // Cari donor yang cocok
        $donors = find_matching_donors($conn, $blood_type, $search_radius);
        
        // Generate rekomendasi
        $recommendations = generate_recommendations($conn, $request_id, $donors);
        
        echo json_encode([
            'success' => true,
            'request_id' => $request_id,
            'recommendations' => $recommendations,
            'message' => 'Permintaan darah berhasil diproses'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Gagal menyimpan permintaan'
        ]);
    }
    
    $stmt->close();
}

function find_matching_donors($conn, $blood_type_request, $radius) {
    // Gunakan exact match (A- cari A-)
    // User request: "blood Tipe A- maka yang di recomendasikan semua data donor yang A- juga"
    
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
        
        // Simpan ke tabel recommendations
        // Pastikan tabel recommendations ada kolom distance
        // $donor['jarak_ke_rs_km'] adalah jarak donor ke RS
        $distance = $donor['jarak_ke_rs_km'];
        
        $stmt = $conn->prepare("INSERT INTO recommendations 
                                (request_id, donor_id, match_score, distance) 
                                VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssdd", $request_id, $donor['donor_id'], 
                        $match_score, $distance);
        $stmt->execute();
        
        $recommendations[] = [
            'name' => $donor['name'],
            'blood_type' => $donor['blood_group'], // Tampilkan apa adanya dari DB (misal 'O')
            'age' => $donor['usia'],
            'distance' => round($distance, 1) . ' km',
            'last_donation' => ($donor['months_since_first_donation'] ?? 0) . ' bulan lalu',
            'score' => number_format($match_score, 1),
            'contact' => $donor['contact_number']
        ];
    }
    
    return $recommendations;
}

function calculate_match_score($donor) {
    // Algoritma Scoring (Skala 1.0 - 5.0)
    // Base Score untuk donor layak = 4.0
    $score = 4.0;
    
    // 1. Factor Kesehatan (Hb Level)
    // Hb normal ~13-17. Lebih optimal jika di tengah.
    if ($donor['hb_level'] >= 14 && $donor['hb_level'] <= 16) {
        $score += 0.3; // Hb sangat prima
    } elseif ($donor['hb_level'] >= 13) {
        $score += 0.1; // Hb oke
    }
    
    // 2. Factor Berat Badan
    // > 60kg dianggap lebih bugar untuk donor
    if ($donor['berat_badan'] > 65) {
        $score += 0.2;
    }
    
    // 3. Factor Jarak (urgensi logistik)
    // Semakin dekat semakin tinggi
    $dist = $donor['jarak_ke_rs_km'];
    if ($dist <= 2.0) {
        $score += 0.5; // Sangat dekat
    } elseif ($dist <= 5.0) {
        $score += 0.3; // Dekat
    } elseif ($dist <= 10.0) {
        $score += 0.1; // Lumayan
    }
    
    // 4. Factor Pengalaman (Loyalitas)
    if ($donor['number_of_donation'] > 10) {
        $score += 0.2; // Donor veteran
    }
    
    // 5. Usia Produktif (20 - 40)
    if ($donor['usia'] >= 20 && $donor['usia'] <= 40) {
        $score += 0.1;
    }
    
    // Penalty: Donor jarang aktif (bulan sejak donor pertama besar, tapi jumlah donor sedikit)
    if ($donor['months_since_first_donation'] > 24 && $donor['number_of_donation'] < 3) {
        $score -= 0.2;
    }
    
    // Cap score at 5.0
    return min(5.0, max(1.0, $score));
}

$db->close();
?>