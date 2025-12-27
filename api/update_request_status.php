<?php
header('Content-Type: application/json');
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get JSON input
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['request_id']) || !isset($data['status'])) {
            throw new Exception("Missing request_id or status");
        }
        
        $request_id = $data['request_id'];
        $status = $data['status'];
        
        // Allowed statuses
        $allowed_statuses = ['PENDING', 'OPEN', 'IN_PROGRESS', 'FULFILLED', 'CLOSED', 'CANCELLED'];
        
        if (!in_array($status, $allowed_statuses)) {
            // Optional: You might want to allow lowercase or mixed case, but strict is safer
            // throw new Exception("Invalid status value"); 
        }

        $db = new Database();
        $conn = $db->getConnection();
        
        $stmt = $conn->prepare("UPDATE blood_requests SET status = ? WHERE request_id = ?");
        $stmt->bind_param("ss", $status, $request_id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => "Status updated to $status"]);
        } else {
            throw new Exception("Update failed: " . $stmt->error);
        }
        
        $stmt->close();
        $conn->close();
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
}
?>
