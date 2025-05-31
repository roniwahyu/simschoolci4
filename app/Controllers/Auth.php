<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class Auth extends BaseController
{
    protected $userModel;
    protected $session;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session = \Config\Services::session();
    }
    
    public function index()
    {
        // Redirect to login if not authenticated
        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }
        
        return redirect()->to('/dashboard');
    }
    
    public function login()
    {
        // If already logged in, redirect to dashboard
        if ($this->session->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }
        
        $data = [
            'title' => 'Login - School Management System',
            'page' => 'login'
        ];
        
        return view('auth/login', $data);
    }
    
    public function authenticate()
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[6]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $remember = $this->request->getPost('remember');
        
        // Check user credentials
        $user = $this->userModel->where('email', $email)->first();
        
        if ($user && password_verify($password, $user['password'])) {
            // Check if user is active
            if ($user['is_active'] != 'yes') {
                return redirect()->back()->with('error', 'Your account has been deactivated. Please contact administrator.');
            }
            
            // Set session data
            $sessionData = [
                'user_id' => $user['id'],
                'email' => $user['email'],
                'username' => $user['username'],
                'role' => $user['role'],
                'isLoggedIn' => true,
                'login_time' => time()
            ];
            
            $this->session->set($sessionData);
            
            // Update last login
            $this->userModel->update($user['id'], [
                'last_login' => date('Y-m-d H:i:s'),
                'login_ip' => $this->request->getIPAddress()
            ]);
            
            // Set remember me cookie if requested
            if ($remember) {
                $token = bin2hex(random_bytes(32));
                setcookie('remember_token', $token, time() + (86400 * 30), '/', '', true, true); // 30 days
                $this->userModel->update($user['id'], ['remember_token' => $token]);
            }
            
            // Log successful login
            log_message('info', 'User logged in: ' . $email . ' from IP: ' . $this->request->getIPAddress());
            
            return redirect()->to('/dashboard')->with('success', 'Welcome back, ' . $user['username'] . '!');
        } else {
            // Log failed login attempt
            log_message('warning', 'Failed login attempt for: ' . $email . ' from IP: ' . $this->request->getIPAddress());
            
            return redirect()->back()->withInput()->with('error', 'Invalid email or password.');
        }
    }
    
    public function logout()
    {
        // Clear remember me cookie
        if (isset($_COOKIE['remember_token'])) {
            setcookie('remember_token', '', time() - 3600, '/', '', true, true);
            
            // Clear remember token from database
            $userId = $this->session->get('user_id');
            if ($userId) {
                $this->userModel->update($userId, ['remember_token' => null]);
            }
        }
        
        // Log logout
        $email = $this->session->get('email');
        if ($email) {
            log_message('info', 'User logged out: ' . $email);
        }
        
        // Destroy session
        $this->session->destroy();
        
        return redirect()->to('/auth/login')->with('success', 'You have been logged out successfully.');
    }
    
    public function demo()
    {
        // Create demo user session
        $sessionData = [
            'user_id' => 999,
            'email' => 'demo@smartschool.com',
            'username' => 'demo_user',
            'first_name' => 'Demo',
            'last_name' => 'User',
            'role' => 'admin',
            'isLoggedIn' => true,
            'is_demo' => true,
            'login_time' => time()
        ];
        
        $this->session->set($sessionData);
        
        // Generate demo data
        $this->generateDemoData();
        
        // Log demo access
        log_message('info', 'Demo mode accessed from IP: ' . $this->request->getIPAddress());
        
        return redirect()->to('/dashboard')->with('success', 'Welcome to Demo Mode! Sample data has been generated.');
    }
    
    private function generateDemoData()
    {
        // This method will generate sample data for demonstration
        // You can expand this to create sample students, teachers, classes, etc.
        
        // Set demo flag in session to indicate sample data is being used
        $this->session->set('demo_data_generated', true);
        
        // Log demo data generation
        log_message('info', 'Demo data generated for session');
    }
    
    public function register()
    {
        $data = [
            'title' => 'Register - School Management System',
            'page' => 'register'
        ];
        
        return view('auth/register', $data);
    }
    
    public function processRegister()
    {
        $rules = [
            'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'confirm_password' => 'required|matches[password]',
            'first_name' => 'required|min_length[2]|max_length[50]',
            'last_name' => 'required|min_length[2]|max_length[50]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'role' => 'user', // Default role
            'is_active' => 'no', // Require admin activation
            'created_at' => date('Y-m-d H:i:s'),
            'email_verification_token' => bin2hex(random_bytes(32))
        ];
        
        if ($this->userModel->insert($data)) {
            // Send verification email (implement email service)
            // $this->sendVerificationEmail($data['email'], $data['email_verification_token']);
            
            return redirect()->to('/auth/login')->with('success', 'Registration successful! Please wait for admin approval.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Registration failed. Please try again.');
        }
    }
    
    public function forgotPassword()
    {
        $data = [
            'title' => 'Forgot Password - School Management System',
            'page' => 'forgot_password'
        ];
        
        return view('auth/forgot_password', $data);
    }
    
    public function processForgotPassword()
    {
        $rules = [
            'email' => 'required|valid_email'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $email = $this->request->getPost('email');
        $user = $this->userModel->where('email', $email)->first();
        
        if ($user) {
            $resetToken = bin2hex(random_bytes(32));
            $resetExpiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            $this->userModel->update($user['id'], [
                'reset_token' => $resetToken,
                'reset_token_expiry' => $resetExpiry
            ]);
            
            // Send reset email (implement email service)
            // $this->sendPasswordResetEmail($email, $resetToken);
            
            return redirect()->back()->with('success', 'Password reset link has been sent to your email.');
        } else {
            return redirect()->back()->with('error', 'Email address not found.');
        }
    }
    
    public function resetPassword($token = null)
    {
        if (!$token) {
            return redirect()->to('/auth/login')->with('error', 'Invalid reset token.');
        }
        
        $user = $this->userModel->where('reset_token', $token)
                                ->where('reset_token_expiry >', date('Y-m-d H:i:s'))
                                ->first();
        
        if (!$user) {
            return redirect()->to('/auth/login')->with('error', 'Invalid or expired reset token.');
        }
        
        $data = [
            'title' => 'Reset Password - School Management System',
            'page' => 'reset_password',
            'token' => $token
        ];
        
        return view('auth/reset_password', $data);
    }
    
    public function processResetPassword()
    {
        $rules = [
            'token' => 'required',
            'password' => 'required|min_length[8]',
            'confirm_password' => 'required|matches[password]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $token = $this->request->getPost('token');
        $password = $this->request->getPost('password');
        
        $user = $this->userModel->where('reset_token', $token)
                                ->where('reset_token_expiry >', date('Y-m-d H:i:s'))
                                ->first();
        
        if (!$user) {
            return redirect()->to('/auth/login')->with('error', 'Invalid or expired reset token.');
        }
        
        $updateData = [
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'reset_token' => null,
            'reset_token_expiry' => null,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        if ($this->userModel->update($user['id'], $updateData)) {
            return redirect()->to('/auth/login')->with('success', 'Password reset successfully. Please login with your new password.');
        } else {
            return redirect()->back()->with('error', 'Failed to reset password. Please try again.');
        }
    }
    
    public function googleAuth()
    {
        // Google OAuth implementation
        // This would require Google Client library
        $data = [
            'title' => 'Google Authentication - School Management System',
            'page' => 'google_auth'
        ];
        
        return view('auth/google_auth', $data);
    }
    
    public function googleCallback()
    {
        // Handle Google OAuth callback
        // Implementation would depend on Google Client library
        return redirect()->to('/dashboard')->with('success', 'Google authentication successful!');
    }
    
    public function twoFactorAuth()
    {
        $data = [
            'title' => '2FA Setup - School Management System',
            'page' => '2fa_setup'
        ];
        
        return view('auth/2fa_setup', $data);
    }
    
    public function generateQRCode()
    {
        $userId = $this->session->get('user_id');
        if (!$userId) {
            return redirect()->to('/auth/login');
        }
        
        $user = $this->userModel->find($userId);
        if (!$user) {
            return redirect()->to('/auth/login');
        }
        
        // Generate secret key for 2FA
        $secret = $this->generateRandomSecret();
        
        // Update user with 2FA secret
        $this->userModel->update($userId, ['two_factor_secret' => $secret]);
        
        // Generate QR code data
        $qrCodeData = $this->generateQRCodeData($user['email'], $secret);
        
        return $this->response->setJSON([
            'success' => true,
            'qr_code' => $qrCodeData,
            'secret' => $secret
        ]);
    }
    
    public function verify2FA()
    {
        $userId = $this->session->get('user_id');
        $code = $this->request->getPost('code');
        
        if (!$userId || !$code) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }
        
        $user = $this->userModel->find($userId);
        if (!$user || !$user['two_factor_secret']) {
            return $this->response->setJSON(['success' => false, 'message' => '2FA not set up']);
        }
        
        // Verify TOTP code (would need TOTP library)
        $isValid = $this->verifyTOTP($user['two_factor_secret'], $code);
        
        if ($isValid) {
            $this->userModel->update($userId, ['two_factor_enabled' => 'yes']);
            return $this->response->setJSON(['success' => true, 'message' => '2FA enabled successfully']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid verification code']);
        }
    }
    
    private function generateRandomSecret($length = 32)
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $secret = '';
        for ($i = 0; $i < $length; $i++) {
            $secret .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $secret;
    }
    
    private function generateQRCodeData($email, $secret)
    {
        $issuer = 'School Management System';
        $label = urlencode($issuer . ':' . $email);
        $qrCodeUrl = "otpauth://totp/{$label}?secret={$secret}&issuer=" . urlencode($issuer);
        
        // This would generate actual QR code using QR code library
        return base64_encode($qrCodeUrl); // Placeholder
    }
    
    private function verifyTOTP($secret, $code)
    {
        // This would implement actual TOTP verification
        // For demo purposes, accept any 6-digit code
        return strlen($code) === 6 && is_numeric($code);
    }
    
    public function checkSession()
    {
        $isLoggedIn = $this->session->get('isLoggedIn');
        $loginTime = $this->session->get('login_time');
        
        // Check session timeout (24 hours)
        if ($isLoggedIn && $loginTime && (time() - $loginTime) > 86400) {
            $this->session->destroy();
            return $this->response->setJSON(['authenticated' => false, 'message' => 'Session expired']);
        }
        
        return $this->response->setJSON([
            'authenticated' => $isLoggedIn,
            'user' => $isLoggedIn ? [
                'id' => $this->session->get('user_id'),
                'email' => $this->session->get('email'),
                'username' => $this->session->get('username'),
                'role' => $this->session->get('role')
            ] : null
        ]);
    }
}