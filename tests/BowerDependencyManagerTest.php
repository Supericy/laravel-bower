<?php
use Illuminate\Support\Collection;
use Kosiec\LaravelBower\BowerComponentManager;
use Kosiec\LaravelBower\Component;
use Kosiec\LaravelBower\HtmlGenerator;
use Kosiec\LaravelBower\TagGenerator;

/**
 * Created by PhpStorm.
 * User: Chad
 * Date: 11/9/2014
 * Time: 1:14 AM
 */

class BowerDependencyManagerTestCase extends AbstractTestCase {

	public function testGetAllDependenciesFromBower1()
	{
		/** @var BowerComponentManager $dependencyManager */
		$dependencyManager = new BowerComponentManager('tests/mock_bower_1/');

		$expected = new Collection([
			new Component('test1', Collection::make(['d1.js']), Collection::make([])),
			new Component('test2', Collection::make(['d2.js', 'd3.css']), Collection::make([]))
		]);

		$this->assertEquals($expected, $dependencyManager->gatherComponents());
	}

	public function testGetAllDependenciesFromBower2()
	{
		/** @var BowerComponentManager $componentManager */
		$componentManager = new BowerComponentManager('tests/mock_bower_2/');

		$components = $componentManager->gatherComponents();

		$expected = new Collection([
			new Component('angular', Collection::make(['./angular.js']), Collection::make([])),
			new Component('angular-route', Collection::make(['./angular-route.js']), Collection::make(['angular'])),

			new Component('jquery', Collection::make(['dist/jquery.js']), Collection::make([])),

			new Component('bootstrap', Collection::make([
				'./dist/css/bootstrap.css',
				'./dist/js/bootstrap.js'
			]), Collection::make(['jquery']))
		]);

		$this->assertEquals($expected->count(), count($components));
		$this->assertEquals($expected, $components);
	}

	public function testGeneratingTags()
	{
		$htmlGenerator = new HtmlGenerator('http://homestead.app');

		$htmlGenerator->add(new TagGenerator('js', '<script src="%s"></script>'));
		$htmlGenerator->add(new TagGenerator('css', '<link rel="stylesheet" type="text/css" href="%s" />'));

		$this->assertSame('<script src="http://homestead.app/test1/file1.js"></script>', $htmlGenerator->generateTag('test1', 'file1.js'));
		$this->assertSame('<link rel="stylesheet" type="text/css" href="http://homestead.app/test2/file2.css" />', $htmlGenerator->generateTag('test2', 'file2.css'));

		$this->assertException(function () use ($htmlGenerator) { $htmlGenerator->generateTag('test3', 'file3.unsupported'); }, '\Kosiec\LaravelBower\GeneratorException');
	}

	public function testGeneratingAllTagsForComponent()
	{
		$htmlGenerator = new HtmlGenerator('http://homestead.app');

		$htmlGenerator->add(new TagGenerator('js', '%s[JS]'));
		$htmlGenerator->add(new TagGenerator('css', '%s[CSS]'));

		$components = new Collection([
			new Component('testcomp1', Collection::make(['testfile1.js', 'testfile2.css']), Collection::make([])),
			new Component('testcomp2', Collection::make(['testfile1.js', 'testfile2.css']), Collection::make([])),
		]);

		$expected = new Collection([
			new Collection([
				'http://homestead.app/testcomp1/testfile1.js[JS]',
				'http://homestead.app/testcomp1/testfile2.css[CSS]',
			]),
			new Collection([
				'http://homestead.app/testcomp2/testfile1.js[JS]',
				'http://homestead.app/testcomp2/testfile2.css[CSS]',
			]),
		]);

		$expectedFlattened = new Collection([
			'http://homestead.app/testcomp1/testfile1.js[JS]',
			'http://homestead.app/testcomp1/testfile2.css[CSS]',
			'http://homestead.app/testcomp2/testfile1.js[JS]',
			'http://homestead.app/testcomp2/testfile2.css[CSS]',
		]);

		$this->assertEquals($expected, $htmlGenerator->generateAll($components, false));
		$this->assertEquals($expectedFlattened, $htmlGenerator->generateAll($components, true));
		$this->assertEquals($expectedFlattened, $htmlGenerator->generateAll($components));
	}

	public function testGenerateTagsForComponent()
	{
		$htmlGenerator = new HtmlGenerator('http://homestead.app');

		$htmlGenerator->add(new TagGenerator('js', '<script src="%s"></script>'));
		$htmlGenerator->add(new TagGenerator('css', '<link rel="stylesheet" type="text/css" href="%s" />'));

		$component = new Component('testcomp', Collection::make(['testfile1.js', 'testfile2.css']), Collection::make([]));

		$expected = new Collection([
			'<script src="http://homestead.app/testcomp/testfile1.js"></script>',
			'<link rel="stylesheet" type="text/css" href="http://homestead.app/testcomp/testfile2.css" />'
		]);

		$this->assertEquals($expected, $htmlGenerator->generateComponentTags($component));
	}

}
 