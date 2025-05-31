<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\UserModel;
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class QRCode extends BaseController
{
    protected $userModel;
    protected $google2fa;
    protected $session;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        
        $this->userModel = new UserModel();
        $this->google2fa = new Google2FA();
        $this->session = \Config\Services::session();
    }

    /**
     * Display QR Code setup page
     */
    public function setup()
    {
        // Check if user is logged in
        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to('/auth')->with('error', 'Please login first.');
        }

        $userId = $this->session->get('user_id');
        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->to('/dashboard')->with('error', 'User not found.');
        }

        // Check if 2FA is already enabled
        if ($user['two_factor_enabled'] === 'yes') {
            return redirect()->to('/dashboard')->with('info', '2FA is already enabled for your account.');
        }

        // Generate secret key if not exists
        if (empty($user['two_factor_secret'])) {
            $secretKey = $this->google2fa->generateSecretKey();
            $this->userModel->update($userId, ['two_factor_secret' => $secretKey]);
            $user['two_factor_secret'] = $secretKey;
        }

        // Generate QR Code
        $qrCodeUrl = $this->google2fa->getQRCodeUrl(
            'Smart School Management System',
            $user['email'],
            $user['two_factor_secret']
        );

        $data = [
            'title' => '2FA Setup',
            'user' => $user,
            'qrCodeUrl' => $qrCodeUrl,
            'secretKey' => $user['two_factor_secret']
        ];

        return view('auth/2fa_setup', $data);
    }

    /**
     * Generate QR Code image
     */
    public function generate()
    {
        // Check if user is logged in
        if (!$this->session->get('isLoggedIn')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }

        $userId = $this->session->get('user_id');
        $user = $this->userModel->find($userId);

        if (!$user || empty($user['two_factor_secret'])) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Secret key not found']);
        }

        try {
            // Generate QR Code URL
            $qrCodeUrl = $this->google2fa->getQRCodeUrl(
                'Smart School Management System',
                $user['email'],
                $user['two_factor_secret']
            );

            // Create QR Code image using BaconQrCode
            $renderer = new ImageRenderer(
                new RendererStyle(400),
                new ImagickImageBackEnd()
            );
            $writer = new Writer($renderer);
            $qrCodeImage = $writer->writeString($qrCodeUrl);

            // Return image response
            return $this->response
                ->setHeader('Content-Type', 'image/png')
                ->setBody($qrCodeImage);

        } catch (\Exception $e) {
            log_message('error', 'QR Code generation failed: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to generate QR code']);
        }
    }

    /**
     * Verify and enable 2FA
     */
    public function verify()
    {
        // Check if user is logged in
        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to('/auth')->with('error', 'Please login first.');
        }

        $userId = $this->session->get('user_id');
        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->to('/dashboard')->with('error', 'User not found.');
        }

        $rules = [
            'verification_code' => 'required|numeric|exact_length[6]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $verificationCode = $this->request->getPost('verification_code');
        $secretKey = $user['two_factor_secret'];

        // Verify the code
        $isValid = $this->google2fa->verifyKey($secretKey, $verificationCode);

        if ($isValid) {
            // Enable 2FA for the user
            $this->userModel->update($userId, ['two_factor_enabled' => 'yes']);
            
            return redirect()->to('/dashboard')->with('success', '2FA has been successfully enabled for your account.');
        } else {
            return redirect()->back()->with('error', 'Invalid verification code. Please try again.');
        }
    }

    /**
     * Disable 2FA
     */
    public function disable()
    {
        // Check if user is logged in
        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to('/auth')->with('error', 'Please login first.');
        }

        $userId = $this->session->get('user_id');
        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->to('/dashboard')->with('error', 'User not found.');
        }

        $rules = [
            'verification_code' => 'required|numeric|exact_length[6]',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $verificationCode = $this->request->getPost('verification_code');
        $password = $this->request->getPost('password');
        $secretKey = $user['two_factor_secret'];

        // Verify password
        if (!password_verify($password, $user['password'])) {
            return redirect()->back()->with('error', 'Invalid password.');
        }

        // Verify 2FA code
        $isValid = $this->google2fa->verifyKey($secretKey, $verificationCode);

        if ($isValid) {
            // Disable 2FA for the user
            $this->userModel->update($userId, [
                'two_factor_enabled' => 'no',
                'two_factor_secret' => null
            ]);
            
            return redirect()->to('/dashboard')->with('success', '2FA has been disabled for your account.');
        } else {
            return redirect()->back()->with('error', 'Invalid verification code. Please try again.');
        }
    }

    /**
     * Verify 2FA during login
     */
    public function verifyLogin()
    {
        $tempUserId = $this->session->get('temp_user_id');
        
        if (!$tempUserId) {
            return redirect()->to('/auth')->with('error', 'Session expired. Please login again.');
        }

        $user = $this->userModel->find($tempUserId);
        
        if (!$user) {
            return redirect()->to('/auth')->with('error', 'User not found.');
        }

        $rules = [
            'verification_code' => 'required|numeric|exact_length[6]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $verificationCode = $this->request->getPost('verification_code');
        $secretKey = $user['two_factor_secret'];

        // Verify the code
        $isValid = $this->google2fa->verifyKey($secretKey, $verificationCode);

        if ($isValid) {
            // Complete login process
            $sessionData = [
                'user_id' => $user['id'],
                'email' => $user['email'],
                'username' => $user['username'],
                'role' => $user['role'],
                'isLoggedIn' => true,
                'login_time' => time()
            ];

            $this->session->set($sessionData);
            $this->session->remove('temp_user_id');

            // Update last login
            $this->userModel->update($user['id'], [
                'last_login' => date('Y-m-d H:i:s'),
                'login_ip' => $this->request->getIPAddress()
            ]);
            
            return redirect()->to('/dashboard')->with('success', 'Login successful!');
        } else {
            return redirect()->back()->with('error', 'Invalid verification code. Please try again.');
        }
    }

    /**
     * Show 2FA verification page during login
     */
    public function loginVerify()
    {
        $tempUserId = $this->session->get('temp_user_id');
        
        if (!$tempUserId) {
            return redirect()->to('/auth')->with('error', 'Session expired. Please login again.');
        }

        $user = $this->userModel->find($tempUserId);
        
        if (!$user) {
            return redirect()->to('/auth')->with('error', 'User not found.');
        }

        $data = [
            'title' => '2FA Verification',
            'user' => $user
        ];

        return view('auth/2fa_verify', $data);
    }
}