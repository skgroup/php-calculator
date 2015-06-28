<?php
/**
 * VariablesAwareInterface.php
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
 * Interface VariablesAwareInterface
 * @package SK\Formuls\Calculator
 */
interface VariablesAwareInterface
{
	/**
	 * @param array $variables
	 * @param bool $merge
	 * @return self
	 */
	public function setVariables(Array $variables, $merge = true);

	/**
	 * @return array
	 */
	public function getVariables();

	/**
	 * @param string $name
	 * @param float|int $value
	 * @return self
	 */
	public function setVariable($name, $value);

	/**
	 * @param string $name
	 * @return float|int|false
	 */
	public function getVariable($name);

	/**
	 * @param $name
	 * @return bool
	 */
	public function hasVariable($name);
}