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
        echo json_encode([
            'success' => true,
            'request_id' => $request_id,
            'message' => 'Permintaan darah berhasil disimpan'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Gagal menyimpan permintaan'
        ]);
    }
    
    $stmt->close();
}

$db->close();
?>