<?php
namespace Mlab\BudetControl\View;

class Sitemap extends Render\Views {

    protected string $templateName = 'sitemap.html';

    public function render(array $data = []): void
    {
        $data['app_url'] = env('APP_URL');
        $lastMod = date('c'); // ISO 8601 format
        $data['last_modified'] = $lastMod;
        
        header('Content-Type: application/xml; charset=utf-8');
        header('Cache-Control: max-age=86400, public'); // Increased cache time to 24 hours
        header('Content-Disposition: inline; filename="sitemap.xml"');
        header('X-Robots-Tag: index, follow', true);
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', strtotime($lastMod)) . ' GMT');
        
        // Add ETag for better caching
        $etag = md5($lastMod);
        header('ETag: "' . $etag . '"');

        echo $data['sitemap'];die;
        
    }

}