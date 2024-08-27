<?php
namespace Mlab\BudetControl\Facade;

use Illuminate\Support\Facades\Facade;
use Budgetcontrol\SdkMailer\Service\Mail as BaseMail;

/**
 * Class Mail
 *
 * This class is a facade for the Mail class.
 * @see MLAB\SdkMailer\Service\Mail
 */

class Mail extends Facade
{
    public static function setProperty($value)
    {
        self::$app['config']->set('mail.driver', env('MAIL_DRIVER'));
        self::$app['config']->set('mail.host', env('MAIL_HOST'));
        self::$app['config']->set('mail.username', env('MAIL_USERNAME'));
        self::$app['config']->set('mail.password', env('MAIL_PASSWORD'));
        self::$app['config']->set('mail.from.address', env('MAIL_FROM_ADDRESS'));
    }

    protected static function getFacadeAccessor()
    {
        return 'mail';
    }
}