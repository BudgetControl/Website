<?php
namespace Mlab\BudetControl\Facade;

use Illuminate\Support\Facades\Facade;

/**
 *
 * @method static string generateSitemapXml()
 * @method static string getSitemapData()
 * @method static string generateRobotsText()
 * 
 * @see \Mlab\BudetControl\Services\RouteSiteService
 */

class RouteSite extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'route-site';
    }
}