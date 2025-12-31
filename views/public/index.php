<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Darah - Selamatkan Nyawa, Donorkan Darahmu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        :root {
            --primary: #e53935;
            --primary-dark: #c62828;
            --secondary: #f5f5f5;
            --dark: #333;
            --light: #fff;
            --gray: #777;
            --green: #4CAF50;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            line-height: 1.6;
            color: var(--dark);
        }

        /* Header & Navbar */
        header {
            background-color: var(--light);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary);
        }

        .logo i {
            font-size: 2.2rem;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 30px;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--dark);
            font-weight: 600;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: var(--primary);
        }

        .cta-button {
            background-color: var(--primary);
            color: white;
            padding: 10px 25px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s;
        }

        .cta-button:hover {
            background-color: var(--primary-dark);
        }

        .mobile-menu {
            display: none;
            font-size: 1.5rem;
            cursor: pointer;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1615461066841-6116e61058f4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80');
            background-size: cover;
            background-position: center;
            color: var(--light);
            padding: 180px 0 120px;
            text-align: center;
        }

        .hero h1 {
            font-size: 3.2rem;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .hero p {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto 30px;
            opacity: 0.9;
        }

        .hero-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 30px;
        }

        .btn-secondary {
            background-color: transparent;
            border: 2px solid var(--light);
            color: var(--light);
        }

        .btn-secondary:hover {
            background-color: rgba(255,255,255,0.1);
        }

        /* Stats Section */
        .stats {
            background-color: var(--primary);
            color: white;
            padding: 80px 0;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            text-align: center;
        }

        .stat-item {
            padding: 20px;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .stat-label {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        /* Features Section */
        .features {
            padding: 100px 0;
            background-color: var(--secondary);
        }

        .section-title {
            text-align: center;
            margin-bottom: 60px;
        }

        .section-title h2 {
            font-size: 2.5rem;
            color: var(--dark);
            margin-bottom: 15px;
        }

        .section-title p {
            color: var(--gray);
            max-width: 700px;
            margin: 0 auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .feature-card {
            background-color: white;
            border-radius: 10px;
            padding: 40px 30px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }

        .feature-icon {
            background-color: rgba(229, 57, 53, 0.1);
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
        }

        .feature-icon i {
            font-size: 2.5rem;
            color: var(--primary);
        }

        .feature-card h3 {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: var(--dark);
        }

        /* Donation Process */
        .process {
            padding: 100px 0;
        }

        .process-steps {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 30px;
            margin-top: 50px;
        }

        .step {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            width: 250px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            position: relative;
        }

        .step-number {
            background-color: var(--primary);
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0 auto 20px;
        }

        /* CTA Section */
        .cta-section {
            background-color: var(--primary);
            color: white;
            padding: 100px 0;
            text-align: center;
        }

        .cta-section h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        .cta-section p {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto 40px;
            opacity: 0.9;
        }

        /* Eligibility */
        .eligibility {
            padding: 100px 0;
            background-color: var(--secondary);
        }

        .eligibility-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }

        .eligibility-card {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .eligibility-card h3 {
            color: var(--primary);
            margin-bottom: 20px;
            font-size: 1.5rem;
        }

        .eligibility-list {
            list-style-type: none;
        }

        .eligibility-list li {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
        }

        .eligibility-list li:last-child {
            border-bottom: none;
        }

        .eligibility-list i {
            color: var(--green);
            margin-right: 10px;
            font-size: 1.2rem;
        }

        /* Footer */
        footer {
            background-color: var(--dark);
            color: white;
            padding: 80px 0 30px;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-bottom: 50px;
        }

        .footer-column h3 {
            font-size: 1.5rem;
            margin-bottom: 25px;
            color: white;
        }

        .footer-column p, .footer-column a {
            color: #ccc;
            margin-bottom: 15px;
            display: block;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-column a:hover {
            color: white;
        }

        .social-icons {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .social-icons a {
            background-color: rgba(255,255,255,0.1);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .social-icons a:hover {
            background-color: var(--primary);
        }

        .copyright {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid rgba(255,255,255,0.1);
            color: #aaa;
            font-size: 0.9rem;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .nav-links {
                gap: 20px;
            }
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 15px 0;
            }
            
            .nav-links {
                display: none;
                position: absolute;
                top: 70px;
                left: 0;
                width: 100%;
                background-color: white;
                flex-direction: column;
                padding: 20px;
                box-shadow: 0 10px 10px rgba(0,0,0,0.1);
            }
            
            .nav-links.active {
                display: flex;
            }
            
            .mobile-menu {
                display: block;
            }
            
            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .hero {
                padding: 150px 0 80px;
            }
            
            .hero h1 {
                font-size: 2rem;
            }
            
            .stats-container {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .features, .process, .eligibility, .cta-section {
                padding: 70px 0;
            }
        }

        @media (max-width: 576px) {
            .stats-container {
                grid-template-columns: 1fr;
            }
            
            .step {
                width: 100%;
            }
        }
    </style>
    <style>
        /* Form Specific Styles */
        .register-container { 
            display: flex; 
            flex-wrap: wrap; 
            gap: 30px;
            padding: 50px 0;
            background: #fff;
        }
        
        .request-section { flex: 1.2; min-width: 400px; padding: 30px; border-right: 1px solid #eee; background-color: #f9f9f9; }
        .recommendation-section { flex: 0.8; min-width: 300px; padding: 30px; background-color: white; }
        
        .form-title { color: #c62828; font-size: 22px; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #ffebee; }
        
        /* Form Elements */
        .form-group { margin-bottom: 25px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: #555; }
        .required::after { content: " *"; color: #c62828; }
        
        .form-row { display: flex; flex-wrap: wrap; gap: 20px; }
        .form-col { flex: 1; min-width: 200px; }
        
        .input-group { margin-bottom: 20px; }
        .input-group label { margin-bottom: 8px; }
        
        .input-field, .select-field { width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 16px; transition: border-color 0.3s; background-color: white; }
        .input-field:focus, .select-field:focus { border-color: #c62828; outline: none; box-shadow: 0 0 0 3px rgba(198, 40, 40, 0.1); }
        
        /* Radio Buttons */
        .radio-group { display: flex; flex-wrap: wrap; gap: 20px; margin-top: 8px; }
        .radio-option { display: flex; align-items: center; gap: 8px; cursor: pointer; }
        .radio-button { width: 20px; height: 20px; border-radius: 50%; border: 2px solid #c62828; display: flex; align-items: center; justify-content: center; position: relative; }
        .radio-button.selected::after { content: ''; width: 10px; height: 10px; border-radius: 50%; background-color: #c62828; position: absolute; }
        
        /* Action Button */
        .search-button { background-color: #c62828; color: white; border: none; padding: 14px 30px; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px; width: 100%; transition: background-color 0.3s; margin-top: 20px; }
        .search-button:hover { background-color: #b71c1c; }
        
        /* Form Info */
        .form-info { background-color: #e8f5e9; border: 1px solid #c8e6c9; border-radius: 8px; padding: 15px; margin-top: 25px; color: #2e7d32; font-size: 14px; display: flex; gap: 10px; align-items: center; }
        
        /* Result Box */
        .result-card { margin-top: 20px; padding: 25px; border-radius: 12px; opacity: 0; transform: translateY(10px); transition: all 0.5s ease; }
        .result-card.active { opacity: 1; transform: translateY(0); }
        .result-card.success { background-color: #e8f5e9; border-left: 5px solid #2e7d32; }
        .result-card.warning { background-color: #fff8e1; border-left: 5px solid #ffca28; }
        .result-card.error { background-color: #ffebee; border-left: 5px solid #c62828; }
        
        .result-header { font-size: 20px; font-weight: 700; margin-bottom: 15px; display: flex; align-items: center; gap: 10px; }
        .result-detail { margin-bottom: 10px; font-size: 15px; }
        .result-detail strong { color: #555; }
        
        .eligibility-badge { display: inline-block; padding: 5px 12px; border-radius: 20px; font-weight: 700; font-size: 14px; color: white; margin-left: 5px; }
        .badge-success { background-color: #2e7d32; }
        .badge-danger { background-color: #c62828; }
        
        .info-card { background-color: #e3f2fd; border-left: 5px solid #1976d2; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        .info-title { font-weight: 700; color: #1565c0; margin-bottom: 10px; font-size: 16px; }
        .info-text { font-size: 14px; color: #555; margin-bottom: 8px; }
        
        @media (max-width: 900px) {
            .main-content { flex-direction: column; }
            .request-section { border-right: none; border-bottom: 1px solid #eee; }
        }

        /* Tambahan CSS untuk bagian kontak */
    .contact-card {
    background: linear-gradient(135deg, #e8f5e9 0%, #f1f8e9 100%);
    border-left: 4px solid #2e7d32;
    padding: 20px;
    border-radius: 10px;
    margin: 15px 0;
    box-shadow: 0 3px 10px rgba(46, 125, 50, 0.1);
}

    .contact-header {
    font-weight: 700;
    color: #2e7d32;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 16px;
}

    .contact-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
    margin-bottom: 15px;
}

    .contact-item {
    background: white;
    padding: 12px;
    border-radius: 6px;
    border: 1px solid #c8e6c9;
}

    .contact-label {
    font-weight: 600;
    color: #555;
    font-size: 13px;
    margin-bottom: 5px;
    display: block;
}

    .contact-value {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
}

    .action-buttons {
    display: flex;
    gap: 10px;
    margin-top: 15px;
}

    .btn-whatsapp {
    background: #25D366;
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-weight: 600;
    flex: 1;
    transition: background 0.3s;
}

    .btn-whatsapp:hover {
    background: #1da851;
}

    .btn-map {
    background: #4285F4;
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-weight: 600;
    flex: 1;
    transition: background 0.3s;
}

    .btn-map:hover {
    background: #3367d6;
}

    .btn-copy {
    background: #757575;
    color: white;
    border: none;
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 11px;
    cursor: pointer;
    margin-left: auto;
    transition: background 0.3s;
}

    .btn-copy:hover {
    background: #616161;
}
    /* Layout & Container */
    .register-container { 
        display: flex; 
        flex-wrap: wrap; 
        gap: 30px;
    }
    
    .request-section { 
        flex: 1.2; 
        min-width: 400px; 
        padding: 30px; 
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .recommendation-section { 
        flex: 0.8; 
        min-width: 300px; 
        padding: 30px; 
        background-color: white;
        border-radius: 10px;
        height: fit-content;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .section-title { 
        color: #c62828; 
        font-size: 22px; 
        margin-bottom: 20px; 
        padding-bottom: 10px; 
        border-bottom: 2px solid #ffebee; 
    }
    
    /* Form Elements */
    .form-group { margin-bottom: 25px; }
    label { display: block; margin-bottom: 8px; font-weight: 600; color: #555; }
    .required::after { content: " *"; color: #c62828; }
    
    .form-row { display: flex; flex-wrap: wrap; gap: 20px; }
    .form-col { flex: 1; min-width: 200px; }
    
    .input-group { margin-bottom: 20px; }
    
    .input-field, .select-field { 
        width: 100%; 
        padding: 12px 15px; 
        border: 1px solid #ddd; 
        border-radius: 8px; 
        font-size: 16px; 
        transition: border-color 0.3s; 
        background-color: white; 
    }
    .input-field:focus, .select-field:focus { 
        border-color: #c62828; 
        outline: none; 
        box-shadow: 0 0 0 3px rgba(198, 40, 40, 0.1); 
    }
    
    /* Radio Buttons */
    .radio-group { display: flex; flex-wrap: wrap; gap: 20px; margin-top: 8px; }
    .radio-option { display: flex; align-items: center; gap: 8px; cursor: pointer; }
    .radio-button { 
        width: 20px; 
        height: 20px; 
        border-radius: 50%; 
        border: 2px solid #c62828; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        position: relative; 
    }
    .radio-button.selected::after { 
        content: ''; 
        width: 10px; 
        height: 10px; 
        border-radius: 50%; 
        background-color: #c62828; 
        position: absolute; 
    }
    
    /* Action Button */
    .search-button { 
        background-color: #c62828; 
        color: white; 
        border: none; 
        padding: 14px 30px; 
        border-radius: 8px; 
        font-size: 16px; 
        font-weight: 600; 
        cursor: pointer; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        gap: 10px; 
        width: 100%; 
        transition: background-color 0.3s; 
        margin-top: 20px; 
    }
    .search-button:hover { background-color: #b71c1c; }
    
    /* Form Info */
    .form-info { 
        background-color: #e8f5e9; 
        border: 1px solid #c8e6c9; 
        border-radius: 8px; 
        padding: 15px; 
        margin-top: 25px; 
        color: #2e7d32; 
        font-size: 14px; 
        display: flex; 
        gap: 10px; 
        align-items: center; 
    }
    
    /* Result Box */
    .result-card { margin-top: 20px; padding: 25px; border-radius: 12px; opacity: 0; transform: translateY(10px); transition: all 0.5s ease; }
    .result-card.active { opacity: 1; transform: translateY(0); }
    .result-card.success { background-color: #e8f5e9; border-left: 5px solid #2e7d32; }
    .result-card.warning { background-color: #fff8e1; border-left: 5px solid #ffca28; }
    .result-card.error { background-color: #ffebee; border-left: 5px solid #c62828; }
    
    .result-header { font-size: 20px; font-weight: 700; margin-bottom: 15px; display: flex; align-items: center; gap: 10px; }
    .result-detail { margin-bottom: 10px; font-size: 15px; }
    .result-detail strong { color: #555; }
    
    .info-card { background-color: #e3f2fd; border-left: 5px solid #1976d2; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
    .info-title { font-weight: 700; color: #1565c0; margin-bottom: 10px; font-size: 16px; }
    .info-text { font-size: 14px; color: #555; margin-bottom: 8px; }
    
    @media (max-width: 900px) {
        .register-container { flex-direction: column; }
    }
</style>
</head>
<body>
    <!-- Header & Navigation -->
    <header>
        <div class="container">
            <nav class="navbar">
                <div class="logo">
                    <i class="fas fa-tint"></i>
                    <span>DonorDarah Kota Depok</span>
                </div>
                
                <ul class="nav-links">
                    <li><a href="#home">Beranda</a></li>
                    <li><a href="#about">Tentang</a></li>
                    <li><a href="#process">Proses</a></li>
                    <li><a href="#eligibility">Syarat</a></li>
                    <!-- <li><a href="#locations">Lokasi</a></li> -->
                    <li><a href="#contact">Kontak</a></li>
                </ul>
                
                <a href="#screen" class="cta-button">Donor Sekarang</a>
                
                <div class="mobile-menu">
                    <i class="fas fa-bars"></i>
                </div>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="container">
            <h1>Setetes Darahmu, Nyawa bagi Sesama</h1>
            <p>Bergabunglah dengan jutaan pendonor darah di Indonesia. Donor darah adalah tindakan sederhana yang dapat menyelamatkan nyawa. Daftar sekarang dan jadilah pahlawan bagi mereka yang membutuhkan.</p>
            
            <div class="hero-buttons">
                <a href="#screen" class="cta-button">Donor Sekarang</a>
                <a href="#about" class="cta-button btn-secondary">Pelajari Lebih Lanjut</a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="about">
        <div class="container">
            <div class="section-title">
                <h2>Mengapa Donor Darah Penting?</h2>
                <p>Donor darah tidak hanya menyelamatkan nyawa orang lain, tetapi juga memberikan manfaat kesehatan bagi pendonor</p>
            </div>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-heartbeat"></i>
                    </div>
                    <h3>Menjaga Kesehatan Jantung</h3>
                    <p>Donor darah secara teratur dapat membantu mengurangi kekentalan darah dan menurunkan risiko penyakit jantung.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Solidaritas Sosial</h3>
                    <p>Donor darah adalah wujud nyata kepedulian terhadap sesama dan membangun komunitas yang saling membantu.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-stethoscope"></i>
                    </div>
                    <h3>Pemeriksaan Kesehatan Gratis</h3>
                    <p>Setiap kali donor, Anda akan mendapatkan pemeriksaan kesehatan dasar secara gratis seperti tekanan darah, hemoglobin, dan deteksi penyakit menular.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Process Section -->
    <section class="process" id="process">
        <div class="container">
            <div class="section-title">
                <h2>Proses Donor Darah</h2>
                <p>Donor darah adalah proses yang aman, cepat, dan nyaman. Hanya membutuhkan sekitar 30-45 menit dari pendaftaran hingga selesai.</p>
            </div>
            
            <div class="process-steps">
                <div class="step">
                    <div class="step-number">1</div>
                    <h3>Pendaftaran</h3>
                    <p>Isi formulir pendaftaran dan bawa identitas diri yang masih berlaku.</p>
                </div>
                
                <div class="step">
                    <div class="step-number">2</div>
                    <h3>Pemeriksaan</h3>
                    <p>Pemeriksaan kesehatan dasar dan wawancara singkat tentang riwayat kesehatan.</p>
                </div>
                
                <div class="step">
                    <div class="step-number">3</div>
                    <h3>Donor Darah</h3>
                    <p>Proses pengambilan darah yang hanya membutuhkan sekitar 10 menit.</p>
                </div>
                
                <div class="step">
                    <div class="step-number">4</div>
                    <h3>Pemulihan</h3>
                    <p>Istirahat dan menikmati makanan kecil yang disediakan selama 10-15 menit.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Eligibility Section -->
    <section class="eligibility" id="eligibility">
        <div class="container">
            <div class="section-title">
                <h2>Syarat Donor Darah</h2>
                <p>Pastikan Anda memenuhi persyaratan sebelum mendonorkan darah</p>
            </div>
            
            <div class="eligibility-grid">
                <div class="eligibility-card">
                    <h3>Syarat Umum</h3>
                    <ul class="eligibility-list">
                        <li><i class="fas fa-check-circle"></i> Usia 17-60 tahun</li>
                        <li><i class="fas fa-check-circle"></i> Berat badan minimal 45 kg</li>
                        <li><i class="fas fa-check-circle"></i> Tekanan darah normal</li>
                        <li><i class="fas fa-check-circle"></i> Kadar hemoglobin ≥ 12.5 g/dL</li>
                        <li><i class="fas fa-check-circle"></i> Jarak donor minimal 3 bulan</li>
                    </ul>
                </div>
                
                <div class="eligibility-card">
                    <h3>Kondisi Tidak Diperbolehkan</h3>
                    <ul class="eligibility-list">
                        <li><i class="fas fa-times-circle" style="color: #e53935;"></i> Sedang sakit atau demam</li>
                        <li><i class="fas fa-times-circle" style="color: #e53935;"></i> Mengidap penyakit menular</li>
                        <li><i class="fas fa-times-circle" style="color: #e53935;"></i> Mengonsumsi antibiotik</li>
                        <li><i class="fas fa-times-circle" style="color: #e53935;"></i> Baru saja menindik atau menato tubuh</li>
                        <li><i class="fas fa-times-circle" style="color: #e53935;"></i> Ibu hamil atau menyusui</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <div class="container" id = "screen">
        <div class="register-container">
            <!-- Left Column: Form -->
            <div class="request-section">
                <h2 class="form-title"><i class="fas fa-user-plus"></i> Form Pendaftaran Donor</h2>
        
        <form id="donorForm">
            <!-- Data Pribadi -->
            <h3 style="margin-bottom: 15px; font-size: 18px; color: #444;">Data Pribadi</h3>
            <div class="form-row">
                <div class="form-col">
                    <div class="input-group">
                        <label class="required" for="name">Nama Lengkap</label>
                        <input type="text" id="name" class="input-field" placeholder="Nama sesuai KTP" required>
                    </div>
                </div>
                <div class="form-col">
                        <div class="input-group">
                        <label class="required" for="city">Kota Domisili</label>
                        <input type="text" id="city" class="input-field" placeholder="Depok" required>
                    </div>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-col">
                    <div class="input-group">
                        <label class="required" for="email">Email</label>
                        <input type="email" id="email" class="input-field" placeholder="email@contoh.com" required>
                    </div>
                </div>
                <div class="form-col">
                    <div class="input-group">
                        <label class="required" for="contact_number">No. Telepon / WhatsApp</label>
                        <input type="tel" id="contact_number" class="input-field" placeholder="+62" required>
                    </div>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-col">
                    <div class="input-group">
                        <label class="required" for="gender">Jenis Kelamin</label>
                        <select id="gender" class="select-field" required>
                            <option value="">Pilih</option>
                            <option value="L">Laki - Laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                </div>
                    <div class="form-col">
                        <div class="input-group">
                        <label for="jarak_ke_rs_km">Jarak ke PMI (km)</label>
                        <input type="number" id="jarak_ke_rs_km" class="input-field" min="0" step="0.1" value="10.0" placeholder="Estimasi jarak">
                        </div>
                    </div>
            </div>

            <hr style="margin: 25px 0; border: 0; border-top: 1px solid #eee;">
            
            <!-- Data Medis -->
            <h3 style="margin-bottom: 15px; font-size: 18px; color: #444;">Data Medis & Kondisi Fisik</h3>
            
            <div class="form-row">
                <div class="form-col">
                    <div class="input-group">
                        <label class="required" for="blood_group">Golongan Darah</label>
                        <select id="blood_group" class="select-field" required>
                            <option value="">Pilih</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="AB">AB</option>
                            <option value="O" selected>O</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-col">
                    <div class="input-group">
                        <label class="required">Rhesus</label>
                        <div class="radio-group" id="rhesusGroup">
                            <div class="radio-option" onclick="selectRadio('rhesus', 'positif', this)">
                                <div class="radio-button selected"></div>
                                <span>Positif (+)</span>
                            </div>
                            <div class="radio-option" onclick="selectRadio('rhesus', 'negatif', this)">
                                <div class="radio-button"></div>
                                <span>Negatif (-)</span>
                            </div>
                        </div>
                        <input type="hidden" id="rhesusValue" value="+">
                    </div>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-col">
                    <div class="input-group">
                        <label class="required" for="usia">Usia (Tahun)</label>
                        <input type="number" id="usia" class="input-field" placeholder="17" required>
                        <small style="color: #666; font-size: 12px;">17 - 60 tahun</small>
                    </div>
                </div>
                <div class="form-col">
                    <div class="input-group">
                        <label class="required" for="berat_badan">Berat Badan (kg)</label>
                        <input type="number" id="berat_badan" class="input-field" placeholder="60" required>
                        <small style="color: #666; font-size: 12px;">Min: 45 kg</small>
                    </div>
                </div>
                <div class="form-col">
                    <div class="input-group">
                        <label for="hb_level">HB Level (g/dL) <small style="font-weight: normal; color: #999;">(Opsional jika belum tahu)</small></label>
                        <input type="number" id="hb_level" class="input-field" step="0.1" placeholder="Kosongkan jika tidak tahu">
                        <small style="color: #666; font-size: 12px;">Normal: 12.5 - 17.0</small>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                    <label class="required" for="riwayat_penyakit">Riwayat Penyakit</label>
                    <select id="riwayat_penyakit" class="select-field">
                    <option value="Tidak" selected>Tidak ada (Sehat)</option>
                    <option value="Hipertensi">Hipertensi (Darah Tinggi)</option>
                    <option value="Diabetes">Diabetes</option>
                    <option value="Jantung">Penyakit Jantung</option>
                    <option value="Hepatitis">Hepatitis B / C</option>
                    <option value="Anemia">Anemia Kronis</option>
                </select>
            </div>
            
            <div class="form-row">
                <div class="form-col">
                        <div class="input-group">
                        <label for="number_of_donation">Jumlah Donor Sebelumnya</label>
                        <input type="number" id="number_of_donation" class="input-field" min="0"  placeholder="0" required>
                        </div>
                </div>
                <div class="form-col">
                        <div class="input-group">
                        <label for="months_since_first_donation">Terakhir Donor (bulan)</label>
                        <input type="number" id="months_since_first_donation" class="input-field" min="0" placeholder="0" required >
                        <!-- <small style="color: #666; font-size: 12px;">Min: 2 bulan</small> -->
                        </div>
                </div>
            </div>
            
            <div class="form-group">
                <label>Ketersediaan untuk Dihubungi</label>
                    <div class="radio-group" id="availabilityGroup">
                    <div class="radio-option" onclick="selectRadio('availability', 'Yes', this)">
                        <div class="radio-button selected"></div>
                        <span>Ya, Bersedia</span>
                    </div>
                    <div class="radio-option" onclick="selectRadio('availability', 'No', this)">
                        <div class="radio-button"></div>
                        <span>Tidak Sementara Ini</span>
                    </div>
                </div>
                <input type="hidden" id="availabilityValue" value="Yes">
            </div>

            <button type="submit" class="search-button" id="submitBtn">
                <i class="fas fa-paper-plane"></i> Daftar & Cek Kelayakan
            </button>
            
                <div class="form-info">
                <i class="fas fa-info-circle"></i>
                <span>Data Anda akan diproses oleh sistem Machine Learning kami untuk memverifikasi kelayakan donor secara otomatis.</span>
            </div>
            
            <!-- Auto-fill button for demo -->
            <button type="button" onclick="fillTestData()" style="margin-top: 15px; background: none; border: 1px dashed #ccc; color: #666; width: 100%; padding: 10px; cursor: pointer; border-radius: 8px;">
                <i class="fas fa-magic"></i> Isi Data Contoh (Demo)
            </button>
        </form>
    </div>
    
    <!-- Right Column: Results & Info -->
    <div class="recommendation-section">
        <h2 class="form-title"><i class="fas fa-clipboard-check"></i> Hasil Prediksi</h2>
        
        <div class="info-card">
            <div class="info-title"><i class="fas fa-info-circle"></i> Syarat Utama Donor Darah</div>
            <ul style="list-style: none; padding: 0; margin-top: 10px;">
                <li style="margin-bottom: 8px; display: flex; align-items: start; gap: 8px;">
                    <i class="fas fa-check-circle" style="color: #2e7d32; margin-top: 3px;"></i> 
                    <span>Usia 17 - 60 tahun</span>
                </li>
                <li style="margin-bottom: 8px; display: flex; align-items: start; gap: 8px;">
                    <i class="fas fa-check-circle" style="color: #2e7d32; margin-top: 3px;"></i> 
                    <span>Berat badan minimal 45 kg</span>
                </li>
                <li style="margin-bottom: 8px; display: flex; align-items: start; gap: 8px;">
                    <i class="fas fa-check-circle" style="color: #2e7d32; margin-top: 3px;"></i> 
                    <span>Kadar Hemoglobin 12.5 - 17.0 g/dL</span>
                </li>
                <li style="margin-bottom: 8px; display: flex; align-items: start; gap: 8px; margin-left: 20px; font-size: 0.9em; color: #555;">
                    <!-- <i class="fas fa-venus" style="color: #e91e63; margin-top: 3px;"></i>  -->
                    <span>Perempuan: Min 12.5 g/dL</span>
                </li>
                <li style="margin-bottom: 8px; display: flex; align-items: start; gap: 8px; margin-left: 20px; font-size: 0.9em; color: #555;">
                    <!-- <i class="fas fa-mars" style="color: #1976d2; margin-top: 3px;"></i>  -->
                    <span>Laki-laki: Min 13.5 g/dL</span>
                </li>
                <li style="margin-bottom: 8px; display: flex; align-items: start; gap: 8px;">
                    <i class="fas fa-check-circle" style="color: #2e7d32; margin-top: 3px;"></i> 
                    <span>Sehat jasmani dan rohani</span>
                </li>
            </ul>
        </div>
        
        <div id="resultContainer">
            <div style="text-align: center; color: #999; padding: 40px 0;">
                <i class="fas fa-file-medical-alt" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;"></i>
                <p>Silakan isi form di samping untuk melihat hasil prediksi kelayakan Anda.</p>
            </div>
        </div>
    </div>
</div>

<script>
    // Handle Radio Button Selection UI
    function selectRadio(groupName, value, element) {
        // Update UI
        const group = element.closest('.radio-group');
        group.querySelectorAll('.radio-button').forEach(btn => btn.classList.remove('selected'));
        element.querySelector('.radio-button').classList.add('selected');
        
        // Update Hidden Value
        if(groupName === 'rhesus') {
                document.getElementById('rhesusValue').value = (value === 'positif') ? '+' : '-';
        } else if (groupName === 'availability') {
                document.getElementById('availabilityValue').value = value;
        }
    }

    document.getElementById('donorForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const resultContainer = document.getElementById('resultContainer');
        const submitBtn = document.getElementById('submitBtn');
        const originalBtnText = submitBtn.innerHTML;
        
        // Loading State
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
        submitBtn.disabled = true;
        resultContainer.innerHTML = '<div style="text-align: center; padding: 30px;"><i class="fas fa-spinner fa-spin fa-3x" style="color: #c62828;"></i><p style="margin-top: 15px;">Menganalisis data medis...</p></div>';
        
        // Kumpulkan data dari form
        const rhesusVal = document.getElementById('rhesusValue').value;
        const donorData = {
            name: document.getElementById('name').value,
            email: document.getElementById('email').value,
            contact_number: document.getElementById('contact_number').value,
            gender: document.getElementById('gender').value,
            city: document.getElementById('city').value,
            blood_group: document.getElementById('blood_group').value + rhesusVal, // Concat Blood Group + Rhesus
            usia: parseInt(document.getElementById('usia').value),
            berat_badan: parseInt(document.getElementById('berat_badan').value),
            usia: parseInt(document.getElementById('usia').value),
            berat_badan: parseInt(document.getElementById('berat_badan').value),
            hb_level: document.getElementById('hb_level').value ? parseFloat(document.getElementById('hb_level').value) : null,
            riwayat_penyakit: document.getElementById('riwayat_penyakit').value,
            riwayat_penyakit: document.getElementById('riwayat_penyakit').value,
            number_of_donation: parseInt(document.getElementById('number_of_donation').value) || 0,
            months_since_first_donation: parseInt(document.getElementById('months_since_first_donation').value) || 0,
            availability: document.getElementById('availabilityValue').value,
            jarak_ke_rs_km: parseFloat(document.getElementById('jarak_ke_rs_km').value) || 10.0
        };
        
        try {
            // Kirim ke API - Adjust path to go up one level then to api
            // Kirim ke API - Adjust path to go up two levels for views/public/
            const response = await fetch('../../api/add_donor.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(donorData)
            });
            
            const result = await response.json();
            
            // Tampilkan Hasil
            if (result.success) {
                const status = parseInt(result.status_layak);
                
                let resultHTML = '';
                
                if (status === 1) {
                    // LAYAK (Green)
                    resultHTML = `
                        <div class="result-card active eligible">
                            <div class="result-header" style="color: #2e7d32;">
                                <i class="fas fa-check-circle"></i> LAYAK DONOR
                            </div>
                            <div style="margin-bottom: 15px;">
                                <p style="color: #2e7d32; font-size: 15px; font-weight: 500; margin-bottom: 10px;">
                                    Data medis menunjukkan bahwa Anda dalam kondisi prima untuk melakukan donor darah.
                                </p>
                            </div>
                            
                            <div style="background-color: #f0f7ff; border-radius: 8px; padding: 15px; border-left: 4px solid #2e7d32; font-size: 13.5px; color: #444;">
                                <strong style="display:block; margin-bottom:8px; color:#2e7d32;"><i class="fas fa-info-circle"></i> Catatan Penting:</strong>
                                <ul style="padding-left: 15px; margin: 0; line-height: 1.5;">
                                    <li style="margin-bottom: 5px;">Hasil di atas merupakan prediksi awal berdasarkan data yang Anda masukkan.</li>
                                    <li style="margin-bottom: 5px;">Kelayakan donor sesungguhnya akan diputuskan oleh Dokter/Petugas Medis melalui pemeriksaan fisik di tempat.</li>
                                    <li style="margin-bottom: 5px;">Pastikan Anda dalam kondisi prima (tidur minimal 5 jam dan sudah makan) sebelum mendonor.</li>
                                    <li>Harap membawa identitas diri (KTP, SIM, Paspor).</li>
                                </ul>
                            </div>
                        </div>
                    `;
                } else if (status === 2) {
                    // DITANGGUHKAN (Yellow)
                    resultHTML = `
                        <div class="result-card active" style="border-left: 5px solid #ff9800; background: #fff3e0;">
                            <div class="result-header" style="color: #ef6c00;">
                                <i class="fas fa-exclamation-triangle"></i> DITANGGUHKAN
                            </div>
                            
                            <div style="margin-bottom: 15px;">
                                <p style="color: #ef6c00; font-size: 15px; font-weight: 500;">
                                    Mohon maaf, Anda belum bisa donor saat ini tapi bisa mencoba lagi nanti.
                                </p>
                            </div>
                            
                            <div style="background: rgba(255, 255, 255, 0.6); padding: 10px; border-radius: 5px;">
                                <strong style="color: #d84315;">Alasan Penangguhan:</strong><br>
                                ${result.warning || 'Kondisi kesehatan perlu perbaikan sedikit.'}
                                ${result.suggestion ? `<ul style="margin-left: 20px; margin-top: 5px; font-size: 14px;">${result.suggestion.map(s => `<li>${s}</li>`).join('')}</ul>` : ''}
                            </div>
                            
                            <div style="margin-top: 15px; font-size: 13px; color: #555;">
                                <i class="fas fa-info-circle"></i> Silakan perbaiki nutrisi (zat besi/vitamin) dan kembali setelah kondisi membaik.
                            </div>
                        </div>
                    `;
                } else if (status === 3) {
                     // BUTUH CEK KESEHATAN (Blue/Info)
                    resultHTML = `
                        <div class="result-card active eligible" style="border-left: 5px solid #0288d1; background: #e1f5fe;">
                            <div class="result-header" style="color: #0277bd;">
                                <i class="fas fa-user-md"></i> PERLU PEMERIKSAAN
                            </div>
                            
                            <div style="margin-bottom: 15px;">
                                <p style="color: #01579b; font-size: 15px; font-weight: 500;">
                                    Silahkan datang ke PMI untuk melakukan pemeriksaan kesehatan.
                                </p>
                            </div>
                            
                            <div style="background: rgba(255, 255, 255, 0.6); padding: 10px; border-radius: 5px; font-size: 13.5px; color: #444;">
                                <strong style="color: #0277bd;">Info:</strong><br>
                                Data dasar Anda memenuhi syarat, namun karena kadar Hemoglobin belum diketahui, kami perlu memastikannya melalui pemeriksaan langsung.
                            </div>
                        </div>
                    `;
                } else {
                    // TIDAK LAYAK (Red)
                    resultHTML = `
                        <div class="result-card active not-eligible">
                            <div class="result-header" style="color: #c62828;">
                                <i class="fas fa-times-circle"></i> DI TOLAK
                            </div>
                            
                            <hr style="margin: 15px 0; border: 0; border-top: 1px dashed #ccc;">
                            
                            <div style="background: rgba(255, 255, 255, 0.6); padding: 10px; border-radius: 5px;">
                                <strong style="color: #c62828;">Alasan Medis:</strong><br>
                                ${result.warning || 'Statistik kesehatan belum memenuhi syarat.'}
                                ${result.suggestion ? `<ul style="margin-left: 20px; margin-top: 5px; font-size: 14px;">${result.suggestion.map(s => `<li>${s}</li>`).join('')}</ul>` : ''}
                            </div>
                        </div>
                    `;
                }
                
                resultContainer.innerHTML = resultHTML;
            } else {
                    resultContainer.innerHTML = `
                    <div class="result-card active error">
                        <div class="result-header" style="color: #c62828;">
                            <i class="fas fa-times-circle"></i> GAGAL MENDAFTAR
                        </div>
                        <p>${result.message}</p>
                        ${result.error ? `<small>${result.error}</small>` : ''}
                    </div>
                `;
            }
            
        } catch (error) {
            resultContainer.innerHTML = `
                <div class="result-card active error">
                    <div class="result-header" style="color: #c62828;">
                        <i class="fas fa-network-wired"></i> KONEKSI ERROR
                    </div>
                    <p>Pastikan server backend berjalan.</p>
                    <small>${error.message}</small>
                </div>
            `;
        } finally {
            submitBtn.innerHTML = originalBtnText;
            submitBtn.disabled = false;
        }
    });
    
    // Auto-fill for testing
    function fillTestData() {
        document.getElementById('name').value = 'Budi Santoso';
        document.getElementById('email').value = 'budi@example.com';
        document.getElementById('contact_number').value = '08123456789';
        document.getElementById('city').value = 'Jakarta';
        document.getElementById('blood_group').value = 'O';
        document.getElementById('usia').value = '28';
        document.getElementById('berat_badan').value = '65';
        document.getElementById('hb_level').value = '14.5';
        
        // Trigger radio update manually if needed or just let visual stay default
    }
</script>

    </div></div> <!-- Close register-container and its wrapper container -->

    <!-- Footer -->
    <footer id="contact">
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>DonorDarah</h3>
                    <p>Platform donasi darah terpercaya yang menghubungkan pendonor dengan mereka yang membutuhkan di seluruh Indonesia.</p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                
                <div class="footer-column">
                    <h3>Tautan Cepat</h3>
                    <a href="#home">Beranda</a>
                    <a href="#about">Tentang Donor Darah</a>
                    <a href="#process">Proses Donor</a>
                    <a href="#eligibility">Syarat Donor</a>
                    <a href="#locations">Lokasi Donor</a>
                </div>
                
                <div class="footer-column">
                    <h3>Kontak Kami</h3>
                    <p><i class="fas fa-map-marker-alt"></i> Jl. Kesehatan No. 123, Jakarta</p>
                    <p><i class="fas fa-phone"></i> (021) 1234-5678</p>
                    <p><i class="fas fa-envelope"></i> info@donordarah</p>
                    <p><i class="fas fa-clock"></i> Buka: Senin - Minggu, 08:00 - 16:00</p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.querySelector('.mobile-menu').addEventListener('click', function() {
            document.querySelector('.nav-links').classList.toggle('active');
        });
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if(targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if(targetElement) {
                    // Close mobile menu if open
                    document.querySelector('.nav-links').classList.remove('active');
                    
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });
        
        // Animate stats counter
        function animateStats() {
            const statNumbers = document.querySelectorAll('.stat-number');
            const speed = 200; // Lower = faster
            
            statNumbers.forEach(stat => {
                const target = parseInt(stat.textContent);
                const increment = target / speed;
                let current = 0;
                
                const updateNumber = () => {
                    if(current < target) {
                        current += increment;
                        stat.textContent = Math.ceil(current).toLocaleString();
                        setTimeout(updateNumber, 1);
                    } else {
                        stat.textContent = target.toLocaleString();
                    }
                };
                
                updateNumber();
            });
        }
        
        // Trigger stats animation when in view
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if(entry.isIntersecting) {
                    animateStats();
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        
        observer.observe(document.querySelector('.stats'));
    </script>
</body>
</html>