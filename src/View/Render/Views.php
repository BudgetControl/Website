<?php
namespace Mlab\BudetControl\View\Render;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;


class Views
{
    protected string $dirPath = __DIR__.'/../../../resources/Templates/';
    private Environment $twig;
    protected string $templateName = 'template.twig';

    public function __construct()
    {
        $this->validate();
        $loader = new FilesystemLoader($this->dirPath);
        $this->twig = new Environment($loader);
    }

    public function render(array $data = []): void
    {
        echo $this->twig->render($this->templateName, $data); die;
    }

    private function validate()
    {
        if(!file_exists($this->dirPath.$this->templateName)) {
            throw new ViewRenderExceptions("File doesn't exist on path ".$this->dirPath.$this->templateName);
        }

        if(!is_readable($this->dirPath.$this->templateName)) {
            throw new ViewRenderExceptions("File is not readable on path ".$this->dirPath.$this->templateName);
        }

        if(!strpos($this->templateName,'.html')) {
            throw new ViewRenderExceptions();
        }
    }
}