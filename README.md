# Laravel Trailing Slash

Adds url formatting and redirection with trailing slash to Laravel framework versions 10.x, 9.x, 8.x, 7.x, 6.x and 5.x.

[![Build For Laravel](https://img.shields.io/badge/Built_for-Laravel-orange.svg)](https://styleci.io/repos/79834672)
[![Latest Stable Version](https://poser.pugx.org/fsasvari/laravel-trailing-slash/v/stable)](https://packagist.org/packages/fsasvari/laravel-trailing-slash)
[![Latest Unstable Version](https://poser.pugx.org/fsasvari/laravel-trailing-slash/v/unstable)](https://packagist.org/packages/fsasvari/laravel-trailing-slash)
[![Total Downloads](https://poser.pugx.org/fsasvari/laravel-trailing-slash/downloads)](https://packagist.org/packages/fsasvari/laravel-trailing-slash)
[![License](https://poser.pugx.org/fsasvari/laravel-trailing-slash/license)](https://packagist.org/packages/fsasvari/laravel-trailing-slash)

## Compatibility Chart

| Laravel Trailing Slash                                                | Laravel | PHP       |
|-----------------------------------------------------------------------|---------|-----------|
| [5.x](https://github.com/fsasvari/laravel-trailing-slash/tree/v5.0.0) | 10.x    | 8.1+      |
| [4.x](https://github.com/fsasvari/laravel-trailing-slash/tree/v4.0.0) | 9.x     | 8.0.2+    |
| [3.x](https://github.com/fsasvari/laravel-trailing-slash/tree/v3.0.0) | 8.x     | 7.3+/8.0+ |
| [2.x](https://github.com/fsasvari/laravel-trailing-slash/tree/v2.0.2) | 7.x     | 7.3+      |
| [1.x](https://github.com/fsasvari/laravel-trailing-slash/tree/v1.1.0) | 6.x     | 7.2+      |
| [0.3.x](https://github.com/fsasvari/laravel-trailing-slash/tree/0.3)  | 5.7-5.8 | 7.1.3+    |
| [0.2.x](https://github.com/fsasvari/laravel-trailing-slash/tree/0.2)  | 5.6     | 7.1.3+    |
| [0.1.x](https://github.com/fsasvari/laravel-trailing-slash/tree/0.1)  | 5.5     | 7.0.0+    |

## Installation

### Step 1: Install package

To get started with Laravel Trailing Slash, use Composer command to add the package to your composer.json project's dependencies:

```
composer require fsasvari/laravel-trailing-slash
```

Or add it directly by copying next line into composer.json:

```
"fsasvari/laravel-trailing-slash": "5.*"
```

### Step 2: Service Provider

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

### Step 3: .htaccess

If you are using apache, copy following redirection code from `public/.htaccess` to your own project:

```
<IfModule mod_rewrite.c>
    # Redirect To Trailing Slashes If Not A Folder Or A File...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_URI} !(/$|\.)
    RewriteRule (.*) %{REQUEST_URI}/ [R=301,L]
</IfModule>
```

### Step 4: Routes

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

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Notice

There is a problem with overriding Laravel `Paginator` and `LengthAwarePaginator` classes. So, every time you use `paginate()` method on your models, query builders etc., you must set current path for pagination links. Example:

```php
$texts = Text::where('is_active', 1)->paginate();
$texts->setPath(URL::current());

$texts->links();
```

## Licence

MIT Licence. Refer to the [LICENSE](https://github.com/fsasvari/laravel-trailing-slash/blob/master/LICENSE.md) file to get more info.

## Author

Frano Šašvari

Email: sasvari.frano@gmail.com
