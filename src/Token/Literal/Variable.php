<?php
/**
 * Variable.php
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
namespace SK\Formuls\Token\Literal;


use SK\Formuls\Calculator\CalculatorAwareInterface;
use SK\Formuls\Calculator\CalculatorAwareTrait;
use SK\Formuls\Calculator\VariablesAwareInterface as CalculatorVariablesAwareInterface;
use SK\Formuls\Token;
use SK\Formuls\Token\LiteralInterface;

/**
 * Class Variable
 * @package SK\Formuls\Token\Literal
 */
class Variable extends Token implements LiteralInterface, CalculatorAwareInterface
{
	use CalculatorAwareTrait;

	public function getValue()
	{
		/** @var CalculatorVariablesAwareInterface $calculator */
		$calculator = $this->getCalculator();

		if (!($calculator instanceof CalculatorVariablesAwareInterface)) {
			throw new \RuntimeException('The calculator must implement the interface "Calculator\VariablesAwareInterface"');
		}

		$name = $this->getName();

		if (!$calculator->hasVariable($name)) {
			throw new \LogicException('The variable "' . $name . '" is not defined in the calculator');
		}

		return $calculator->getVariable($name);
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return parent::getValue();
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->getName();
	}
}