<?php

declare(strict_types=1);

namespace Mlab\BudetControl\Services;

use Mlab\BudetControl\Entities\Route;
use Psr\Container\ContainerInterface;

class RouteSiteService
{   
    private const EXPLUDE_ROUTES = [
        'Mlab\BudetControl\Http\Controller\WordpressController:showByCategory',
        '/admin',
    ];

    /**
     * @var <int, \Mlab\BudetControl\Entities\RouteApplicationInterface>
     */
    private array $routes;
    private \Slim\App $container;

    public function __construct(\Slim\App $container)
    {
        $this->container = $container;
    }

    public function generateSitemapXml(): string
    {
        $this->getRouteFromApplication();

        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"/>');

        /** @var Route $route */
        foreach ($this->routes as $route) {
            $url = $xml->addChild('url');
            $url->addChild('loc', $this->getFullUrl($route->getRoute()));
            $url->addChild('priority', $route->getPriority());
            $url->addChild('changefreq', $route->getChangefreq());
            $url->addChild('lastmod', date('Y-m-d'));
        }

        return $xml->asXML();
    }

    public function getSitemapData(): array
    {
        return $this->routes;
    }

    private function getFullUrl(string $path): string
    {
        // Sostituisci con il tuo dominio
        $baseUrl = 'https://www.budgetcontrol.cloud';
        return $baseUrl . $path;
    }

    public function generateRobotsText(): string
    {
        return "User-agent: *\nAllow: /\nSitemap: " . $this->getFullUrl('/sitemap.xml');
    }

    /**
     * Ottiene le rotte dall'applicazione e le categorizza in base al pattern.
     *
     * @return void
     */
    private function getRouteFromApplication(): void
    {
        /** @var \Slim\App $app */
        $app = $this->container;
        $routes = $app->getRouteCollector()->getRoutes();
        $sitemapRoutes = [];
        
        /** @var \Slim\Routing\Route $route */
        foreach ($routes as $route) {

            if(!empty($group = $route->getGroups())) {
                if($group[0]->getPattern() === 'blog' || $group[0]->getPattern() === 'api') {
                    continue;
                }
            }

            $pattern = $route->getPattern();

            // Skippa le rotte API o admin se necessario
            if (str_starts_with($pattern, '/api') || str_starts_with($pattern, '/admin')) {
                continue;
            }

            // Categorizza le rotte in base al pattern
            $routeData = [
                'loc' => $pattern,
                'priority' => $this->getRoutePriority($pattern),
                'changefreq' => $this->getRouteChangeFreq($pattern)
            ];

            $sitemapRoutes[] = new Route(
                $routeData['loc'],
                $routeData['priority'],
                $routeData['changefreq']
            );

        }

        $blogRoutes = $this->getBlogRoute();
        $sitemapRoutes = array_merge($sitemapRoutes, $blogRoutes);

        $this->routes = $sitemapRoutes;
    }

    private function getRoutePriority(string $pattern): string
    {
        // Definisci le priorità in base al pattern della rotta
        return match (true) {
            $pattern === '/' => '1.0',
            in_array($pattern, ['/about', '/features', '/pricing']) => '0.8',
            str_starts_with($pattern, '/blog') => '0.7',
            str_starts_with($pattern, '/user') => '0.6',
            default => '0.5',
        };
    }

    private function getRouteChangeFreq(string $pattern): string
    {
        // Definisci la frequenza di cambio in base al pattern della rotta
        return match (true) {
            $pattern === '/' => 'daily',
            str_starts_with($pattern, '/blog') => 'weekly',
            str_starts_with($pattern, '/docs') => 'monthly',
            default => 'monthly',
        };
    }

    private function getBlogRoute(): array
    {
        /** @var \Mlab\BudetControl\Entities\Posts $articles */
        $articles = WordpressPost::getArticles();

        $routes = [];
        foreach ($articles->getPosts() as $article) {
            $routes[] = new Route(
                path_to($article['post']->getLink(), ''),
                '0.7',
                'never'
            );
        }

        return $routes;
    }
}
