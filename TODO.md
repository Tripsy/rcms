# BUGS


# CONTINUE

- unit test

if ( ! function_exists('isValidHttpResponseCode')) {
    function isValidHttpResponseCode(int $code): bool
    {
        return array_key_exists($code, Response::$statusTexts);
    }
}

if ( ! function_exists('returnValidHttpResponseCode')) {
    function returnValidHttpResponseCode(int $providedCode, int $fallbackCode): int
    {
        return isValidHttpResponseCode($providedCode) ? $providedCode : $fallbackCode;
    }
}

- https://inertiajs.com/upgrade-guide
- https://medium.com/@demian.kostelny/laravel-inertia-js-react-simple-crud-example-2e0d167365d

# TEST

- stubs

# NICE TO HAVE

- postman documentation & tests
- unit tests

# PACKAGES

Excel

# IDEAS

- https://github.com/spatie/laravel-responsecache

# READ

https://inertiajs.com/upgrade-guide

# NOTES

[Laravel 11 Upgrade - Commit](https://github.com/Tripsy/rcms/commit/571aa4e950c59237a6f08075b1bbf17f44323f57)

php artisan vendor:publish --tag=sanctum-migrations
php artisan vendor:publish --tag=telescope-migrations
