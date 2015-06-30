<?php
/**
 * FunctionCall.php
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
namespace SKGroup\MathExpression\Token\Operator;


use SKGroup\MathExpression\Calculator\CalculatorAwareInterface;
use SKGroup\MathExpression\Calculator\CalculatorAwareTrait;
use SKGroup\MathExpression\Calculator\FunctionsAwareInterface;
use SKGroup\MathExpression\Token;
use SKGroup\MathExpression\Token\OperatorInterface;


/**
 * Class FunctionCall
 * @package SKGroup\MathExpression\Token\Operator
 */
class FunctionCall extends Token implements OperatorInterface, CalculatorAwareInterface
{
	use CalculatorAwareTrait;

	/**
	 * @var int
	 */
	protected $argumentsNum = 1;

	/**
	 * @param mixed $a
	 * @param mixed $b
	 * @return mixed
	 */
	public function execute($a, $b = null)
	{
		/** @var FunctionsAwareInterface $calc */
		$calc = $this->getCalculator();

		if ($func = $calc->getFunction($this->getValue())) {
			return call_user_func_array($func, func_get_args());
		} else {
			return NAN;
		}
	}

	/**
	 * @return self::TYPE_BINARY | self::TYPE_UNARY
	 */
	public function getType()
	{
		return static::TYPE_POLYADIC;
	}

	/**
	 * @return int
	 */
	public function getPriority()
	{
		return 6;
	}

	/**
	 * @return self::ASSOC_LEFT | self::ASSOC_RIGHT
	 */
	public function getAssociation()
	{
		return static::ASSOC_LEFT;
	}

	/**
	 * @param $num
	 * @return $this
	 */
	public function setArgumentsNum($num)
	{
		$this->argumentsNum = $num;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getArgumentsNum()
	{
		return $this->argumentsNum;
	}
}