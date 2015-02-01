Laravel Bower Component
=============

Automatically load all frontend dependencies that were installed via Bower (ie. js/css assets). Components will automatically
be ordered so that a component's required dependencies are loaded ahead of them.


## Installation

Require this package with composer:

    composer require kosiec/laravel-bower

After updating composer, add the LaravelBowerServiceProvider to your providers array in app/config/app.php

    'Kosiec\LaravelBower\LaravelBowerServiceProvider',

Out of the box, the service will look inside `public/bower_components` directory for all of your frontend assets,
however, if you wish to change the configuration, you can public the config via:

    php artisan config:publish kosiec/laravel-bower

(Keep in mind, it's best to keep your bower components inside your public folder, as anything outside of it may not be
accessible from the web.

## Usage

Simply add your blade tag (default is `includeBowerDependencies`) to your master template, either in your header or at
the bottom of your body. Example:

    // master-layout.blade.php
    <html>
        <head>
            @includeBowerDependencies()
        </head>

        <body> ... </body
    <html>

If you wish to use a different blade tag, you can change it within the config.
