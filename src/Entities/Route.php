<?php

declare(strict_types=1);

namespace Mlab\BudetControl\Entities;

class Route implements RouteApplicationInterface
{
    private readonly string $path;
    private string $priority;
    private string $changefreq;
    private array $params;

    public function __construct(string $path, string $priority, string $changefreq, array $params = [])
    {
        $this->path = $path;
        $this->priority = $priority;
        $this->changefreq = $changefreq;
        $this->params = $params;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function getRoute(): string
    {
        return $this->path;
    }

    public function getRouteWithParams(): string
    {
        $route = $this->path;
        foreach ($this->params as $key => $value) {
            $route = str_replace('{' . $key . '}', $value, $route);
        }
        return $route;
    }

    /**
     * Get the value of priority
     *
     * @return string
     */
    public function getPriority(): string
    {
        return $this->priority;
    }

    /**
     * Get the value of changefreq
     *
     * @return string
     */
    public function getChangefreq(): string
    {
        return $this->changefreq;
    }

    /**
     * Get the value of path
     *
     * @return string
     */
    public function getPattern(): string
    {
        return $this->path;
    }
}