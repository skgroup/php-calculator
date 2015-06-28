<?php
/**
 * TokenizerInterface.php
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
namespace SK\Formuls;


use SK\Formuls\Calculator\CalculatorBaseInterface;

/**
 * Interface TokenizerInterface
 * @package SK\Formuls
 */
interface TokenizerInterface
{
	/**
	 * @param CalculatorBaseInterface $calculator
	 */
	public function __construct(CalculatorBaseInterface $calculator);

	/**
	 * @return string|false
	 */
	public function getToken();

	/**
	 * @param string $expression
	 * @return self
	 */
	public function setExpression($expression);

	/**
	 * @return self
	 */
	public function rewind();
}