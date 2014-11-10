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

	// sort based on http://stackoverflow.com/questions/4106862/how-to-sort-depended-objects-by-dependency
	/**
	 * Sorts the given components based on any dependencies they have, so that dependencies are loaded before the
	 * component.
	 *
	 * @param $components
	 * @return Collection
	 */
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
	}

	/**
	 * @param $path
	 * @return Component
	 */
	private function createComponentFromBowerFile($path)
	{
		$bower = json_decode(file_get_contents($path), true);

		$componentName = $bower['name'];
		$files = Collection::make(isset($bower['main']) ? $bower['main'] : []);
		$dependencies = Collection::make(isset($bower['dependencies']) ? array_keys($bower['dependencies']) : []);

		$component = new Component($componentName, $files, $dependencies);

		return $component;
	}

	private function findAllBowerFiles()
	{
		return glob(rtrim($this->componentDirectory, '/\\') . '/*/bower.json');
	}

}