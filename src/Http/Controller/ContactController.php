<?php

namespace Mlab\BudetControl\Http\Controller;

use Illuminate\Support\Facades\Log;
use Mlab\BudetControl\Facade\Mail;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Mlab\BudetControl\Services\EmailService;
use Mlab\BudetControl\View\Mail\ContactForm;
use Mlab\BudetControl\View\Mail\ReplyToUser;

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
            'timestamp' => date('Y-m-d H:i:s'),
            'ip_address' => $request->getAttribute('ip_address') ?? '0.0.0.0',
            'privacy' => isset($body['privacy']) && $body['privacy'] === 'on'
        ];

        $message = new ContactForm();
        $message->setData($data)
            ->setSubject("[Budget Control contact ] {$data['subject']}");

        try {
            $result = Mail::sendEmailViaCurl($message, env('CONTACT_EMAIL'));
            $this->replyToUser($data);
        } catch (\Exception $e) {
            Log::critical('Failed to send contact email: ' . $e->getMessage());
            return response(['success' => false, 'message' => 'Failed to send email: ' . $e->getMessage()], 500);
        }

        // Return JSON response
        return response(['success' => true, 'message' => "Email sent successfully"], 200);
    }

    /**
     * Sends a reply to the user based on the provided data.
     *
     * This method handles the process of responding to a user's contact request
     * using the information contained in the data array.
     *
     * @param array $data The data containing information needed to reply to the user
     * @return void
     */
    protected function replyToUser(array $data): void
    {
        $message = new ReplyToUser();
        $message->setData($data)
            ->setSubject('Thank you for contacting Budget Control');

        Mail::sendEmailViaCurl($message, $data['email']);
    }
}
