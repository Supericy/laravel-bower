<?php namespace Kosiec\LaravelBower;
use Illuminate\Support\Collection;

/**
 * Created by PhpStorm.
 * User: Chad
 * Date: 11/9/2014
 * Time: 6:30 AM
 */

class HtmlGenerator {

	/**
	 * @var array
	 */
	private $generators = [];

	/**
	 * @var string
	 */
	private $baseUrl;

	/**
	 * @param $baseUrl
	 */
	public function __construct($baseUrl)
	{
		$this->baseUrl = rtrim($baseUrl, '/') . '/';
	}

	/**
	 * @param TagGenerator $generator
	 */
	public function add(TagGenerator $generator)
	{
		$this->generators[$generator->getExtension()] = $generator;
	}

	/**
	 * @param TagGenerator $generator
	 */
	public function remove(TagGenerator $generator)
	{
		unset($this->generators[$generator->getExtension()]);
	}

	/**
	 * @param Collection $components
	 * @return Collection
	 */
	public function generateAll(Collection $components)
	{
		return $components->map(function (Component $component)
		{
			return $this->generateComponentTags($component);
		});
	}

	/**
	 * @param Component $component
	 * @return Component
	 */
	public function generateComponentTags(Component $component)
	{
		return $component->getPaths()->map(function ($path) use ($component)
		{
			return $this->generateTag($component->getName(), $path);
		});
	}

	/**
	 * @param $componentName
	 * @param $path
	 * @return string
	 * @throws GeneratorException
	 */
	public function generateTag($componentName, $path)
	{
		$ext = $this->getExtension($path);

		if (!array_key_exists($ext, $this->generators))
			throw new GeneratorException('No formatter found for extension "' . $ext . '"');

		$generator = $this->generators[$ext];

		return $generator->generateTag($this->baseUrl . $componentName . '/' . $path);
	}

	private function getExtension($path)
	{
		return substr($path, strrpos($path, '.') + 1);
	}

}