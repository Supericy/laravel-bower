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

		$this->app->bind('Kosiec\LaravelBower\BowerDependencyManager', function ($app)
		{
			// @TODO load directory from config
			return new BowerDependencyManager($app['config']->get('laravel-bower::bower_directory'));
		});

		$this->app->bind('Kosiec\LaravelBower\HtmlGenerator', function ($app)
		{
			// @TODO load base url from somewhere
			$generator = new HtmlGenerator($app['config']->get('laravel-bower::base_url'));

			$generator->add(new JavascriptTagGenerator());
			$generator->add(new CssTagGenerator());

			return $generator;
		});

		\Blade::extend(function ($view, $compiler) use ($app)
		{
			$pattern = $compiler->createMatcher('includeBowerDependencies');

			$dependencies = $app->make('Kosiec\LaravelBower\BowerDependencyManager')->gatherDependencies();

			$generator = $app->make('Kosiec\LaravelBower\HtmlGenerator');

			$tags = $generator->generateTags($dependencies);

			return preg_replace($pattern, $tags->reduce(function ($left, $right)
			{
				return $left . "\n" . $right;
			}, ''), $view);
		});
	}

}