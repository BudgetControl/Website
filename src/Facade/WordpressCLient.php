<?php
declare(strict_types=1);

namespace Mlab\BudetControl\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Mlabfactory\WordPress\Object\Post getPosts()
 * @method static \Mlabfactory\WordPress\WordPressFacade setClient($client)
 * @method static \Mlabfactory\WordPress\WordPressFacade getClient()
 * @method static \Mlabfactory\WordPress\WordPressFacade setAccessToken($accessToken)
 * @method static \Mlabfactory\WordPress\WordPressFacade getAccessToken()
 * @method static \Mlabfactory\WordPress\WordPressFacade getLatestHeader()
 * @method static \Mlabfactory\WordPress\WordPressFacade setServer($url)
 * @method static string getServer()
 * @method static \Mlabfactory\WordPress\WordPressFacade setUsername($username)
 * @method static \Mlabfactory\WordPress\WordPressFacade setApplicationPassword($password)
 * @method static \Mlabfactory\WordPress\WordPressFacade postCall($endPoint, $data)
 * @method static \Mlabfactory\WordPress\WordPressFacade getCall($endPoint, $data = null)
 * @method static \Mlabfactory\WordPress\WordPressFacade putCall($endPoint, $data)
 * @method static \Mlabfactory\WordPress\WordPressFacade deleteCall($endPoint)
 * @method static \Mlabfactory\WordPress\WordPressFacade optionsCall($endPoint)
 * @method static \Mlabfactory\WordPress\WordPressFacade login($email, $password)
 * @method static \Mlabfactory\WordPress\Service\User user()
 * @method static \Mlabfactory\WordPress\Service\Post post()
 * @method static \Mlabfactory\WordPress\Service\CustomPost customPost($postType)
 * @method static \Mlabfactory\WordPress\Service\Tag tag()
 * @method static \Mlabfactory\WordPress\WordPressFacade outputAsObject($outputAsObject)
 * @method static \Mlabfactory\WordPress\WordPressFacade rawOutput($rawOutput)
 * @method static \Mlabfactory\WordPress\Service\Media media()
 * 
 * @see \Mlabfactory\WordPress\WordPress
 */
final class WordpressCLient extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'wordpress-client';
    }
}