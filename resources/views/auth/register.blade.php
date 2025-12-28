<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - File & Driver Management</title>
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
            background: linear-gradient(135deg, #FFD 0%, #ffeedc 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .register-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            display: flex;
            max-width: 600px;
            width: 100%;
            animation: fadeIn 0.5s ease-out;
            max-height: 90vh;
        }
        .register-form {
            flex: 1.2;
            padding: 50px;
            background: #ffffff;
            overflow-y: auto;
            max-height: 100vh;
        }

        .side-content {
            max-width: 400px;
            max-height: 80vh;
        }
        .register-side {
            flex: 0.8;
            background: #f8cf16;
            color: black;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        .register-side::before {
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
        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
        }
        .form-row .form-group {
            flex: 1;
            margin-bottom: 0;
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
        .password-strength {
            margin-top: 10px;
        }
        .strength-meter {
            height: 6px;
            background: #e1e5e9;
            border-radius: 3px;
            overflow: hidden;
            margin-bottom: 8px;
        }
        .strength-fill {
            height: 100%;
            width: 0%;
            border-radius: 3px;
            transition: width 0.3s, background 0.3s;
        }
        .strength-text {
            font-size: 12px;
            color: #666;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .requirements {
            margin-top: 10px;
            padding: 15px;
            background: #f8fafc;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }
        .requirements h4 {
            color: #555;
            margin-bottom: 10px;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .requirements ul {
            list-style: none;
            padding-left: 0;
        }
        .requirements li {
            font-size: 12px;
            color: #666;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
        }
        .requirements li i {
            margin-right: 8px;
            font-size: 10px;
            width: 16px;
        }
        .requirements li.valid {
            color: #10b981;
        }
        .requirements li.invalid {
            color: #ef4444;
        }
        .btn-register {
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
        .btn-register::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        .btn-register:hover::before {
            left: 100%;
        }
        .btn-register:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }
        .btn-register:active {
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
        .benefits {
            list-style: none;
            margin-bottom: 40px;
        }
        .benefits li {
            margin-bottom: 18px;
            display: flex;
            align-items: flex-start;
            font-size: 16px;
        }
        .benefits i {
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
            flex-shrink: 0;
            margin-top: 2px;
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
        .alert-danger ul {
            list-style: none;
            padding-left: 0;
            margin: 10px 0 0 0;
        }
        .alert-danger li {
            font-size: 13px;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
        }
        .alert-danger li i {
            margin-right: 8px;
            font-size: 12px;
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
            .register-container {
                flex-direction: column;
                max-width: 700px;
                max-height: 90vh;
            }
            .register-side {
                order: -1;
                padding: 40px;
            }
            .register-form {
                padding: 40px;
                overflow-y: visible;
                max-height: none;
            }
            .form-row {
                flex-direction: column;
                gap: 25px;
            }
        }
        @media (max-width: 480px) {
            .register-container {
                border-radius: 10px;
            }
            .register-side, .register-form {
                padding: 30px 25px;
            }
            .logo h1 {
                font-size: 24px;
            }
            .side-content h2 {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-form">
            <a href="{{ route('home') }}"><i class="fas fa-arrow-left"></i></a>
            <div class="logo">
                <img src="/asset/logo.jpg" alt="PATCORP logo" srcset="" class="logo-img" style="width: 90px; height: 90px; border-radius: 50%; object-fit: cover;">
                <h1>Create Driver Account</h1>
                <p>Join our platform to manage your files and driver information</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> Please fix the following errors:
                    <ul>
                        @foreach($errors->all() as $error)
                            <li><i class="fas fa-times"></i> {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <div class="input-with-icon">
                            <i class="fas fa-user"></i>
                            <input type="text" id="first_name" name="first_name" required 
                                   placeholder="Enter first name"
                                   value="{{ old('first_name') }}"
                                   pattern="[A-Za-z\s]+"
                                   title="Only letters and spaces allowed">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <div class="input-with-icon">
                            <i class="fas fa-user"></i>
                            <input type="text" id="last_name" name="last_name" required 
                                   placeholder="Enter last name"
                                   value="{{ old('last_name') }}"
                                   pattern="[A-Za-z\s]+"
                                   title="Only letters and spaces allowed">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-with-icon">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" required 
                               placeholder="Enter your email address"
                               value="{{ old('email') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <div class="input-with-icon">
                        <i class="fas fa-phone"></i>
                        <input type="tel" id="phone" name="phone" 
                               placeholder="Enter phone number (optional)"
                               value="{{ old('phone') }}"
                               pattern="[\d\s\-\+\(\)]+"
                               title="Only numbers, spaces, +, -, and () allowed">
                    </div>
                </div>

                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" id="username" name="username" required 
                               placeholder="Choose a username (letters, numbers, underscore)"
                               value="{{ old('username') }}"
                               pattern="[A-Za-z0-9_]+"
                               title="Only letters, numbers, and underscore allowed">
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" required 
                               placeholder="Create a secure password">
                        <button type="button" class="password-toggle" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    
                    <div class="password-strength">
                        <div class="strength-meter">
                            <div class="strength-fill" id="strengthFill"></div>
                        </div>
                        <div class="strength-text">
                            <span id="strengthText">Password strength</span>
                            <span id="strengthScore">0/4</span>
                        </div>
                    </div>
                    
                    <div class="requirements">
                        <h4><i class="fas fa-list-check"></i> Password Requirements</h4>
                        <ul>
                            <li id="reqLength" class="invalid">
                                <i class="fas fa-circle"></i> At least 8 characters
                            </li>
                            <li id="reqLowercase" class="invalid">
                                <i class="fas fa-circle"></i> One lowercase letter
                            </li>
                            <li id="reqUppercase" class="invalid">
                                <i class="fas fa-circle"></i> One uppercase letter
                            </li>
                            <li id="reqNumber" class="invalid">
                                <i class="fas fa-circle"></i> One number
                            </li>
                            <li id="reqSpecial" class="invalid">
                                <i class="fas fa-circle"></i> One special character
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password_confirmation" name="password_confirmation" required 
                               placeholder="Confirm your password">
                        <button type="button" class="password-toggle" id="toggleConfirmPassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div id="confirmError" style="color: #ef4444; font-size: 12px; margin-top: 5px; display: none;">
                        <i class="fas fa-times"></i> Passwords do not match
                    </div>
                </div>

                <button type="submit" class="btn-register" id="registerBtn">
                    <i class="fas fa-user-plus"></i> Create Driver Account
                </button>

                <div class="links">
                    <p>Already have an account? <a href="{{ route('login') }}">
                        <i class="fas fa-sign-in-alt"></i> Sign In Here
                    </a></p>
                </div>
            </form>
        </div>

        {{-- <div class="register-side">
            <div class="side-content">
                <h2>Drive With Us</h2>
                <p>Join thousands of drivers who trust our platform for efficient management and seamless operations.</p>
                
                <ul class="benefits">
                    <li>
                        <i class="fas fa-file-contract"></i>
                        <div>
                            <strong>Document Management</strong>
                            <p style="font-size: 14px; opacity: 0.8;">Upload and organize all your important documents in one place.</p>
                        </div>
                    </li>
                    <li>
                        <i class="fas fa-chart-line"></i>
                        <div>
                            <strong>Payment Tracking</strong>
                            <p style="font-size: 14px; opacity: 0.8;">Monitor your payments and earnings in real-time with detailed reports.</p>
                        </div>
                    </li>
                    <li>
                        <i class="fas fa-shield-alt"></i>
                        <div>
                            <strong>Secure Platform</strong>
                            <p style="font-size: 14px; opacity: 0.8;">Your data is protected with enterprise-grade security and encryption.</p>
                        </div>
                    </li>
                    <li>
                        <i class="fas fa-clock"></i>
                        <div>
                            <strong>24/7 Access</strong>
                            <p style="font-size: 14px; opacity: 0.8;">Access your account anytime, anywhere from any device.</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div> --}}
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password toggle functionality
            const togglePassword = document.getElementById('togglePassword');
            const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('password_confirmation');
            
            function setupPasswordToggle(button, input) {
                if (button && input) {
                    button.addEventListener('click', function() {
                        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                        input.setAttribute('type', type);
                        this.innerHTML = type === 'password' ? 
                            '<i class="fas fa-eye"></i>' : 
                            '<i class="fas fa-eye-slash"></i>';
                    });
                }
            }
            
            setupPasswordToggle(togglePassword, passwordInput);
            setupPasswordToggle(toggleConfirmPassword, confirmPasswordInput);
            
            // Password strength checker
            const strengthFill = document.getElementById('strengthFill');
            const strengthText = document.getElementById('strengthText');
            const strengthScore = document.getElementById('strengthScore');
            const passwordRegex = {
                length: /.{8,}/,
                lowercase: /[a-z]/,
                uppercase: /[A-Z]/,
                number: /[0-9]/,
                special: /[@$!%*?&]/
            };
            
            const requirementElements = {
                length: document.getElementById('reqLength'),
                lowercase: document.getElementById('reqLowercase'),
                uppercase: document.getElementById('reqUppercase'),
                number: document.getElementById('reqNumber'),
                special: document.getElementById('reqSpecial')
            };
            
            if (passwordInput) {
                passwordInput.addEventListener('input', function() {
                    const password = this.value;
                    let score = 0;
                    let validRequirements = 0;
                    
                    // Check each requirement
                    Object.keys(passwordRegex).forEach(req => {
                        const isValid = passwordRegex[req].test(password);
                        const element = requirementElements[req];
                        
                        if (element) {
                            if (isValid) {
                                element.className = 'valid';
                                element.querySelector('i').className = 'fas fa-check';
                                element.querySelector('i').style.color = '#10b981';
                                score++;
                                validRequirements++;
                            } else {
                                element.className = 'invalid';
                                element.querySelector('i').className = 'fas fa-circle';
                                element.querySelector('i').style.color = '#94a3b8';
                            }
                        }
                    });
                    
                    // Update strength meter
                    const percentage = (score / 5) * 100;
                    if (strengthFill) {
                        strengthFill.style.width = percentage + '%';
                        
                        // Set color based on strength
                        if (score <= 1) {
                            strengthFill.style.background = '#ef4444';
                            strengthText.textContent = 'Weak';
                            strengthText.style.color = '#ef4444';
                        } else if (score <= 3) {
                            strengthFill.style.background = '#f59e0b';
                            strengthText.textContent = 'Fair';
                            strengthText.style.color = '#f59e0b';
                        } else if (score === 4) {
                            strengthFill.style.background = '#10b981';
                            strengthText.textContent = 'Good';
                            strengthText.style.color = '#10b981';
                        } else {
                            strengthFill.style.background = '#3b82f6';
                            strengthText.textContent = 'Strong';
                            strengthText.style.color = '#3b82f6';
                        }
                    }
                    
                    if (strengthScore) {
                        strengthScore.textContent = `${score}/5`;
                        strengthScore.style.color = strengthText.style.color;
                    }
                    
                    // Validate password match
                    validatePasswordMatch();
                });
            }
            
            // Password confirmation validation
            function validatePasswordMatch() {
                const password = passwordInput.value;
                const confirmPassword = confirmPasswordInput.value;
                const confirmError = document.getElementById('confirmError');
                
                if (confirmError) {
                    if (confirmPassword && password !== confirmPassword) {
                        confirmError.style.display = 'block';
                        confirmPasswordInput.style.borderColor = '#ef4444';
                        return false;
                    } else {
                        confirmError.style.display = 'none';
                        confirmPasswordInput.style.borderColor = confirmPassword ? '#10b981' : '#e1e5e9';
                        return true;
                    }
                }
                return true;
            }
            
            if (confirmPasswordInput) {
                confirmPasswordInput.addEventListener('input', validatePasswordMatch);
            }
            
            // Form validation
            const registerForm = document.getElementById('registerForm');
            const registerBtn = document.getElementById('registerBtn');
            
            if (registerForm) {
                registerForm.addEventListener('submit', function(e) {
                    let isValid = true;
                    
                    // Validate all fields
                    const fields = [
                        'first_name', 'last_name', 'email', 'username', 'password', 'password_confirmation'
                    ];
                    
                    fields.forEach(field => {
                        const input = document.getElementById(field);
                        if (input && !input.value.trim()) {
                            isValid = false;
                            input.style.borderColor = '#ef4444';
                        }
                    });
                    
                    // Validate password match
                    if (!validatePasswordMatch()) {
                        isValid = false;
                    }
                    
                    // Validate password strength
                    const password = passwordInput.value;
                    const requirements = [
                        passwordRegex.length.test(password),
                        passwordRegex.lowercase.test(password),
                        passwordRegex.uppercase.test(password),
                        passwordRegex.number.test(password),
                        passwordRegex.special.test(password)
                    ];
                    
                    const validCount = requirements.filter(Boolean).length;
                    if (validCount < 4) {
                        isValid = false;
                        showError('Password must meet at least 4 out of 5 requirements');
                    }
                    
                    if (!isValid) {
                        e.preventDefault();
                        if (!document.querySelector('.alert.alert-danger')) {
                            showError('Please fix all validation errors before submitting');
                        }
                    } else {
                        // Show loading state
                        if (registerBtn) {
                            registerBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating Account...';
                            registerBtn.disabled = true;
                        }
                    }
                });
            }
            
            // Real-time input validation
            function setupRealTimeValidation(inputId, regex, errorMessage) {
                const input = document.getElementById(inputId);
                if (input) {
                    input.addEventListener('blur', function() {
                        if (this.value && !regex.test(this.value)) {
                            this.style.borderColor = '#ef4444';
                            showFieldError(this, errorMessage);
                        } else if (this.value) {
                            this.style.borderColor = '#10b981';
                            clearFieldError(this);
                        }
                    });
                    
                    input.addEventListener('input', function() {
                        if (this.value && regex.test(this.value)) {
                            this.style.borderColor = '#10b981';
                            clearFieldError(this);
                        } else {
                            this.style.borderColor = '#e1e5e9';
                            clearFieldError(this);
                        }
                    });
                }
            }
            
            // Setup validations
            setupRealTimeValidation('first_name', /^[A-Za-z\s]+$/, 'Only letters and spaces allowed');
            setupRealTimeValidation('last_name', /^[A-Za-z\s]+$/, 'Only letters and spaces allowed');
            setupRealTimeValidation('username', /^[A-Za-z0-9_]+$/, 'Only letters, numbers, and underscore allowed');
            setupRealTimeValidation('phone', /^[\d\s\-\+\(\)]*$/, 'Invalid phone number format');
            
            function showFieldError(input, message) {
                let errorDiv = input.parentNode.querySelector('.field-error');
                if (!errorDiv) {
                    errorDiv = document.createElement('div');
                    errorDiv.className = 'field-error';
                    errorDiv.style.color = '#ef4444';
                    errorDiv.style.fontSize = '12px';
                    errorDiv.style.marginTop = '5px';
                    input.parentNode.appendChild(errorDiv);
                }
                errorDiv.innerHTML = `<i class="fas fa-times"></i> ${message}`;
            }
            
            function clearFieldError(input) {
                const errorDiv = input.parentNode.querySelector('.field-error');
                if (errorDiv) {
                    errorDiv.remove();
                }
            }
            
            function showError(message) {
                // Create or update error message
                let errorDiv = document.querySelector('.alert.alert-danger');
                if (!errorDiv) {
                    errorDiv = document.createElement('div');
                    errorDiv.className = 'alert alert-danger';
                    const form = document.querySelector('.register-form');
                    form.insertBefore(errorDiv, form.firstChild.nextSibling);
                }
                errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
                
                // Enable button if disabled
                if (registerBtn) {
                    registerBtn.innerHTML = '<i class="fas fa-user-plus"></i> Create Driver Account';
                    registerBtn.disabled = false;
                }
            }
        });
    </script>
</body>
</html>