<?php namespace Kosiec\LaravelBower;
/**
 * Created by PhpStorm.
 * User: Chad
 * Date: 11/9/2014
 * Time: 4:08 AM
 */

class Dependency {

	/**
	 * @var string $path
	 */
	private $path;

	/**
	 * @param string $path
	 */
	public function __construct($path)
	{
		$this->path = $path;
	}

	public function getPath()
	{
		return $this->path;
	}

	public function getExtension()
	{
		return substr($this->path, strrpos($this->path, '.') + 1);
	}

}