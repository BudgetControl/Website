<?php
namespace Mlab\BudetControl\View\Mail;

use MLAB\SdkMailer\View\Render\View;

class ContactForm implements ViewMailInterface {

    protected array $data;
    protected string $subject = 'Contact Form Submission';

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setData(array $data): ViewMailInterface
    {
        if(empty($data['name']) || empty($data['email']) || empty($data['subject']) || empty($data['message']) || empty($data['timestamp']) || empty($data['ip_address']) || !isset($data['privacy'])) {
            throw new \InvalidArgumentException('Required data fields are missing');
        }

        $this->data = $data;

        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getMessage(): string
    {
        $data = $this->data;

        return "
            <p>New contact form submission from BudgetControl website:</p>

            <p>Name: {$data['name']}</p>
            <p>Email: {$data['email']}</p>
            <p>Subject: {$data['subject']}</p>
            <p>Timestamp: {$data['timestamp']}</p>
            <p>IP Address: {$data['ip_address']}</p>

            <p>Message:<br/>
            {$data['message']}</p>

            <p>Is accepted privacy policy: " . ($data['privacy'] ? 'Yes' : 'No') . "</p>

            <p>This message was sent from the BudgetControl contact form.</p>
        ";

    }
}