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
    
    /**
     * Determine if the signature from the given request matches the URL with a trailing slash.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  bool  $absolute
     * @param  array  $ignoreQuery
     * @return bool
     */
    public function hasCorrectSignature($request, $absolute = true, array $ignoreQuery = [])
    {
        $ignoreQuery[] = 'signature';

        $url = $absolute ? $request->url() : '/'.$request->path();

        $queryString = collect(explode('&', $request->server->get('QUERY_STRING')))
            ->reject(fn ($parameter) => in_array(Str::before($parameter, '='), $ignoreQuery))
            ->join('&');

        $original = rtrim($url.'/?'.$queryString, '?');

        $signature = hash_hmac('sha256', $original, call_user_func($this->keyResolver));

        return hash_equals($signature, (string) $request->query('signature', ''));
    }
}
