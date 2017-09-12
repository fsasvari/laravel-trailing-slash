<?php

namespace LaravelTrailingSlash;

use Illuminate\Http\Request as BaseRequest;

class Request extends BaseRequest
{
    /**
     * Get the root URL for the application.
     *
     * @return string
     */
    public function root()
    {
        return parent::root().'/';
    }

    /**
     * Get the URL (no query string) for the request.
     *
     * @return string
     */
    public function url()
    {
        return parent::url().'/';
    }

    /**
     * Get the current path info for the request.
     *
     * @return string
     */
    public function path()
    {
        $pattern = parent::path();

        return $pattern == '/' ? '' : $pattern.'/';
    }

    /**
     * Get all of the segments for the request path.
     *
     * @return array
     */
    public function segments()
    {
        $segments = explode('/', trim($this->decodedPath(), '/'));

        return array_values(array_filter($segments, function ($v) {
            return $v !== '';
        }));
    }
}
