<?php
namespace Mlab\BudetControl\View;

use Throwable;

class Error extends Render\Views {

    protected string $templateName = 'error.html';
    private Throwable $exception;

    public function __construct(Throwable $exception)
    {
        $this->exception = $exception;
        parent::__construct();
    }

    public function render(array $message = []): void
    {   
        if(env('APP_ENV') == 'dev') {
            $data['exception'] = $this->exception->getMessage();
        }
        $data['statusCode'] = $this->exception->getCode();

        // generic message test
        if(empty($message)) {
            switch ($data['statusCode']) {
                case 404:
                    $data['message'] = 'Page not found';
                    break;
                case 405:
                    $data['message'] = 'Method not allowed';
                    break;
                default:
                    $data['message'] = 'An error occurred';
                    break;
            }
        }

        echo $this->twig->render($this->templateName, $data); die;
    }

}