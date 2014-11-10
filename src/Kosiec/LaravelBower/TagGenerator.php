<?php namespace Kosiec\LaravelBower;

/**
 * Created by PhpStorm.
 * User: Chad
 * Date: 11/9/2014
 * Time: 6:33 AM
 */

abstract class TagGenerator {

	abstract public function getExtension();

	abstract public function generateTag($path);

} 