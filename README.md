# Coding Test

The API was developed using PHP’s inbuilt server. I used PHP7.3, but I don’t believe I used any features
that would not work in PHP7.2. However, I don’t believe the API would work with PHP7.1.

The framework used is [Lumen](https://lumen.laravel.com/).

## Setup
 
In order to set up the project, please follow these steps:
- Run `composer install` from the root of the project. If any PHP extensions are missing,
install them and try again
- Copy the [.env.example](.env.example) file from the root of to project to [.env](.env)
- Open the [.env](.env) file and change the `APP_URL` variable if necessary
- Start the server by running `php -S localhost:8000 php-server-router.php` (or whatever host:port combinatin
you specified in the [.env](.env) file)

## Code

This project uses a fairly standard code layout. Most business logic can be found in [app/Domain/Exchange](app/Domain/Exchange),
and the controller logic can be found in [app/Http/Controllers](app/Http/Controllers).

## Tests

To execute the tests, run `./vendor/bin/phpunit` from the root of the project.

## Setting up database cache

Although the project defaults to file-based cache storage, it is possible to configure it
to use a MySQL database instead.

In order to enable this feature, you’ll need to add the relevant database information to the [.env](.env) file.

Once that’s done, you’ll need to run `php artisan migrate` to create the cache table in the database, and then change
the `cache_driver` variable in [.env](.env) to `database`—see [Lumen’s documentation](https://laravel.com/docs/6.x/cache#configuration)
for further information.

## Notes

Couple of things I’d like to point out:
- You should be able to view the changes commit by commit. I kept them relatively small to make it easy to review one
at the time. The code itself contains very few comments, but some commit messages will contain extra information regarding
implementation details
- The specification suggests the frontend page should not be edited, but I had to change how the response is handled as it was
not being parsed correctly. I also added extra logic to handle 4xx and 5xx responses
- I avoided adding docblocks if I felt they added no information on top of what can be derived from method signatures etc.
- I implemented caching using a single cache record instead of one record per pair, as it was a lot easier 
- I normally wouldn’t implement any money-related logic using floats—I would instead use integers to represent the minor currency units,
e.g. pennies—but the specification was very clear on the subject, so I sticked to floating-point numbers
