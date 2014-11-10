<?php namespace Kosiec\LaravelBower\Formatters;
use Kosiec\LaravelBower\TagGenerator;

/**
 * Created by PhpStorm.
 * User: Chad
 * Date: 11/9/2014
 * Time: 6:34 AM
 */

class JavascriptTagGenerator extends TagGenerator {

	const FORMAT = '<script src="%s"></script>';

	public function getExtension()
	{
		return 'js';
	}

	public function generateTag($path)
	{
		return sprintf(self::FORMAT, $path);
	}

}