<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Rekomendasi Donor Darah</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
            padding: 20px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        header {
            background-color: #c62828;
            color: white;
            padding: 0;
            border-bottom: 5px solid #b71c1c;
        }
        
        .header-content {
            padding: 25px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .nav-bar {
            background-color: #b71c1c;
            padding: 0 30px;
            display: flex;
            gap: 20px;
        }
        
        .nav-link {
            color: white;
            text-decoration: none;
            padding: 15px 5px;
            font-weight: 500;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
            font-size: 15px;
        }
        
        .nav-link:hover, .nav-link.active {
            border-bottom-color: white;
            background-color: rgba(255,255,255,0.1);
        }
        
        h1 {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .subtitle {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .logout-btn {
            background-color: white;
            color: #c62828;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .logout-btn:hover {
            background-color: #ffebee;
        }

        .main-content {
            padding: 30px;
            flex: 1;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="header-content">
                <div>
                    <h1><i class="fas fa-tint"></i> Sistem Rekomendasi Donor Darah</h1>
                    <div class="subtitle">Sistem Cerdas Pencarian & Rekomendasi Donor Darah</div>
                </div>
                <div>
                     <!-- Path Handling Logic: Check if we are in a subdirectory -->
                     <?php 
                        $isInSubfolder = strpos($_SERVER['PHP_SELF'], '/views/') !== false;
                        $rootPath = $isInSubfolder ? '../' : ''; 
                     ?>
                     <!-- Authentication removed as per request -->
                </div>
            </div>
            
            <nav class="nav-bar">
                <?php 
                   $current_page = basename($_SERVER['PHP_SELF']);
                ?>
                <a href="<?php echo $rootPath; ?>index.php" class="nav-link <?php echo $current_page == 'index.php' ? 'active' : ''; ?>"><i class="fas fa-home"></i> Beranda</a>
                <a href="<?php echo $rootPath; ?>views/req_donorform.php" class="nav-link <?php echo $current_page == 'req_donorform.php' ? 'active' : ''; ?>"><i class="fas fa-search"></i> Cari Donor</a>
                <a href="<?php echo $rootPath; ?>views/input_donor.php" class="nav-link <?php echo $current_page == 'input_donor.php' ? 'active' : ''; ?>"><i class="fas fa-user-plus"></i> Daftar Jadi Donor</a>
            </nav>
        </header>
        <div class="main-content">
