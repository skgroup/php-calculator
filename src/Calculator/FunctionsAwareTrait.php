<?php
/**
 * VariablesAwareTrait.php
 * ----------------------------------------------
 *
 *
 * @author      Stanislav Kiryukhin <korsar.zn@gmail.com>
 * @copyright   Copyright (c) 2015, CKGroup.ru
 *
 * ----------------------------------------------
 * All Rights Reserved.
 * ----------------------------------------------
 */
namespace SK\Formuls\Calculator;

/**
 * Class VariablesAwareTrait
 * @package SK\Formuls\Calculator
 */
trait FunctionsAwareTrait
{
	private $functions = [];

	/**
	 * @param $name
	 * @param callable $callback
	 * @return self
	 */
	public function registerFunction($name, callable $callback)
	{
		$this->functions[$name] = $callback;
		return $this;
	}

	/**
	 * @param $name
	 * @return callable
	 */
	public function getFunction($name)
	{
		return $this->functions[$name];
	}

	/**
	 * @return callable[]
	 */
	public function getFunctions()
	{
		return $this->functions;
	}

	/**
	 * @param $name
	 * @return bool
	 */
	public function hasFunction($name)
	{
		return isset($this->functions[$name]) && is_callable($this->functions[$name]);
	}

	/**
	 * @return self
	 */
	public function clearFunctions()
	{
		$this->functions = [];
		return $this;
	}
}