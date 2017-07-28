# Laravel Trailing Slash

Adds redirection with trailing slash in Laravel.

[![Build For Laravel](https://img.shields.io/badge/Built_for-Laravel-orange.svg)](https://styleci.io/repos/79834672)
[![Latest Stable Version](https://poser.pugx.org/fsasvari/laravel-trailing-slash/v/stable)](https://packagist.org/packages/fsasvari/laravel-trailing-slash)
[![Latest Unstable Version](https://poser.pugx.org/fsasvari/laravel-trailing-slash/v/unstable)](https://packagist.org/packages/fsasvari/laravel-trailing-slash)
[![Total Downloads](https://poser.pugx.org/fsasvari/laravel-trailing-slash/downloads)](https://packagist.org/packages/fsasvari/laravel-trailing-slash)
[![License](https://poser.pugx.org/fsasvari/laravel-trailing-slash/license)](https://packagist.org/packages/fsasvari/laravel-trailing-slash)

## Installation

To get started with Laravel Trailing Slash, use Composer to add the package to your project's dependencies:

    composer require fsasvari/laravel-trailing-slash

## Configuration

After installing the Laravel Trailing Slash library, register the `LaravelTrailingSlash\RoutingServiceProvider` in your `config/app.php` configuration file immediately after 'Application Service Providers':

```php
'providers' => [
    // Application Service Providers...
    // ...

    // Other Service Providers...
    LaravelTrailingSlash\RoutingServiceProvider::class,
    // ...
],
```

Copy following redirection code from `public/.htaccess` to your own project:

```
<IfModule mod_rewrite.c>
    # Redirect To Trailing Slashes If Not A Folder Or A File...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_URI} !(/$|\.)
    RewriteRule (.*) %{REQUEST_URI}/ [R=301,L]
</IfModule>
```

## Usage

### Routes

In routes/web.php, you can use routes with trailing slashes:

```php
Route::get('/', function () {
    return view('welcome');
});

Route::get('about/', function () {
    return view('about');
});

Route::get('contact/', function () {
    return view('contact');
});
```
