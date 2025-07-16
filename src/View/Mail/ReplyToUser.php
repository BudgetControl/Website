<?php
namespace Mlab\BudetControl\View\Mail;

use Mlab\BudetControl\View\Contact;
use MLAB\SdkMailer\View\Render\View;

class ReplyToUser extends ContactForm {

    public function getMessage(): string
    {
        $data = $this->data;

        return "
            <p>Hello {$data['name']},<br/>
            Thank you for reaching out to us!<br/>
            We have received your message and will get back to you shortly.<br/></p>

            <p>This is an automated response from BudgetControl.</p>
        ";
    }

}