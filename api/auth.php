<?php
session_start();
require_once 'config.php';
require_once 'db.php';

header('Content-Type: application/json');

// Helper untuk kirim response
function send_response($success, $message, $redirect = null) {
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'redirect' => $redirect
    ]);
    exit;
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'redirect' => $redirect
    ]);
    exit;
}

// Instantiate DB
$database = new Database();
$db = $database->getConnection();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'login') {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');
        
        if (empty($username) || empty($password)) {
            send_response(false, 'Username dan Password wajib diisi.');
        }
        
        // Query User (Using MD5 as per user request/plan)
        // Note: For production, use password_hash() and password_verify()
        $stmt = $db->prepare("SELECT id, username, password FROM users WHERE username = ? AND password = MD5(?)");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Set Session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            
            send_response(true, 'Login berhasil!', '../views/backoffice/index.php');
        } else {
            send_response(false, 'Username atau Password salah.');
        }
        
        $stmt->close();
        
    } elseif ($action === 'logout') {
        // Destroy Session
        session_unset();
        session_destroy();
        
        // Redirect ke Login
        header('Location: ../views/login.php');
        exit;
    }
} else {
    // Jika akses langsung GET ke file ini (misal logout via link)
    if (isset($_GET['action']) && $_GET['action'] === 'logout') {
        session_unset();
        session_destroy();
        header('Location: ../views/login.php');
        exit;
    }
}

$db->close();
?>
