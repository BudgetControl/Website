<?php
namespace Mlab\BudetControl\Entities;

interface RouteApplicationInterface
{
    public function getRoute(): string;

    public function getRouteWithParams(): string;

    public function getParams(): array;

    public function getPattern(): string;
}