<?php

declare(strict_types=1);

namespace LaravelTrailingSlash;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Routing\UrlGenerator as BaseUrlGenerator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class UrlGenerator extends BaseUrlGenerator
{
    /**
     * Format the given URL segments into a single URL.
     *
     * @param  string  $root
     * @param  string  $path
     * @param  Route|null  $route
     */
    public function format($root, $path, $route = null): string
    {
        return rtrim(parent::format($root, $path, $route), '/').$this->getTrailingSlash($path);
    }

    /**
     * Determine if the signature from the given request matches the URL.
     *
     * @param  bool  $absolute
     * @param  Closure|array<array-key, string>  $ignoreQuery
     */
    public function hasCorrectSignature(Request $request, $absolute = true, Closure|array $ignoreQuery = []): bool
    {
        $url = ($absolute ? $request->url() : '/'.$request->path());
        $url = $url.$this->getTrailingSlash($url);

        $rawQuery = $request->server->get('QUERY_STRING');
        $rawQuery = is_string($rawQuery) ? $rawQuery : '';

        $queryString = (new Collection(explode('&', $rawQuery)))
            ->reject(function ($parameter) use ($ignoreQuery) {
                $parameter = Str::before($parameter, '=');

                if ($parameter === 'signature') {
                    return true;
                }

                if ($ignoreQuery instanceof Closure) {
                    return $ignoreQuery($parameter);
                }

                return in_array($parameter, $ignoreQuery);
            })
            ->join('&');

        $original = rtrim($url.'?'.$queryString, '?');

        $keys = ($this->keyResolver)();

        $keys = is_array($keys) ? $keys : [$keys];

        $signature = $request->query('signature');

        if (!is_string($signature)) {
            return false;
        }

        foreach ($keys as $key) {
            if (hash_equals(
                hash_hmac('sha256', $original, $key),
                $signature
            )) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the previous path info for the request.
     *
     * @param  mixed  $fallback
     */
    public function previousPath($fallback = false): string
    {
        $rootToPath = rtrim($this->to('/'), '/');
        $previousPath = rtrim((string) preg_replace('/\?.*/', '', $this->previous($fallback)), '/');

        $previousPath = str_replace($rootToPath, '', $previousPath.$this->getTrailingSlash($previousPath));

        return $previousPath === '' ? '/' : $previousPath;
    }

    /**
     * Get trailing slash suffix for path or url, if no dash (#) is present.
     */
    private function getTrailingSlash(?string $url = null): string
    {
        if ($url === null) {
            return '/';
        }

        return Str::contains($url, '#') ? '' : '/';
    }
}
