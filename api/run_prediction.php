<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Format data untuk Python script
    $python_input = json_encode($data);
    
    // Jalankan Python script
    $command = PYTHON_PATH . " " . ML_SCRIPT_PATH . " 2>&1";
    $descriptorspec = [
        0 => ["pipe", "r"], // stdin
        1 => ["pipe", "w"], // stdout
        2 => ["pipe", "w"]  // stderr
    ];
    
    $process = proc_open($command, $descriptorspec, $pipes);
    
    if (is_resource($process)) {
        // Kirim data ke stdin Python
        fwrite($pipes[0], $python_input);
        fclose($pipes[0]);
        
        // Baca output dari Python
        $output = stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        
        $error = stream_get_contents($pipes[2]);
        fclose($pipes[2]);
        
        $return_value = proc_close($process);
        
        // Parse hasil
        $result = json_decode($output, true);
        
        if ($result) {
            echo json_encode([
                'success' => true,
                'prediction' => $result
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Prediction failed',
                'error' => $error
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to start Python process'
        ]);
    }
}
?>