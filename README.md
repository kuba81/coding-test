# Coding Test

## Changelog

### 2019-03-09

- Removed old cache implementation where all cached exchange rates were stored under one key
- Refactored abstractions and implementations to allow caching of rates on a per-pair basis
- Implemented pair-based cache using [Eloquent](https://laravel.com/docs/6.x/eloquent)
- Implemented an optimization where the caching rate provider will use a inverse rate if possible

  As mentioned in the corresponding commit, this is implemented by doing an additional cache lookup
  instead of storing the inverse value under the same key, but it is what the specification asked for.
- Added SQLite configuration for tests (requires php*-sqlite extension to run)
- Added additional setup requirements to the readme file


## Summary

The API was developed using PHP’s inbuilt server. I used PHP7.3, but I don’t believe I used any features
that would not work in PHP7.2. However, I don’t believe the API would work with PHP7.1.

The framework used is [Lumen](https://lumen.laravel.com/).
  
## Setup
 
In order to set up the project, please follow these steps:
- Make sure your PHP installation has all the required extensions (you’ll need _php*-mbstring_,
  _php*-mysql_. If you want to run tests, you’ll also need _php*-sqlite_ and _php*-xml_).
- Make sure you have an instance of MySQL set up and running
- Copy the [.env.example](.env.example) file from the root of to project to [.env](.env)
- Open the [.env](.env) file
  - change `DB_*` variables to point to your MySQL installation
  - change the `APP_URL` variable if necessary (this will be the root of your project)
  - update `CACHE_TTL_SECONDS` if you want to change the cache duration
- Run `composer install` from the root of the project. If any other PHP extensions are missing,
  install them and try again
- Run `php artisan migrate` from the root of the project
- Start the server by running `php -S localhost:8000 php-server-router.php` (or whatever host:port combinatin
you specified in the [.env](.env) file)

## Code

This project uses a fairly standard code layout. Most business logic can be found in [app/Domain](app/Domain),
and the controller logic can be found in [app/Http/Controllers](app/Http/Controllers).

## Tests

To execute the tests, run `./vendor/bin/phpunit` from the root of the project.

## Notes

Couple of things I’d like to point out:
- You should be able to view the changes commit by commit. I kept them relatively small to make it easy to review one
at the time. The code itself contains very few comments, but some commit messages will contain extra information regarding
implementation details
- The specification suggests the frontend page should not be edited, but I had to change how the response is handled as it was
not being parsed correctly. I also added extra logic to handle 4xx and 5xx responses
- I avoided adding docblocks if I felt they added no information on top of what can be derived from method signatures etc. 
- I normally wouldn’t implement any money-related logic using floats—I would instead use integers to represent the minor currency units,
e.g. pennies—but the specification was very clear on the subject, so I sticked to floating-point numbers
