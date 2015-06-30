<?php
/**
 * FunctionsAwareInterface.php
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
namespace SKGroup\MathExpression\Calculator;

/**
 * Interface FunctionsAwareInterface
 * @package SKGroup\MathExpression\Calculator
 */
interface FunctionsAwareInterface
{
	/**
	 * @param $name
	 * @param callable $callback
	 * @return self
	 */
	public function registerFunction($name, callable $callback);

	/**
	 * @return callable[]
	 */
	public function getFunctions();

	/**
	 * @param $name
	 * @return callable
	 */
	public function getFunction($name);

	/**
	 * @param $name
	 * @return bool
	 */
	public function hasFunction($name);

	/**
	 * @return self
	 */
	public function clearFunctions();
}