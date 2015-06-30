<?php
/**
 * CalculatorAwareTrait.php
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
 * Class CalculatorAwareTrait
 * @package SKGroup\MathExpression\Calculator
 */
trait CalculatorAwareTrait
{
	/**
	 * @var CalculatorInterface
	 */
	private $calculator;

	/**
	 * @param CalculatorInterface $calculator
	 * @return self
	 */
	public function setCalculator(CalculatorInterface $calculator)
	{
		$this->calculator = $calculator;
		return $this;
	}

	/**
	 * @return CalculatorInterface
	 */
	public function getCalculator()
	{
		return $this->calculator;
	}
}
