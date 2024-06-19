<?php

namespace Mlab\BudetControl\Http\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Mlab\BudetControl\Domain\Model\DataModel;
use Mlab\BudetControl\View\Home;
use stdClass;

class SubscribeController
{
    public function saveContact(Request $request, Response $response): Response
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

        return $response->withStatus(201);

    }
}
