# BUGS


# CONTINUE

- drop inertia
- adjust response from index()  to match next.js
  $this->apiWrapper->data([
  'results' => $results,
  'filter' => array_filter($validated['filter']),
  'count' => count($results),
  'limit' => $validated['limit'],
  'page' => $validated['page'],
  ]);

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
