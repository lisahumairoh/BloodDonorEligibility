<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Backoffice - BloodMatch AI</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { 
            background: linear-gradient(135deg, #c62828, #b71c1c); 
            height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            padding: 20px;
        }
        
        .login-card {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        
        .logo {
            font-size: 48px;
            color: #c62828;
            margin-bottom: 20px;
        }
        
        .title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 5px;
            color: #333;
        }
        
        .subtitle {
            color: #777;
            font-size: 14px;
            margin-bottom: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
            color: #555;
        }
        
        .input-box {
            position: relative;
        }
        
        .input-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }
        
        .input-field {
            width: 100%;
            padding: 12px 15px 12px 40px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s;
        }
        
        .input-field:focus {
            border-color: #c62828;
            outline: none;
            box-shadow: 0 0 0 3px rgba(198, 40, 40, 0.1);
        }
        
        .login-btn {
            background-color: #c62828;
            color: white;
            border: none;
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .login-btn:hover {
            background-color: #b71c1c;
        }
        
        .alert {
            padding: 12px;
            background-color: #ffebee;
            color: #c62828;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 20px;
            display: none;
            border: 1px solid #ffcdd2;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="logo">
            <i class="fas fa-droplet"></i>
        </div>
        <div class="title">Login Admin</div>
        <div class="subtitle">Silakan masuk</div>
        
        <div class="alert" id="alertBox"></div>
        
        <form id="loginForm">
            <input type="hidden" name="action" value="login">
            
            <div class="form-group">
                <label>Username</label>
                <div class="input-box">
                    <i class="fas fa-user"></i>
                    <input type="text" name="username" class="input-field" placeholder="Masukkan username" required>
                </div>
            </div>
            
            <div class="form-group">
                <label>Password</label>
                <div class="input-box">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" class="input-field" placeholder="Masukkan password" required>
                </div>
            </div>
            
            <button type="submit" class="login-btn" id="loginBtn">
                <i class="fas fa-sign-in-alt"></i> Masuk
            </button>
        </form>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const btn = document.getElementById('loginBtn');
            const alertBox = document.getElementById('alertBox');
            const originalText = btn.innerHTML;
            
            // Loading State
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
            btn.disabled = true;
            alertBox.style.display = 'none';
            
            const formData = new FormData(this);
            
            try {
                const response = await fetch('../api/auth.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    window.location.href = result.redirect;
                } else {
                    alertBox.textContent = result.message;
                    alertBox.style.display = 'block';
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }
                
            } catch (error) {
                alertBox.textContent = 'Terjadi kesalahan koneksi.';
                alertBox.style.display = 'block';
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        });
    </script>
</body>
</html>
