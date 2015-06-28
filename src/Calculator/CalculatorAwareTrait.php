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
namespace SK\Formuls\Calculator;

/**
 * Class CalculatorAwareTrait
 * @package SK\Formuls\Calculator
 */
trait CalculatorAwareTrait
{
	/**
	 * @var CalculatorBaseInterface
	 */
	private $calculator;

	/**
	 * @param CalculatorBaseInterface $calculator
	 * @return self
	 */
	public function setCalculator(CalculatorBaseInterface $calculator)
	{
		$this->calculator = $calculator;
		return $this;
	}

	/**
	 * @return CalculatorBaseInterface
	 */
	public function getCalculator()
	{
		return $this->calculator;
	}
}
