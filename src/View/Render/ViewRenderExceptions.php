<?php
namespace Mlab\BudetControl\View\Render;

use Exception;

class ViewRenderExceptions extends Exception {

    // Personalizza la tua eccezione, se necessario
    public function __construct($message = "Templane file must have .twig in extension") {
        parent::__construct($message, 500);
    }

}