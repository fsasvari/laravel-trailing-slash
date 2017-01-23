# Laravel Trailing Slash

Adds redirection with trailing slash in Laravel.

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

## Basic Usage


