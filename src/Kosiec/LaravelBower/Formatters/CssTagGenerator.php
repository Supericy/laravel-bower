<?php namespace Kosiec\LaravelBower\Formatters;
use Kosiec\LaravelBower\TagGenerator;

/**
 * Created by PhpStorm.
 * User: Chad
 * Date: 11/9/2014
 * Time: 6:35 AM
 */

class CssTagGenerator extends TagGenerator {

	const FORMAT = '<link rel="stylesheet" type="text/css" href="http://homestead.app/dir/1/file.css" />';

	public function getExtension()
	{
		return 'css';
	}

	public function generateTag($path)
	{
		return sprintf(self::FORMAT, $path);
	}
}