<?php
/**
 * CalculatorInterface.php
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

use SKGroup\MathExpression\Tokenizer\TokenizerAwareInterface;

/**
 * Interface CalculatorInterface
 * @package SKGroup\MathExpression\Calculator
 */
interface CalculatorInterface extends TokenizerAwareInterface
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