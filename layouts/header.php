<?php
session_start();

// Security Check: Redirect to Login if session not active
// Check if file is not login.php to prevent infinite loop
$current_page = basename($_SERVER['PHP_SELF']);
if (!isset($_SESSION['user_id']) && $current_page !== 'login.php') {
    header('Location: ../login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Rekomendasi Donor Darah</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #f8f9fa; color: #333; line-height: 1.6; padding: 20px; display: flex; flex-direction: column; min-height: 100vh; }
        
        .container { max-width: 1200px; margin: 0 auto; width: 100%; background-color: white; border-radius: 15px; box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08); overflow: hidden; flex: 1; display: flex; flex-direction: column; }
        header { background-color: #c62828; color: white; padding: 25px 30px; border-bottom: 5px solid #b71c1c; display: flex; justify-content: space-between; align-items: center; }
        .header-left h1 { font-size: 28px; font-weight: 700; margin-bottom: 8px; }
        .subtitle { font-size: 16px; opacity: 0.9; }
        
        .main-content { padding: 30px; flex: 1; }
        
        /* Navbar */
        .navbar { display: flex; gap: 15px; }
        .nav-link { 
            color: white; 
            text-decoration: none; 
            font-weight: 600; 
            padding: 8px 15px; 
            border-radius: 5px; 
            transition: background 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .nav-link:hover { background-color: rgba(255,255,255,0.2); }
        .nav-link.active { background-color: white; color: #c62828; }
        
        .logout-btn {
            background-color: rgba(0,0,0,0.2);
            border: 1px solid rgba(255,255,255,0.3);
        }
        .logout-btn:hover {
            background-color: #b71c1c;
            border-color: #b71c1c;
        }
        
        @media (max-width: 768px) {
            header { flex-direction: column; text-align: center; gap: 20px; }
            .navbar { flex-wrap: wrap; justify-content: center; }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="header-left">
                <h1><i class="fas fa-droplet"></i> BloodMatch AI</h1>
                <p class="subtitle">Sistem Rekomendasi & Prediksi Kelayakan Donor Cerdas</p>
            </div>
            
            <nav class="navbar">
                <?php 
                // Since this header is included by files in views/backoffice/, links are relative to THAT directory.
                ?>
                <a href="index.php" class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">
                    Beranda
                </a>
                <a href="req_donorform.php" class="nav-link <?php echo ($current_page == 'req_donorform.php' || $current_page == 'search_results.php') ? 'active' : ''; ?>">
                   Cari Donor
                </a>
                <a href="request_list.php" class="nav-link <?php echo ($current_page == 'request_list.php') ? 'active' : ''; ?>">
                    Permintaan Darah
                </a>
                <a href="input_donor.php" class="nav-link <?php echo ($current_page == 'input_donor.php') ? 'active' : ''; ?>">
                    Input Donor
                </a>
                <!-- Logout Link: relative to views/backoffice/ is ../../api/auth.php -->
                <a href="../../api/auth.php?action=logout" class="nav-link logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Keluar
                </a>
            </nav>
        </header>

        <main class="main-content">
