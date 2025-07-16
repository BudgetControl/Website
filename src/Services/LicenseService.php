<?php
// filepath: /Users/marco/Documents/Projects/marco/BudgetControl-universe/Website/src/Services/LicenseService.php

namespace Mlab\BudetControl\Services;

class LicenseService
{
    private EmailService $emailService;
    
    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }
    
    public function createLicense(array $orderData): array
    {
        try {
            // Generate license key
            $licenseKey = $this->generateLicenseKey();
            
            // Create license record
            $license = [
                'license_key' => $licenseKey,
                'customer_email' => $orderData['email'],
                'customer_name' => $orderData['first_name'] . ' ' . $orderData['last_name'],
                'product_id' => $orderData['product_id'],
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'expires_at' => null // Lifetime license
            ];
            
            // Save license to database or file
            $this->saveLicense($license);
            
            // Send license email
            $this->sendLicenseEmail($license);
            
            return [
                'success' => true,
                'license_key' => $licenseKey,
                'message' => 'License created successfully'
            ];
            
        } catch (\Exception $e) {
            error_log("License creation failed: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'License creation failed'
            ];
        }
    }
    
    private function generateLicenseKey(): string
    {
        // Generate a unique license key
        $prefix = 'BC-PRO-';
        $random = strtoupper(substr(str_replace(['+', '/', '='], '', base64_encode(random_bytes(16))), 0, 16));
        
        // Format as XXXX-XXXX-XXXX-XXXX
        $formatted = chunk_split($random, 4, '-');
        
        return $prefix . rtrim($formatted, '-');
    }
    
    private function saveLicense(array $license): void
    {
        // Save to file (in production, use database)
        $licensesFile = dirname(__DIR__, 2) . '/storage/licenses.json';
        $licensesDir = dirname($licensesFile);
        
        if (!is_dir($licensesDir)) {
            mkdir($licensesDir, 0755, true);
        }
        
        $existingLicenses = [];
        if (file_exists($licensesFile)) {
            $existingLicenses = json_decode(file_get_contents($licensesFile), true) ?? [];
        }
        
        $existingLicenses[] = $license;
        
        file_put_contents($licensesFile, json_encode($existingLicenses, JSON_PRETTY_PRINT));
    }
    
    private function sendLicenseEmail(array $license): void
    {
        $emailData = [
            'name' => $license['customer_name'],
            'email' => $license['customer_email'],
            'subject' => 'Your Budget Control Pro License',
            'message' => $this->getLicenseEmailTemplate($license),
            'timestamp' => date('Y-m-d H:i:s'),
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ];
        
        $this->emailService->sendEmailViaCurl($emailData);
    }
    
    private function getLicenseEmailTemplate(array $license): string
    {
        return "
        <h2>Welcome to Budget Control Pro!</h2>
        
        <p>Thank you for purchasing Budget Control Pro. Your license has been activated and you now have access to all premium features.</p>
        
        <h3>License Details:</h3>
        <ul>
            <li><strong>License Key:</strong> {$license['license_key']}</li>
            <li><strong>Product:</strong> Budget Control Pro</li>
            <li><strong>Status:</strong> Active</li>
            <li><strong>Type:</strong> Lifetime License</li>
        </ul>
        
        <h3>How to Activate:</h3>
        <ol>
            <li>Login to your Budget Control account at <a href='https://app.budgetcontrol.cloud'>app.budgetcontrol.cloud</a></li>
            <li>Go to Settings > License</li>
            <li>Enter your license key: <strong>{$license['license_key']}</strong></li>
            <li>Click 'Activate License'</li>
        </ol>
        
        <h3>Pro Features Now Available:</h3>
        <ul>
            <li>✓ Unlimited accounts and workspaces</li>
            <li>✓ Advanced analytics and reporting</li>
            <li>✓ API access for integrations</li>
            <li>✓ Priority customer support</li>
            <li>✓ Export data in multiple formats</li>
        </ul>
        
        <p>If you need any assistance, please contact our support team at <a href='mailto:support@budgetcontrol.cloud'>support@budgetcontrol.cloud</a></p>
        
        <p>Thank you for choosing Budget Control Pro!</p>
        
        <p>Best regards,<br>
        The Budget Control Team</p>
        ";
    }
    
    public function verifyLicense(string $licenseKey): array
    {
        // Load licenses from file (in production, use database)
        $licensesFile = dirname(__DIR__, 2) . '/storage/licenses.json';
        
        if (!file_exists($licensesFile)) {
            return ['valid' => false, 'message' => 'License not found'];
        }
        
        $licenses = json_decode(file_get_contents($licensesFile), true) ?? [];
        
        foreach ($licenses as $license) {
            if ($license['license_key'] === $licenseKey) {
                if ($license['status'] === 'active') {
                    return ['valid' => true, 'license' => $license];
                } else {
                    return ['valid' => false, 'message' => 'License is not active'];
                }
            }
        }
        
        return ['valid' => false, 'message' => 'Invalid license key'];
    }
}