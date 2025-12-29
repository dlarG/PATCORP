<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - File & Driver Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #fdf8e5 0%, #ffeedc 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            display: flex;
            max-width: 1100px;
            width: 100%;
            animation: fadeIn 0.5s ease-out;
        }
        .login-form {
            flex: 1;
            padding: 50px;
            background: #ffffff;
        }
        .login-side {
            flex: 1;
            background: #f8cf16;
            color: black;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        .login-side::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: float 20s linear infinite;
            opacity: 0.3;
        }
        .logo {
            text-align: center;
            margin-bottom: 40px;
        }
        .logo i {
            font-size: 56px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 15px;
        }
        .logo h1 {
            color: #333;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        .logo p {
            color: #666;
            font-size: 14px;
            margin-top: 5px;
        }
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .input-with-icon {
            position: relative;
        }
        .input-with-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #667eea;
            font-size: 18px;
        }
        input {
            width: 100%;
            padding: 15px 15px 15px 50px;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            font-size: 16px;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
            background: #f8fafc;
        }
        input:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }
        .password-toggle {
            position: absolute;
            right: 50px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            font-size: 18px;
            transition: color 0.3s;
        }
        .password-toggle:hover {
            color: #667eea;
        }
        .btn-login {
            width: 100%;
            padding: 20px;
            background: linear-gradient(135deg, #FFD41D 0%, #FFA240 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
            letter-spacing: 0.5px;
            position: relative;
            overflow: hidden;
        }
        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        .btn-login:hover::before {
            left: 100%;
        }
        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }
        .btn-login:active {
            transform: translateY(-1px);
        }
        .links {
            text-align: center;
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px solid #e1e5e9;
        }
        .links p {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .links a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            display: inline-block;
        }
        .links a:hover {
            color: #764ba2;
            transform: translateX(5px);
        }
        .side-content h2 {
            font-size: 36px;
            margin-bottom: 20px;
            font-weight: 700;
            line-height: 1.2;
        }
        .side-content p {
            font-size: 17px;
            opacity: 0.95;
            margin-bottom: 35px;
            line-height: 1.6;
        }
        .features {
            list-style: none;
            margin-bottom: 40px;
        }
        .features li {
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            font-size: 16px;
        }
        .features i {
            margin-right: 15px;
            color: #D73535;
            font-size: 20px;
            background: rgba(215, 53, 53, 0.1);
            padding: 10px;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .demo-credentials {
            background: rgba(255,255,255,0.15);
            padding: 20px;
            border-radius: 10px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
        }
        .demo-credentials h3 {
            font-size: 16px;
            margin-bottom: 15px;
            opacity: 0.9;
        }
        .demo-credentials p {
            font-size: 14px;
            margin-bottom: 8px;
            opacity: 0.8;
            display: flex;
            align-items: center;
        }
        .demo-credentials i {
            margin-right: 10px;
            font-size: 12px;
        }
        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            border-left: 4px solid;
            animation: slideIn 0.3s ease-out;
        }
        .alert-danger {
            background-color: #fee;
            color: #c33;
            border-left-color: #c33;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes float {
            0% { transform: translate(0, 0) rotate(0deg); }
            100% { transform: translate(-50px, -50px) rotate(360deg); }
        }
        @media (max-width: 992px) {
            .login-container {
                flex-direction: column;
                max-width: 500px;
            }
            .login-side {
                order: 1;
                padding: 40px;
            }
            .login-form {
                padding: 40px;
            }
        }
        @media (max-width: 480px) {
            .login-container {
                border-radius: 10px;
            }
            .login-side, .login-form {
                padding: 30px 25px;
            }
            .logo h1 {
                font-size: 24px;
            }
            .side-content h2 {
                font-size: 28px;
            }

            .back-btn i {
                color: #667eea;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-form">
            <a href="{{route('home')}}" class="back-btn"><i class="fas fa-arrow-left" style="margin-bottom: 20px;"></i></a>
            <div class="logo">
                <img src="/asset/logo.jpg" alt="PATCORP Logo" class="logo-img" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover;">
                <p>Sign in to your account</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf
                <div class="form-group">
                    <label for="login">Username or Email</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" id="login" name="login" required 
                               placeholder="Enter username or email"
                               value="{{ old('login') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" required 
                               placeholder="Enter your password">
                        <button type="button" class="password-toggle" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-login" id="loginBtn">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </button>

                <div class="links">
                    <p>Don't have an account? <a href="{{ route('register') }}">
                         Create Account
                    </a></p>
                    <p><a href="#">
                        <i class="fas fa-question-circle"></i> Forgot Password?
                    </a></p>
                </div>
            </form>
        </div>

        <div class="login-side">
            <div class="side-content">
                <h2>Streamline Your Operations</h2>
                <p>Efficiently manage your files and driver information in one powerful platform designed for modern businesses.</p>
                
                <ul class="features">
                    <li><i class="fas fa-shield-alt"></i> Secure file management system</li>
                    <li><i class="fas fa-chart-line"></i> Advanced analytics</li>
                    <li><i class="fas fa-mobile-alt"></i> Mobile-responsive design</li>
                    <li><i class="fas fa-cloud-upload-alt"></i> Cloud-based storage</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            
            if (togglePassword) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    this.innerHTML = type === 'password' ? 
                        '<i class="fas fa-eye"></i>' : 
                        '<i class="fas fa-eye-slash"></i>';
                });
            }

            // Form validation
            const loginForm = document.getElementById('loginForm');
            const loginBtn = document.getElementById('loginBtn');
            
            if (loginForm) {
                loginForm.addEventListener('submit', function(e) {
                    const login = document.getElementById('login').value.trim();
                    const password = document.getElementById('password').value;
                    
                    if (!login) {
                        e.preventDefault();
                        showError('Please enter username or email');
                        return;
                    }
                    
                    if (!password) {
                        e.preventDefault();
                        showError('Please enter password');
                        return;
                    }
                    
                    // Show loading state
                    if (loginBtn) {
                        loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Signing In...';
                        loginBtn.disabled = true;
                    }
                });
            }
            
            function showError(message) {
                // Create or update error message
                let errorDiv = document.querySelector('.alert.alert-danger');
                if (!errorDiv) {
                    errorDiv = document.createElement('div');
                    errorDiv.className = 'alert alert-danger';
                    const form = document.querySelector('.login-form');
                    form.insertBefore(errorDiv, form.firstChild);
                }
                errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
                
                // Enable button if disabled
                if (loginBtn) {
                    loginBtn.innerHTML = '<i class="fas fa-sign-in-alt"></i> Sign In';
                    loginBtn.disabled = false;
                }
            }
        });
    </script>
</body>
</html>