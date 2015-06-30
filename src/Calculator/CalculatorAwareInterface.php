<?php
/**
 * CalculatorAwareInterface.php
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
 * Interface CalculatorAwareInterface
 * @package SKGroup\MathExpression\Calculator
 */
interface CalculatorAwareInterface
{
	/**
	 * @param CalculatorInterface $calculator
	 * @return self
	 */
	public function setCalculator(CalculatorInterface $calculator);

	/**
	 * @return CalculatorInterface
	 */
	public function getCalculator();
}