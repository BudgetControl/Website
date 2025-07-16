<?php 
namespace Mlab\BudetControl\View\Mail;

interface ViewMailInterface
{

    /**
     * Set the message content for the email.
     *
     * @param string $message
     * @return self
     */
    public function getMessage(): string;

    /**
     * Set the data used in the email.
     *
     * @param string $name
     * @return self
     */
    public function setData(array $data): self;

    /**
     * Get the data used in the email.
     *
     * @return array
     */
    public function getData(): array;

    /**
     * Set the subject of the email.
     *
     * @param string $subject
     * @return self
     */
    public function setSubject(string $subject): self;

    /**
     * Get the subject of the email.
     *
     * @return string
     */
    public function getSubject(): string;
}