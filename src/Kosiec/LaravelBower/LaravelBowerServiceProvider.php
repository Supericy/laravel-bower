<?php namespace Kosiec\LaravelBower;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Collection;

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
			return new BowerComponentManager($app['config']->get('laravel-bower::bower_component_dir'));
		});

		$this->app->bind('Kosiec\LaravelBower\HtmlGenerator', function ($app)
		{
			$generator = new HtmlGenerator(
				$app['config']->get('laravel-bower::base_url'),
				$app['config']->get('laravel-bower::bower_component_dir'));

			$generatorConfig = $app['config']->get('laravel-bower::generators');

			array_walk($generatorConfig, function ($entry) use ($generator)
			{
				$generator->add(new TagGenerator($entry['ext'], $entry['tag']));
			});

			return $generator;
		});

		\Blade::extend(function ($view, $compiler) use ($app)
		{
			/** @var BowerComponentManager $manager */
			$manager = $app->make('Kosiec\LaravelBower\BowerComponentManager');

			/** @var HtmlGenerator $generator */
			$generator = $app->make('Kosiec\LaravelBower\HtmlGenerator');

			$components = $manager->gatherComponents();
			$tags = $generator->generateAll($components);

//			$pattern = $compiler->createMatcher('includeBowerDependencies');
			$pattern = $compiler->createMatcher($app['config']->get('laravel-bower::blade_tag'));

			return preg_replace($pattern, $tags->reduce(function ($left, $right)
			{
				return $left . "\n" . $right;
			}, ''), $view);
		});
	}

}