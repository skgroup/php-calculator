<?php
/**
 * CalculatorBaseInterface.php
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

use SK\Formuls\TokenizerAwareInterface;

/**
 * Interface CalculatorBaseInterface
 * @package SK\Formuls\Calculator
 */
interface CalculatorBaseInterface extends TokenizerAwareInterface
{
	/**
	 * @return int|float
	 */
	public function calculate();

	/**
	 * @param string $expression
	 * @return self
	 */
	public function setExpression($expression);

	/**
	 * @return string
	 */
	public function getExpression();

	/**
	 * @return void
	 */
	public function clear();
}