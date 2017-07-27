# Laravel Trailing Slash

Adds redirection with trailing slash in Laravel.

<p align="center">
<a href="https://styleci.io/repos/69124179"><img src="https://img.shields.io/badge/Built_for-Laravel-orange.svg" alt="Build For Laravel"></a>
<a href="https://packagist.org/packages/fsasvari/laravel-trailing-slash"><img src="https://poser.pugx.org/fsasvari/laravel-trailing-slash/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/fsasvari/laravel-trailing-slash"><img src="https://poser.pugx.org/fsasvari/laravel-trailing-slash/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/fsasvari/laravel-trailing-slash"><img src="https://poser.pugx.org/fsasvari/laravel-trailing-slash/license.svg" alt="License"></a>
</p>

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
