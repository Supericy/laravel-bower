<?php
use Illuminate\Support\Collection;
use Kosiec\LaravelBower\BowerDependencyManager;
use Kosiec\LaravelBower\Dependency;
use Kosiec\LaravelBower\Formatters\CssTagGenerator;
use Kosiec\LaravelBower\Formatters\JavascriptTagGenerator;
use Kosiec\LaravelBower\HtmlGenerator;

/**
 * Created by PhpStorm.
 * User: Chad
 * Date: 11/9/2014
 * Time: 1:14 AM
 */

class BowerDependencyManagerTestCase extends AbstractTestCase {

	public function testGetAllDependenciesFromBower1()
	{
		/** @var BowerDependencyManager $dependencyManager */
		$dependencyManager = new BowerDependencyManager('tests/mock_bower_1/');

		$expected = new Collection([
			new Dependency('tests/mock_bower_1/test1/d1.js'),
			new Dependency('tests/mock_bower_1/test2/d2.js'),
			new Dependency('tests/mock_bower_1/test2/d3.css')
		]);

		$this->assertEquals($expected, $dependencyManager->gatherDependencies());
	}

	public function testGetAllDependenciesFromBower2()
	{
		/** @var BowerDependencyManager $dependencyManager */
		$dependencyManager = new BowerDependencyManager('tests/mock_bower_2/');

		$dependencies = $dependencyManager->gatherDependencies();

		$expected = new Collection([
			new Dependency('tests/mock_bower_2/angular-route/./angular-route.js'),
			new Dependency('tests/mock_bower_2/angular/./angular.js'),
			new Dependency('tests/mock_bower_2/bootstrap/./dist/css/bootstrap.css'),
			new Dependency('tests/mock_bower_2/bootstrap/./dist/js/bootstrap.js'),
			new Dependency('tests/mock_bower_2/jquery/dist/jquery.js')
		]);

		$this->assertEquals($expected->count(), count($dependencies));
		$this->assertEquals($expected, $dependencies);
	}

	public function testGeneratingTags()
	{
		$htmlGenerator = new HtmlGenerator('http://homestead.app');

		$htmlGenerator->add(new JavascriptTagGenerator());
		$htmlGenerator->add(new CssTagGenerator());

		$javascriptDependency = new Dependency('dir/1/file.js');
		$cssDependency = new Dependency('dir/1/file.css');
		$unsupportedDependency = new Dependency('dir/1/file.unsupportedext');

		$this->assertSame('<script src="http://homestead.app/dir/1/file.js"></script>', $htmlGenerator->generateTag($javascriptDependency));
		$this->assertSame('<link rel="stylesheet" type="text/css" href="http://homestead.app/dir/1/file.css" />', $htmlGenerator->generateTag($cssDependency));

		$this->assertException(function () use ($htmlGenerator, $unsupportedDependency) { $htmlGenerator->generateTag($unsupportedDependency); }, '\Kosiec\LaravelBower\GeneratorException');
	}

}
 