<?php namespace Kosiec\LaravelBower;
use Illuminate\Support\Collection;

/**
 * Created by PhpStorm.
 * User: Chad
 * Date: 11/9/2014
 * Time: 1:14 AM
 */

class BowerComponentManager {

	private $componentDirectory;
	private $extensions;

	public function __construct($componentDirectory, array $extensions = ['js', 'css'])
	{
		$this->componentDirectory = $componentDirectory;
		$this->extensions = $extensions;
	}

	/**
	 * @return Collection
	 */
	public function gatherComponents()
	{
		$components = [];

		foreach ($this->findAllBowerFiles() as $path)
		{
			$component = $this->createComponentFromBowerFile($path);;

			$components[$component->getName()] = $component;
		}

		return $this->sort($components);
	}

	private function sort($components)
	{
		$sorted = new Collection();
		$visited = new Collection();

		foreach ($components as $component)
		{
			$this->visit($component, $visited, $sorted, $components);
		}

		return $sorted;
	}

	private function visit(Component $component, Collection $visited, Collection $sorted, $components)
	{
		if (!$visited->contains($component))
		{
			$visited->push($component);

			foreach ($component->getDependencies() as $componentName)
			{
				$this->visit($components[$componentName], $visited, $sorted, $components);
			}

			$sorted->push($component);
		}
		else
		{
//			throw new CyclicDependencyException();
		}
	}

	/**
	 * @param $path
	 * @return Component
	 */
	private function createComponentFromBowerFile($path)
	{
		$componentName = $this->getComponentName($path);

		$data = json_decode(file_get_contents($path), true);

		$files = Collection::make(isset($data['main']) ? $data['main'] : []);
		$dependencies = Collection::make(isset($data['dependencies']) ? array_keys($data['dependencies']) : []);

		$component = new Component($componentName, $files, $dependencies);

		return $component;
	}

	private function getComponentName($path)
	{
		$temp = substr($path, strlen($this->componentDirectory));

		return substr($temp, 0, strrpos($temp, '/'));
	}

	private function findAllBowerFiles()
	{
		return glob(rtrim($this->componentDirectory, '/\\') . '/*/bower.json');
	}

}