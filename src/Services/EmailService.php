<?php
// filepath: /Users/marco/Documents/Projects/marco/BudgetControl-universe/Website/src/Services/EmailService.php

namespace Mlab\BudetControl\Services;

class EmailService
{
    
    public function sendEmailViaCurl($data)
    {
        $mailer_path = $_ENV['MAILER_PATH'] ?? null;
        
        if (!$mailer_path) {
            return ['success' => false, 'message' => 'Mailer service not configured'];
        }
        
        // Prepare email data
        $email_data = [
            'to' => env('CONTACT_EMAIL'),
            'subject' => '[BudgetControl Contact] ' . $data['subject'],
            'user_name' => $data['name'],
            'message' => $this->formatEmailMessage($data)
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
    
    private function formatEmailMessage($data)
    {
        return "
            New contact form submission from BudgetControl website:<br/>
            -----------------------------------

            Name: {$data['name']}<br/>
            Email: {$data['email']}<br/>
            Subject: {$data['subject']}<br/>
            Timestamp: {$data['timestamp']}<br/>
            IP Address: {$data['ip_address']}<br/>
            -----------------------------------<br/>

            Message:<br/>
            {$data['message']}<br/>

            Is accepted privacy policy: " . ($data['privacy'] ? 'Yes' : 'No') . "<br/>

            ---<br/>
            This message was sent from the BudgetControl contact form.
        ";
    }
   
}