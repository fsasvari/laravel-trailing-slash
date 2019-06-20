<?php

namespace LaravelTrailingSlash;

use Illuminate\Routing\UrlGenerator as BaseUrlGenerator;

class UrlGenerator extends BaseUrlGenerator
{
    /**
     * Format the given URL segments into a single URL.
     *
     * @param string                         $root
     * @param string                         $path
     * @param \Illuminate\Routing\Route|null $route
     *
     * @return string
     */
    public function format($root, $path, $route = null)
    {
        $match = [$path];

        if(!is_null($route)) {
            $match = array_merge([$route->uri,$route->action['as']], $match);
        }

        $matchRoute = config('trailingslash.included','*') == '*' || (is_array($match) && array_intersect($match, config('trailingslash.included','*')));

        return parent::format($root, $path, $route).(str_contains($path, '#') || !$matchRoute ? '' : '/');
    }
}
