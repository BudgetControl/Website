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
        $data['seo'] = ['title' => 'Budget Control - The best way to manage your money'];
        $viewRenderer->render($data);
    }

    public function aboutUs()
    {
        $viewRenderer = new \Mlab\BudetControl\View\About();
        $data = [];
        $data['seo'] = ['title' => 'About Budget Control'];
        $viewRenderer->render($data);
    }

    public function documentation()
    {
        $viewRenderer = new \Mlab\BudetControl\View\Documentation();
        $data = [];
        $data['seo'] = ['title' => 'Budget Control Documentation'];
        $viewRenderer->render($data);
    }

    public function budgetControlForProfessionalUse()
    {
        $viewRenderer = new \Mlab\BudetControl\View\ProfessionalUse();
        $data = [];
        $data['seo'] = ['title' => 'Budget Control for professional use'];
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
        $data['seo'] = ['title' => 'Donations'];
        $viewRenderer->render($data);
    }

    public function thanks()
    {
        $viewRenderer = new \Mlab\BudetControl\View\Thanks();
        $data = [];
        $data['seo'] = ['title' => 'Thanks'];
        $viewRenderer->render($data);
    }

    public function contact()
    {
        $viewRenderer = new \Mlab\BudetControl\View\Contact();
        $data = [];
        $data['seo'] = ['title' => 'Contact'];
        $viewRenderer->render($data);
    }

    public function terms()
    {
        $viewRenderer = new \Mlab\BudetControl\View\Terms();
        $data = [];
        $data['seo'] = ['title' => 'Terms'];
        $viewRenderer->render($data);
    }

    public function privacy()
    {
        $viewRenderer = new \Mlab\BudetControl\View\Privacy();
        $data = [];
        $data['seo'] = ['title' => 'Privacy'];
        $viewRenderer->render($data);
    }

    public function contributors()
    {
        $viewRenderer = new \Mlab\BudetControl\View\Contributors();
        $data = [];
        $data['seo'] = ['title' => 'Contributors'];
        $viewRenderer->render($data);
    }

}
