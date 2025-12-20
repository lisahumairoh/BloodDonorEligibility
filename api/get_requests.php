<?php
header('Content-Type: application/json');
require_once 'db.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    // Select all requests, ordered by latest date
    // We handle potential missing columns gracefully in frontend if needed, 
    // but assuming request_date exists based on user image.
    $query = "SELECT * FROM blood_requests ORDER BY request_date DESC";
    $result = $conn->query($query);
    
    $requests = [];
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $requests[] = $row;
        }
        
        echo json_encode([
            'success' => true,
            'data' => $requests,
            'count' => count($requests)
        ]);
    } else {
        // If query fails (e.g., table doesn't exist yet, though unlikely)
        throw new Exception($conn->error);
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching requests: ' . $e->getMessage()
    ]);
} finally {
    if (isset($db)) {
        $db->close();
    }
}
?>
