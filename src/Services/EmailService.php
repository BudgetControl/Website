<?php
// filepath: /Users/marco/Documents/Projects/marco/BudgetControl-universe/Website/src/Services/EmailService.php

namespace Mlab\BudetControl\Services;

use Mlab\BudetControl\View\Mail\ViewMailInterface;

class EmailService
{
    
    public function sendEmailViaCurl(ViewMailInterface $message, string $to)
    {
        $mailer_path = $_ENV['MAILER_PATH'] ?? null;
        
        if (!$mailer_path) {
            return ['success' => false, 'message' => 'Mailer service not configured'];
        }
        
        // Prepare email data
        $email_data = [
            'to' => $to,
            'subject' => $message->getSubject(),
            'user_name' => $message->getData()['name'],
            'message' => $message->getMessage()
        ];
        
        $curl = invoke($mailer_path . '/notify/email/contact', 'POST', $email_data);
        
        // Handle cURL errors
        if ($curl['error'] === true) {
            error_log("Contact form cURL error: " . $curl['response']);
            return ['success' => false, 'message' => 'Email service unavailable'];
        }
        
        return [
            'success' => true, 
            'message' => 'Message sent successfully! We\'ll get back to you soon.'
        ];
    }
   
}