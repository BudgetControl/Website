<?php
namespace Mlab\BudetControl\View\Mail;

use MLAB\SdkMailer\View\Render\View;

class ContactForm extends View  {

    private string $message;
    private string $name;

    public function view() :string
    {
        return $this->render([
            'message' => $this->message,
            'name' => $this->name,
        ]);
    }

    /**
     * Set the value of message
     *
     * @param string $message
     *
     * @return self
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }


    /**
     * Set the value of name
     *
     * @param string $name
     *
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}