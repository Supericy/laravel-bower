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
	 * @var TagGenerator[]
	 */
	private $generators = [];

	/**
	 * @var string
	 */
	private $baseUrl;
	private $bowerComponentDir;

	/**
	 * @param $baseUrl
	 * @param $bowerComponentDir
	 */
	public function __construct($baseUrl, $bowerComponentDir)
	{
		$this->baseUrl = rtrim($baseUrl, '/') . '/';
		$this->bowerComponentDir = rtrim($bowerComponentDir, '/') . '/';
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
	 * @return array Extensions currently supported/added
	 */
	public function getSupportedExtensions()
	{
		$extensions = [];
		foreach ($this->generators as $generator)
		{
			$extensions[] = $generator->getExtension();
		}
		return $extensions;
	}

	/**
	 * @param Collection $components
	 * @param bool $flatten
	 * @return Collection
	 */
	public function generateAll(Collection $components, $flatten = true)
	{
		$collection = $components->map(function (Component $component)
		{
			return $this->generateComponentTags($component);
		});

		if ($flatten)
			$collection = $this->flatten($collection);

		return $collection;
	}

	private function flatten(Collection $components)
	{
		$tags = new Collection();

		$components->each(function (Collection $componentTags) use (&$tags)
		{
			$tags = $tags->merge($componentTags);
		});

		return $tags;
	}

	/**
	 * @param Component $component
	 * @return Component
	 */
	public function generateComponentTags(Component $component)
	{
		return $this->filterByExtension($component->getPaths())
			->map(function ($path) use ($component)
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

		return $generator->generateTag($this->baseUrl . $this->bowerComponentDir . $componentName . '/' . $path);
	}

	private function getExtension($path)
	{
		return substr($path, strrpos($path, '.') + 1);
	}

	private function filterByExtension(Collection $paths)
	{
		$validExtensions = array_keys($this->generators);

		return $paths->filter(function ($path) use ($validExtensions)
		{
			return in_array($this->getExtension($path), $validExtensions);
		});
	}

}