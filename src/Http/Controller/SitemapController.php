<?php
declare(strict_types=1);
namespace Mlab\BudetControl\Http\Controller;

use Mlab\BudetControl\Facade\RouteSite;

class SitemapController extends Controller
{
    public function index()
    {
        $sitemap = RouteSite::generateSitemapXml();

        $viewRenderer = new \Mlab\BudetControl\View\Sitemap();
        $data = ['sitemap' => $sitemap];
        $data['seo'] = ['title' => 'Sitemap'];
        $viewRenderer->render($data);
    }
}