<?php
/**
 * Exponentiation.php
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
use SKGroup\MathExpression\Token\Operator;


/**
 * Class Exponentiation
 * @package SKGroup\MathExpression\Token\Operator
 */
class Exponentiation extends Token implements Token\OperatorInterface
{
	/**
	 * @param mixed $a
	 * @param mixed $b
	 * @return mixed
	 */
	public function execute($a, $b = null)
	{
		return version_compare(PHP_VERSION, '5.6.0') >= 0 ? $a ** $b : pow($a, $b);
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
		return 4;
	}

	/**
	 * @return self::ASSOC_LEFT | self::ASSOC_RIGHT
	 */
	public function getAssociation()
	{
		return static::ASSOC_RIGHT;
	}
}