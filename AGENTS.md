# Agent Guidelines: Laravel Trailing Slash

This repository is a lightweight, standalone Laravel package (`fsasvari/laravel-trailing-slash`) that provides automatic trailing slash redirection and URL formatting.

## Architecture & Scope

* **Package Nature:** Standalone Composer library, not a full Laravel application (no `app/`, no `artisan` CLI).
* **Core Components:**
  * `src/UrlGenerator.php`: Extends `Illuminate\Routing\UrlGenerator` to append trailing slashes and handle signed URLs.
  * `src/RoutingServiceProvider.php`: Replaces Laravel's default `url` singleton with custom generator.
* **Auto-Discovery:** Registered under `extra.laravel.providers` in `composer.json`.

## Technical Requirements & Standards

* **PHP Support:** `^8.3`
* **Laravel Compatibility:** `^^13.0` (`illuminate/routing`, `illuminate/database`)
* **Strict Typing:** All PHP files must declare `declare(strict_types=1);`.
* **Code Style:** Laravel PER / PSR-12 standard enforced via **Laravel Pint**.
* **Static Analysis:** PHPStan level 9 (`phpstan.neon.dist`).

## Development & Testing Workflow

```bash
# Run unit test suite
vendor/bin/phpunit

# Check and fix code style
vendor/bin/pint --test  # check only
vendor/bin/pint         # auto-fix

# Run static analysis
vendor/bin/phpstan analyse

# Test lowest / stable dependency matrices
composer update --prefer-lowest
composer update --prefer-stable
```
