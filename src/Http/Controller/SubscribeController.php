<?php

namespace Mlab\BudetControl\Http\Controller;

use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Mlab\BudetControl\Domain\Model\DataModel;
use Mlab\BudetControl\Facade\Mail;
use Mlab\BudetControl\View\Mail\ContactForm;
use MLAB\SdkMailer\View\Mail as ViewMail;
use stdClass;

class SubscribeController
{
    public function subscribe(Request $request, Response $response): Response
    {
        $parsedBody = $request->getParsedBody();

        $data = new DataModel();
        $data->email = $parsedBody['email'];
        $data->marketing = true;
        $data->save();

        return $response->withStatus(201);
    }

    public function contact(Request $request, Response $response): Response
    {

        $parsedBody = $request->getParsedBody();

        $data = new stdClass();
        $data->email = $parsedBody['email'];
        $data->name = $parsedBody['name'];
        $data->message = $parsedBody['message'];

        try {
            $view = new ContactForm();
            $view->setMessage($data->email." </br> ".$data->message);
            $view->setName($data->name);
    
            Mail::sendMail(env('MAIL_TO_ADDRESS'), "Contact form", $view);

        } catch (\Throwable $e) {
            Log::error("Error on sending mail ".$e->getMessage());
            return $response->withStatus(500);
        }

        return $response->withStatus(201);

    }
}
