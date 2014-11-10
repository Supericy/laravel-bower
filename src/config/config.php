<?php
/**
 * Created by PhpStorm.
 * User: Chad
 * Date: 11/9/2014
 * Time: 6:14 PM
 */

return [

	/*
	|--------------------------------------------------------------------------
	| Bower Component Directory
	|--------------------------------------------------------------------------
	|
	| Let the manager know where you have your scripts currently installed.
	|
	| Note: These files must be in your web root (ie. your public/ directory),
	| otherwise they will not be accessible. This dir is relative to your
	| public directory.
	|
	*/
	'bower_component_dir' => 'bower_components',

	/*
	|--------------------------------------------------------------------------
	| Base URL for all dependencies
	|--------------------------------------------------------------------------
	|
	| The base URL for your website, eg. http://localhost.
	|
	| Note that you can also leave it as '', which makes your scripts relative
	| to the base URL automatically.
	|
	*/
	'base_url' => '',

	/*
	|--------------------------------------------------------------------------
	| Default Tag Generators
	|--------------------------------------------------------------------------
	|
	*/
	'generators' => [
		[
			'ext' => 'js',
			'tag' => '<script src="%s"></script>'
		],
		[
			'ext' => 'css',
			'tag' => '<link rel="stylesheet" type="text/css" href="%s" />'
		]
	]

];