<?php namespace Kosiec\LaravelBower;
use Illuminate\Support\ServiceProvider;
use Kosiec\LaravelBower\Formatters\CssTagGenerator;
use Kosiec\LaravelBower\Formatters\JavascriptTagGenerator;

/**
 * Created by PhpStorm.
 * User: Chad
 * Date: 11/9/2014
 * Time: 6:58 AM
 */

class LaravelBowerServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	public function boot()
	{
		$this->package('kosiec/laravel-bower');
	}

	public function register()
	{
		$app = $this->app;

		$this->app->bind('Kosiec\LaravelBower\BowerComponentManager', function ($app)
		{
			// @TODO load directory from config
			return new BowerComponentManager($app['config']->get('laravel-bower::bower_component_dir'));
		});

		$this->app->bind('Kosiec\LaravelBower\HtmlGenerator', function ($app)
		{
			// @TODO load base url from somewhere
			$generator = new HtmlGenerator($app['config']->get('laravel-bower::base_url'));

			array_walk($app['config']->get('laravel-bower::generators'), function ($entry) use ($generator)
			{
				$generator->add(new TagGenerator($entry['ext'], $entry['tag']));
			});

			return $generator;
		});

		\Blade::extend(function ($view, $compiler) use ($app)
		{
			$pattern = $compiler->createMatcher('includeBowerDependencies');

			$dependencies = $app->make('Kosiec\LaravelBower\BowerComponentManager')->gatherDependencies();

			$generator = $app->make('Kosiec\LaravelBower\HtmlGenerator');

			$tags = $generator->generateTags($dependencies);

			return preg_replace($pattern, $tags->reduce(function ($left, $right)
			{
				return $left . "\n" . $right;
			}, ''), $view);
		});
	}

}