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
     *
     * @return string
     */
    public function format($root, $path)
    {
        return parent::format($root, $path).(str_contains($path, '#') ? '' : '/');
    }
}
