<?php namespace Kosiec\LaravelBower;
use Illuminate\Support\Collection;

/**
 * Created by PhpStorm.
 * User: Chad
 * Date: 11/9/2014
 * Time: 1:14 AM
 */

class BowerDependencyManager {

	private $dependenciesDirectory;
	private $extensions;

	public function __construct($dependenciesDirectory, array $extensions = ['js', 'css'])
	{
		$this->dependenciesDirectory = $dependenciesDirectory;
		$this->extensions = $extensions;
	}

	/**
	 * @return Collection
	 */
	public function gatherDependencies()
	{
		$dependencies = new Collection();;

		foreach ($this->findAllBowerFiles() as $path)
		{
			$dependencies = $dependencies->merge($this->parseDependenciesFromBowerFile($path));
		}

		return $dependencies;
	}

	/**
	 * @param $something
	 * @return Collection
	 */
	private function createCollection($something)
	{
		return new Collection(is_array($something) ? $something : [$something]);
	}

	/**
	 * @param string $directory
	 * @param Collection $paths
	 * @return Collection
	 */
	private function createDependencyCollectionFromPaths($directory, Collection $paths)
	{
		return $paths->map(function ($path) use ($directory)
		{
			return new Dependency($directory . $path);
		});
	}

	private function parseDependenciesFromBowerFile($path)
	{
		// includes trailing forward slash
		$directory = substr($path, 0, strrpos($path, '/') + 1);

		$data = json_decode(file_get_contents($path), true);

		$dependencies = $this->createDependencyCollectionFromPaths($directory, $this->createCollection($data['main']));

		return $dependencies->filter(function ($dependency) { return $this->hasValidExtension($dependency); });
	}

	private function hasValidExtension(Dependency $dependency)
	{
		return in_array($dependency->getExtension(), $this->extensions);
	}

	private function findAllBowerFiles()
	{
		return glob(rtrim($this->dependenciesDirectory, '/\\') . '/*/bower.json');
	}

}