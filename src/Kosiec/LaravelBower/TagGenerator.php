<?php namespace Kosiec\LaravelBower;

/**
 * Created by PhpStorm.
 * User: Chad
 * Date: 11/9/2014
 * Time: 6:33 AM
 */

class TagGenerator {

	private $extension;
	private $format;

	public function __construct($extension, $format)
	{
		$this->extension = $extension;
		$this->format = $format;
	}

	public function getExtension()
	{
		return $this->extension;
	}

	public function generateTag($path)
	{
		return sprintf($this->format, $path);
	}

} 