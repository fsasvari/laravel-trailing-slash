<?php

namespace LaravelTrailingSlash;

use Illuminate\Routing\UrlGenerator as BaseUrlGenerator;

class UrlGenerator extends BaseUrlGenerator
{
	/**
     * Format the given URL segments into a single URL.
     *
     * @param  string  $root
     * @param  string  $path
     * @param  string  $tail
     * @return string
     */
	protected function trimUrl($root, $path, $tail = '')
	{
		return parent::trimUrl($root, $path, $tail).'/';
	}
}
