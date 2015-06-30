<?php
/**
 * Modulus.php
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


use SKGroup\MathExpression\Token;
use SKGroup\MathExpression\Token\OperatorInterface;


/**
 * Class Modulus
 * @package SKGroup\MathExpression\Token\Operator
 */
class Modulus extends Token implements OperatorInterface
{
	/**
	 * @param mixed $a
	 * @param mixed $b
	 * @return mixed|void
	 */
	public function execute($a, $b = null)
	{
		return $a % $b;
	}

	/**
	 * @return self::TYPE_BINARY | self::TYPE_UNARY
	 */
	public function getType()
	{
		return static::TYPE_BINARY;
	}

	/**
	 * @return int
	 */
	public function getPriority()
	{
		return 3;
	}

	/**
	 * @return self::ASSOC_LEFT | self::ASSOC_RIGHT
	 */
	public function getAssociation()
	{
		return static::ASSOC_LEFT;
	}
}