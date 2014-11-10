<?php namespace Kosiec\LaravelBower;
use Illuminate\Support\Collection;

/**
 * Created by PhpStorm.
 * User: Chad
 * Date: 11/9/2014
 * Time: 6:30 AM
 */

class HtmlGenerator {

	private $generators = [];
	private $baseUrl;

	public function __construct($baseUrl)
	{
		$this->baseUrl = rtrim($baseUrl, '/') . '/';
	}

	public function add(TagGenerator $generator)
	{
		$this->generators[$generator->getExtension()] = $generator;
	}

	public function remove(TagGenerator $generator)
	{
		unset($this->generators[$generator->getExtension()]);
	}

	public function generateTag(Dependency $dependency)
	{
		if (!array_key_exists($dependency->getExtension(), $this->generators))
			throw new GeneratorException('No formatter found for extension "' . $dependency->getExtension() . '"');

		$generator = $this->generators[$dependency->getExtension()];

		return $generator->generateTag($this->baseUrl . $dependency->getPath());
	}

	public function generateTags(Collection $dependencies)
	{
		return $dependencies->map(function (Dependency $dependency)
		{
			return $this->generateTag($dependency);
		});
	}

}