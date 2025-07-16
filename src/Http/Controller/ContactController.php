<?php

namespace Mlab\BudetControl\Http\Controller;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Mlab\BudetControl\Services\EmailService;

class ContactController extends Controller
{
    public function send(Request $request, Response $response, $arg)
    {

        // Validate required fields
        $required_fields = ['name', 'email', 'subject', 'message', 'privacy'];
        $errors = [];

        $body = $request->getParsedBody();
        foreach ($required_fields as $field) {
            if (empty($body[$field])) {
                $errors[] = "The field '$field' is required.";
            }
        }
        
        // Validate email format
        if (!empty($body['email']) && !filter_var($body['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
        }
        
        // Validate privacy checkbox
        if (empty($body['privacy'])) {
            $errors[] = 'You must accept the privacy policy';
        }
        
        if (!empty($errors)) {
            return response(['success' => false, 'message' => implode(', ', $errors)], 500);
        }
        
        // Sanitize input data
        $data = [
            'name' => htmlspecialchars(trim($body['name'])),
            'email' => filter_var(trim($body['email']), FILTER_SANITIZE_EMAIL),
            'subject' => htmlspecialchars(trim($body['subject'])),
            'message' => htmlspecialchars(trim($body['message'])),
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        // Send email via external service
        $service = new EmailService();
        $result = $service->sendEmailViaCurl($data);

        // Return JSON response
        return response($result);
    }
    
    
}