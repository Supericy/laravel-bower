laravel-bower
=============

Automatically load all HTML dependencies that were installed via Bower (ie. js/css assets)


## Installation

Require this package with composer:

    composer require kosiec/laravel-bower

After updating composer, add the ServiceProvider to the providers array in app/config/app.php

    'Kosiec\LaravelBower\LaravelBowerServiceProvider'