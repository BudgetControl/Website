<?php
namespace Mlab\BudetControl\Facade;

use Illuminate\Support\Facades\Facade;
use Budgetcontrol\SdkMailer\Service\Mail as BaseMail;

/**
 * Class Mail
 * 
 * @method static sendEmailViaCurl(\Mlab\BudetControl\View\Mail\ViewMailInterface $message, string $to)
 *
 * This class is a facade for the Mail class.
 * @see EmailService
 */

class Mail extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'mail';
    }
}