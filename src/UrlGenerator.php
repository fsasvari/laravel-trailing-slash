<?php

namespace LaravelTrailingSlash;

use Illuminate\Routing\UrlGenerator as BaseUrlGenerator;

class UrlGenerator extends BaseUrlGenerator
{
    /**
     * Format the given URL segments into a single URL.
     *
     * @param string $root
     * @param string $path
     * @param \Illuminate\Routing\Route|null $route
     * @return string
     */
    public function format($root, $path, $route = null)
    {
        return parent::format($root, $path, $route).(str_contains($path, '#') ? '' : '/');
    }
}
