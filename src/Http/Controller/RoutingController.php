<?php

namespace Mlab\BudetControl\Http\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class RoutingController extends Controller
{

    public function index()
    {

        $viewRenderer = new \Mlab\BudetControl\View\Home();
        $data = [];
        $viewRenderer->render($data);
    }

    public function aboutUs()
    {
        $viewRenderer = new \Mlab\BudetControl\View\About();
        $data = [];
        $viewRenderer->render($data);
    }

    public function documentation()
    {
        $viewRenderer = new \Mlab\BudetControl\View\Documentation();
        $data = [];
        $viewRenderer->render($data);
    }

    public function budgetControlForProfessionalUse()
    {
        $viewRenderer = new \Mlab\BudetControl\View\ProfessionalUse();
        $data = [];
        $viewRenderer->render($data);
    }

    public function login()
    {
        header("Location: https://app.budgetcontrol.cloud");
        exit;
    }

    public function donations()
    {
        $viewRenderer = new \Mlab\BudetControl\View\Donations();
        $data = [];
        $viewRenderer->render($data);
    }

    public function thanks()
    {
        $viewRenderer = new \Mlab\BudetControl\View\Thanks();
        $data = [];
        $viewRenderer->render($data);
    }

    public function contact()
    {
        $viewRenderer = new \Mlab\BudetControl\View\Contact();
        $data = [];
        $viewRenderer->render($data);
    }

    public function terms()
    {
        $viewRenderer = new \Mlab\BudetControl\View\Terms();
        $data = [];
        $viewRenderer->render($data);
    }

    public function privacy()
    {
        $viewRenderer = new \Mlab\BudetControl\View\Privacy();
        $data = [];
        $viewRenderer->render($data);
    }

}
