<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Login - School Management System' ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.27/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Toastr -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-dark: #3730a3;
            --secondary-color: #f8fafc;
            --accent-color: #06b6d4;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --border-color: #e2e8f0;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            width: 100%;
            max-width: 1000px;
            min-height: 600px;
            display: flex;
        }
        
        .login-left {
            flex: 1;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .login-left::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>') repeat;
            animation: float 20s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        
        .login-left-content {
            position: relative;
            z-index: 2;
        }
        
        .school-logo {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            backdrop-filter: blur(10px);
        }
        
        .school-logo i {
            font-size: 40px;
        }
        
        .login-left h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            line-height: 1.2;
        }
        
        .login-left p {
            font-size: 1.1rem;
            opacity: 0.9;
            line-height: 1.6;
        }
        
        .login-right {
            flex: 1;
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .login-form-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .login-form-header h2 {
            color: var(--text-primary);
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .login-form-header p {
            color: var(--text-secondary);
            font-size: 1rem;
        }
        
        .form-floating {
            margin-bottom: 20px;
        }
        
        .form-floating .form-control {
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 20px 16px 8px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #fafbfc;
        }
        
        .form-floating .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
            background: white;
        }
        
        .form-floating label {
            color: var(--text-secondary);
            font-weight: 500;
        }
        
        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            z-index: 10;
        }
        
        .form-check {
            margin-bottom: 30px;
        }
        
        .form-check-input {
            border-radius: 6px;
            border: 2px solid var(--border-color);
        }
        
        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-login {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border: none;
            border-radius: 12px;
            padding: 16px;
            font-size: 16px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.3);
            color: white;
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .divider {
            text-align: center;
            margin: 30px 0;
            position: relative;
        }
        
        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: var(--border-color);
        }
        
        .divider span {
            background: white;
            padding: 0 20px;
            color: var(--text-secondary);
            font-size: 14px;
        }
        
        .btn-google {
            background: white;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 16px;
            font-size: 16px;
            font-weight: 600;
            color: var(--text-primary);
            transition: all 0.3s ease;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }
        
        .btn-google:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .google-icon {
            width: 20px;
            height: 20px;
        }
        
        .auth-links {
            text-align: center;
            margin-top: 30px;
        }
        
        .auth-links a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .auth-links a:hover {
            color: var(--primary-dark);
        }
        
        .alert {
            border-radius: 12px;
            border: none;
            margin-bottom: 20px;
        }
        
        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
        }
        
        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }
        
        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid transparent;
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .btn-2fa {
            background: linear-gradient(135deg, var(--accent-color) 0%, #0891b2 100%);
            border: none;
            border-radius: 12px;
            padding: 16px;
            font-size: 16px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            margin-bottom: 10px;
        }
        
        .btn-2fa:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(6, 182, 212, 0.3);
            color: white;
        }
        
        .btn-demo {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            color: white;
            border: none;
            padding: 16px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-bottom: 15px;
        }
        
        .btn-demo:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 107, 107, 0.3);
            color: white;
        }
        
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                max-width: 400px;
            }
            
            .login-left {
                padding: 40px 30px;
                min-height: 300px;
            }
            
            .login-left h1 {
                font-size: 2rem;
            }
            
            .login-right {
                padding: 40px 30px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Left Side - Branding -->
        <div class="login-left">
            <div class="login-left-content">
                <div class="school-logo">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h1>Welcome Back!</h1>
                <p>Access your school management dashboard with secure authentication and modern features.</p>
            </div>
        </div>
        
        <!-- Right Side - Login Form -->
        <div class="login-right">
            <div class="login-form-header">
                <h2>Sign In</h2>
                <p>Enter your credentials to access your account</p>
            </div>
            
            <!-- Display Messages -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <ul class="mb-0">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <!-- Login Form -->
            <form id="loginForm" action="<?= base_url('auth/authenticate') ?>" method="POST">
                <?= csrf_field() ?>
                
                <div class="form-floating">
                    <input type="email" class="form-control" id="email" name="email" 
                           placeholder="name@example.com" value="<?= old('email') ?>" required>
                    <label for="email"><i class="fas fa-envelope me-2"></i>Email Address</label>
                </div>
                
                <div class="form-floating position-relative">
                    <input type="password" class="form-control" id="password" name="password" 
                           placeholder="Password" required>
                    <label for="password"><i class="fas fa-lock me-2"></i>Password</label>
                    <button type="button" class="password-toggle" onclick="togglePassword()">
                        <i class="fas fa-eye" id="passwordIcon"></i>
                    </button>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember" name="remember">
                        <label class="form-check-label" for="remember">
                            Remember me
                        </label>
                    </div>
                    <a href="<?= base_url('auth/forgot-password') ?>" class="text-decoration-none">
                        Forgot Password?
                    </a>
                </div>
                
                <button type="submit" class="btn btn-login w-100">
                    <span class="btn-text">Sign In</span>
                    <div class="loading-spinner"></div>
                </button>
            </form>
            
            <!-- Divider -->
            <div class="divider">
                <span>or continue with</span>
            </div>
            
            <!-- Google OAuth -->
            <button type="button" class="btn btn-google w-100" onclick="loginWithGoogle()">
                <svg class="google-icon" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                Sign in with Google
            </button>
            
            <!-- 2FA Login -->
            <button type="button" class="btn btn-2fa w-100" onclick="show2FALogin()">
                <i class="fas fa-qrcode me-2"></i>
                Sign in with 2FA
            </button>
            
            <!-- Demo Skip Button -->
            <button type="button" class="btn btn-demo w-100" onclick="skipToDemo()">
                <i class="fas fa-rocket me-2"></i>
                Skip to Demo (Generate Sample Data)
            </button>
            
            <!-- Auth Links -->
            <div class="auth-links">
                <p class="mb-0">Don't have an account? 
                    <a href="<?= base_url('auth/register') ?>">Create Account</a>
                </p>
            </div>
        </div>
    </div>
    
    <!-- 2FA Modal -->
    <div class="modal fade" id="twoFactorModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title">
                        <i class="fas fa-shield-alt me-2 text-primary"></i>
                        Two-Factor Authentication
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="mb-4">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" 
                             style="width: 80px; height: 80px;">
                            <i class="fas fa-qrcode fa-2x text-primary"></i>
                        </div>
                    </div>
                    <h6 class="mb-3">Enter your 6-digit verification code</h6>
                    <p class="text-muted mb-4">Open your authenticator app and enter the 6-digit code</p>
                    
                    <form id="twoFactorForm">
                        <div class="row g-2 justify-content-center mb-4">
                            <div class="col-2">
                                <input type="text" class="form-control text-center" maxlength="1" 
                                       style="font-size: 1.5rem; font-weight: bold;" 
                                       oninput="moveToNext(this, 1)" id="code1">
                            </div>
                            <div class="col-2">
                                <input type="text" class="form-control text-center" maxlength="1" 
                                       style="font-size: 1.5rem; font-weight: bold;" 
                                       oninput="moveToNext(this, 2)" id="code2">
                            </div>
                            <div class="col-2">
                                <input type="text" class="form-control text-center" maxlength="1" 
                                       style="font-size: 1.5rem; font-weight: bold;" 
                                       oninput="moveToNext(this, 3)" id="code3">
                            </div>
                            <div class="col-2">
                                <input type="text" class="form-control text-center" maxlength="1" 
                                       style="font-size: 1.5rem; font-weight: bold;" 
                                       oninput="moveToNext(this, 4)" id="code4">
                            </div>
                            <div class="col-2">
                                <input type="text" class="form-control text-center" maxlength="1" 
                                       style="font-size: 1.5rem; font-weight: bold;" 
                                       oninput="moveToNext(this, 5)" id="code5">
                            </div>
                            <div class="col-2">
                                <input type="text" class="form-control text-center" maxlength="1" 
                                       style="font-size: 1.5rem; font-weight: bold;" 
                                       oninput="moveToNext(this, 6)" id="code6">
                            </div>
                        </div>
                        
                        <button type="button" class="btn btn-primary w-100" onclick="verify2FA()">
                            Verify Code
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.27/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    
    <script>
        // Configure Toastr
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "5000"
        };
        
        // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('passwordIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('fa-eye');
                passwordIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('fa-eye-slash');
                passwordIcon.classList.add('fa-eye');
            }
        }
        
        // Handle form submission
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            const btnText = submitBtn.querySelector('.btn-text');
            const spinner = submitBtn.querySelector('.loading-spinner');
            
            btnText.style.display = 'none';
            spinner.style.display = 'inline-block';
            submitBtn.disabled = true;
        });
        
        // Google OAuth login
        function loginWithGoogle() {
            toastr.info('Redirecting to Google OAuth...');
            // Implement Google OAuth redirect
            window.location.href = '<?= base_url("auth/google") ?>';
        }
        
        // Show 2FA login modal
        function show2FALogin() {
            const modal = new bootstrap.Modal(document.getElementById('twoFactorModal'));
            modal.show();
        }
        
        // Skip to demo function
        function skipToDemo() {
            // Show loading state
            const btn = event.target;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Generating Demo Data...';
            btn.disabled = true;
            
            // Redirect to demo authentication
            window.location.href = '<?= base_url("auth/demo") ?>';
        }
        
        // Move to next input in 2FA code
        function moveToNext(current, nextIndex) {
            if (current.value.length === 1 && nextIndex <= 6) {
                const nextInput = document.getElementById('code' + nextIndex);
                if (nextInput) {
                    nextInput.focus();
                }
            }
            
            // Auto-submit when all 6 digits are entered
            const allInputs = document.querySelectorAll('#twoFactorForm input');
            const allFilled = Array.from(allInputs).every(input => input.value.length === 1);
            if (allFilled) {
                setTimeout(() => verify2FA(), 500);
            }
        }
        
        // Verify 2FA code
        function verify2FA() {
            const inputs = document.querySelectorAll('#twoFactorForm input');
            const code = Array.from(inputs).map(input => input.value).join('');
            
            if (code.length !== 6) {
                toastr.error('Please enter all 6 digits');
                return;
            }
            
            // Show loading
            Swal.fire({
                title: 'Verifying...',
                text: 'Please wait while we verify your code',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Simulate API call
            setTimeout(() => {
                // For demo purposes, accept any 6-digit code
                if (code === '123456') {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Authentication successful',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = '<?= base_url("dashboard") ?>';
                    });
                } else {
                    Swal.fire({
                        title: 'Invalid Code',
                        text: 'Please check your authenticator app and try again',
                        icon: 'error'
                    });
                    
                    // Clear inputs
                    inputs.forEach(input => input.value = '');
                    inputs[0].focus();
                }
            }, 2000);
        }
        
        // Handle keyboard navigation in 2FA inputs
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('#twoFactorForm input');
            
            inputs.forEach((input, index) => {
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && this.value === '' && index > 0) {
                        inputs[index - 1].focus();
                    }
                });
                
                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const pastedData = e.clipboardData.getData('text');
                    if (pastedData.length === 6 && /^\d{6}$/.test(pastedData)) {
                        inputs.forEach((inp, i) => {
                            inp.value = pastedData[i] || '';
                        });
                        verify2FA();
                    }
                });
            });
        });
        
        // Auto-focus first input when modal opens
        document.getElementById('twoFactorModal').addEventListener('shown.bs.modal', function() {
            document.getElementById('code1').focus();
        });
    </script>
</body>
</html>