# Laravel Trailing Slash

Adds redirection with trailing slash in Laravel.

[![Build For Laravel](https://img.shields.io/badge/Built_for-Laravel-orange.svg)](https://styleci.io/repos/79834672)
[![Latest Stable Version](https://poser.pugx.org/fsasvari/laravel-trailing-slash/v/stable)](https://packagist.org/packages/fsasvari/laravel-trailing-slash)
[![Latest Unstable Version](https://poser.pugx.org/fsasvari/laravel-trailing-slash/v/unstable)](https://packagist.org/packages/fsasvari/laravel-trailing-slash)
[![Total Downloads](https://poser.pugx.org/fsasvari/laravel-trailing-slash/downloads)](https://packagist.org/packages/fsasvari/laravel-trailing-slash)
[![License](https://poser.pugx.org/fsasvari/laravel-trailing-slash/license)](https://packagist.org/packages/fsasvari/laravel-trailing-slash)

## Installation

### Step 1: Install package

To get started with Laravel Trailing Slash, use Composer command to add the package to your composer.json project's dependencies:

```
composer require fsasvari/laravel-trailing-slash
```

Or add it directly by copying next line into composer.json:

```
"fsasvari/laravel-trailing-slash": "0.2.*"
```

### Step 2: index file

In `public/index.php` file change base `$request`:

```php
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);
```

With `$request` from LaravelTrailingSlash:

```php
$response = $kernel->handle(
    $request = LaravelTrailingSlash\Request::capture()
);
```

### Step 3: Service Provider

After installing the Laravel Trailing Slash library, register the `LaravelTrailingSlash\RoutingServiceProvider` in your `config/app.php` configuration file:

```php
'providers' => [
    // Application Service Providers...
    // ...

    // Other Service Providers...
    LaravelTrailingSlash\RoutingServiceProvider::class,
    // ...
],
```

### Step 4: .htaccess

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

### Step 5: Routes

In routes/web.php, you must use routes with trailing slashes now:

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

## Usage

Every time you use some Laravel redirect function, trailing slash ("/") will be applied at the end of url.

```php
return redirect('about/');

return back()->withInput();

return redirect()->route('text', ['id' => 1]);

return redirect()->action('IndexController@about');
```

## Licence

MIT Licence. Refer to the [LICENSE](https://github.com/fsasvari/laravel-trailing-slash/blob/master/LICENSE.md) file to get more info.

## Author

Frano Šašvari

Email: sasvari.frano@gmail.com
