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
trait VariablesAwareTrait
{
	private $variables = [];

	/**
	 * @param array $variables
	 * @param bool $merge
	 * @return $this
	 */
	public function setVariables(Array $variables, $merge = true)
	{
		$this->variables = $merge ? array_merge($this->variables, $variables) : $variables;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getVariables()
	{
		return $this->variables;
	}

	/**
	 * @param string $name
	 * @param float|int $value
	 * @return $this
	 */
	public function setVariable($name, $value)
	{
		$this->variables[$name] = $value;
		return $this;
	}

	/**
	 * @param string $name
	 * @return float|int|false
	 */
	public function getVariable($name)
	{
		if (!$this->hasVariable($name)) {
			return false;
		}

		return $this->variables[$name];
	}

	/**
	 * @param $name
	 * @return bool
	 */
	public function hasVariable($name)
	{
		return isset($this->variables[$name]);
	}

	/**
	 * @return $this
	 */
	public function clearVariables()
	{
		$this->variables = [];
	 	return $this;
	}
}