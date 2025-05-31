<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2FA Setup - Smart School Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .setup-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .setup-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .setup-header h2 {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .setup-header p {
            opacity: 0.9;
            font-size: 0.9rem;
        }

        .setup-body {
            padding: 2rem;
        }

        .step {
            margin-bottom: 2rem;
            padding: 1.5rem;
            border: 2px solid #e1e5e9;
            border-radius: 15px;
            transition: all 0.3s ease;
        }

        .step:hover {
            border-color: #667eea;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.1);
        }

        .step-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 50%;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .step h4 {
            color: #333;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .qr-container {
            text-align: center;
            padding: 2rem;
            background: #f8f9fa;
            border-radius: 15px;
            margin: 1rem 0;
        }

        .qr-code {
            display: inline-block;
            padding: 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
        }

        .secret-key {
            background: #e9ecef;
            padding: 1rem;
            border-radius: 10px;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            word-break: break-all;
            margin: 1rem 0;
            border: 1px solid #dee2e6;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 500;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .form-control {
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn-verify {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-verify:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(40, 167, 69, 0.3);
        }

        .btn-back {
            background: #6c757d;
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-back:hover {
            background: #5a6268;
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
        }

        .alert {
            border-radius: 10px;
            border: none;
            margin-bottom: 1rem;
        }

        .app-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin: 1rem 0;
        }

        .app-item {
            text-align: center;
            padding: 1rem;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .app-item:hover {
            border-color: #667eea;
            transform: translateY(-2px);
        }

        .app-item i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            color: #667eea;
        }

        .copy-btn {
            background: #17a2b8;
            border: none;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.8rem;
            margin-left: 10px;
            cursor: pointer;
        }

        .copy-btn:hover {
            background: #138496;
        }
    </style>
</head>
<body>
    <div class="setup-container">
        <div class="setup-header">
            <i class="fas fa-shield-alt fa-3x mb-3"></i>
            <h2>Two-Factor Authentication Setup</h2>
            <p>Secure your account with an additional layer of protection</p>
        </div>
        
        <div class="setup-body">
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <!-- Step 1: Download Authenticator App -->
            <div class="step">
                <div class="step-number">1</div>
                <h4>Download an Authenticator App</h4>
                <p class="text-muted mb-3">Choose and install one of these authenticator apps on your mobile device:</p>
                
                <div class="app-list">
                    <div class="app-item">
                        <i class="fab fa-google"></i>
                        <div><strong>Google Authenticator</strong></div>
                        <small class="text-muted">iOS & Android</small>
                    </div>
                    <div class="app-item">
                        <i class="fas fa-mobile-alt"></i>
                        <div><strong>Microsoft Authenticator</strong></div>
                        <small class="text-muted">iOS & Android</small>
                    </div>
                    <div class="app-item">
                        <i class="fas fa-key"></i>
                        <div><strong>Authy</strong></div>
                        <small class="text-muted">iOS & Android</small>
                    </div>
                </div>
            </div>

            <!-- Step 2: Scan QR Code -->
            <div class="step">
                <div class="step-number">2</div>
                <h4>Scan the QR Code</h4>
                <p class="text-muted mb-3">Open your authenticator app and scan this QR code:</p>
                
                <div class="qr-container">
                    <div class="qr-code">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=<?= urlencode($qrCodeUrl) ?>" 
                             alt="QR Code" width="200" height="200">
                    </div>
                    <p class="text-muted">Can't scan? Enter this key manually:</p>
                    <div class="secret-key">
                        <span id="secretKey"><?= $secretKey ?></span>
                        <button type="button" class="copy-btn" onclick="copySecret()">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>
                </div>
            </div>

            <!-- Step 3: Verify Setup -->
            <div class="step">
                <div class="step-number">3</div>
                <h4>Verify Setup</h4>
                <p class="text-muted mb-3">Enter the 6-digit code from your authenticator app to complete setup:</p>
                
                <form action="<?= base_url('qrcode/verify') ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="form-group">
                        <label class="form-label">Verification Code</label>
                        <input type="text" class="form-control" name="verification_code" 
                               placeholder="Enter 6-digit code" maxlength="6" required 
                               pattern="[0-9]{6}" autocomplete="off">
                        <small class="text-muted">Enter the current code shown in your authenticator app</small>
                    </div>

                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-verify">
                            <i class="fas fa-check"></i> Verify & Enable 2FA
                        </button>
                        <a href="<?= base_url('dashboard') ?>" class="btn btn-back">
                            <i class="fas fa-arrow-left"></i> Back to Dashboard
                        </a>
                    </div>
                </form>
            </div>

            <!-- Security Notice -->
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                <strong>Important:</strong> Keep your authenticator app safe and backed up. 
                If you lose access to your device, you may need administrator assistance to regain access to your account.
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
    
    <script>
        // Copy secret key to clipboard
        function copySecret() {
            const secretKey = document.getElementById('secretKey').textContent;
            navigator.clipboard.writeText(secretKey).then(function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Copied!',
                    text: 'Secret key copied to clipboard',
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            }).catch(function(err) {
                console.error('Could not copy text: ', err);
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = secretKey;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                
                Swal.fire({
                    icon: 'success',
                    title: 'Copied!',
                    text: 'Secret key copied to clipboard',
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            });
        }

        // Auto-format verification code input
        document.querySelector('input[name="verification_code"]').addEventListener('input', function(e) {
            // Only allow numbers
            this.value = this.value.replace(/[^0-9]/g, '');
            
            // Limit to 6 digits
            if (this.value.length > 6) {
                this.value = this.value.slice(0, 6);
            }
        });

        // Auto-submit when 6 digits are entered
        document.querySelector('input[name="verification_code"]').addEventListener('input', function(e) {
            if (this.value.length === 6) {
                // Optional: Auto-submit after a short delay
                setTimeout(() => {
                    if (this.value.length === 6) {
                        this.form.submit();
                    }
                }, 500);
            }
        });
    </script>
</body>
</html>