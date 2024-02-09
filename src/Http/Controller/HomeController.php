<?php

namespace Mlab\BudetControl\Http\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Mlab\BudetControl\Domain\Model\DataModel;
use Mlab\BudetControl\View\Home;

class HomeController extends Controller
{

    public function index()
    {

        $viewRenderer = new Home();
        $data = [
            'title' => 'Hello Page',
            'message' => 'Hello, world!',
        ];
        $viewRenderer->render($data);
    }

    public function saveContact(Request $request, Response $response): Response
    {

        $parsedBody = $request->getParsedBody();
	
	file_put_contents('/var/www/workdir/storage/email.log',json_encode(['email' => $parsedBody['email'], 'date' => date('Y-m-d H:i:s')]),FILE_APPEND);
#        $data = new DataModel();
#        $data->email = $parsedBody['email'];
#        $data->marketing = true;
#        $data->save();

        return $response->withStatus(201);

    }
}
