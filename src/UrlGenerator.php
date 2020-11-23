<?php

namespace LaravelTrailingSlash;

use Illuminate\Routing\UrlGenerator as BaseUrlGenerator;
use Illuminate\Support\Str;

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
        $trailingSlash = (Str::contains($path, '#') ? '' : '/');

        return rtrim(parent::format($root, $path, $route), '/').$trailingSlash;
    }
}
