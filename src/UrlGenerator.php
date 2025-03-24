<?php

declare(strict_types=1);

namespace LaravelTrailingSlash;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator as BaseUrlGenerator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class UrlGenerator extends BaseUrlGenerator
{
    /**
     * Format the given URL segments into a single URL.
     *
     * @param string                         $root
     * @param string                         $path
     * @param \Illuminate\Routing\Route|null $route
     */
    public function format($root, $path, $route = null): string
    {
        return rtrim(parent::format($root, $path, $route), '/').$this->getTrailingSlash($path);
    }

    /**
     * Determine if the signature from the given request matches the URL.
     *
     * @param \Illuminate\Http\Request $request
     * @param bool                     $absolute
     * @param array                    $ignoreQuery
     *
     * @return bool
     */
    public function hasCorrectSignature(Request $request, $absolute = true, Closure|array $ignoreQuery = [])
    {
        $ignoreQuery[] = 'signature';

        $url = ($absolute ? $request->url() : '/'.$request->path());
        $url = $url.$this->getTrailingSlash($url);

        $queryString = (new Collection(explode('&', (string) $request->server->get('QUERY_STRING'))))
            ->reject(fn ($parameter) => in_array(Str::before($parameter, '='), $ignoreQuery))
            ->join('&');

        $original = rtrim($url.'?'.$queryString, '?');

        $keys = call_user_func($this->keyResolver);

        $keys = is_array($keys) ? $keys : [$keys];

        foreach ($keys as $key) {
            if (hash_equals(
                hash_hmac('sha256', $original, $key),
                (string) $request->query('signature', '')
            )) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the previous path info for the request.
     *
     * @param mixed $fallback
     *
     * @return string
     */
    public function previousPath($fallback = false)
    {
        $rootToPath = rtrim($this->to('/'), '/');
        $previousPath = rtrim(preg_replace('/\?.*/', '', $this->previous($fallback)), '/');

        $previousPath = str_replace($rootToPath, '', $previousPath.$this->getTrailingSlash($previousPath));

        return $previousPath === '' ? '/' : $previousPath;
    }

    /**
     * Get trailing slash suffix for path or url, if no dash (#) is present.
     *
     * @param string|null $url
     *
     * @return string
     */
    private function getTrailingSlash(?string $url = null): string
    {
        if ($url === null) {
            return '/';
        }

        return Str::contains($url, '#') ? '' : '/';
    }
}
