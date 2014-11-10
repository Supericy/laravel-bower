<?php namespace Kosiec\LaravelBower;
use Illuminate\Support\Collection;

/**
 * Created by PhpStorm.
 * User: Chad
 * Date: 11/9/2014
 * Time: 4:08 AM
 */

class Component {

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $directory;

	/**
	 * @var string
	 */
	private $paths;

	/**
	 * @var Collection
	 */
	private $dependencies;

	/**
	 * @param $name
	 * @param Collection $paths
	 * @param Collection $dependencies
	 */
	public function __construct($name, Collection $paths, Collection $dependencies)
	{
		$this->name = $name;
		$this->paths = $paths;
		$this->dependencies = $dependencies;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getPaths()
	{
		return $this->paths;
	}

	public function getDependencies()
	{
		return $this->dependencies;
	}

}