<?php
/**
 * TokenInterface.php
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
namespace SKGroup\MathExpression\Token;


/**
 * Interface TokenInterface
 * @package SKGroup\MathExpression
 */
interface TokenInterface
{
	/**
	 * @return string
	 */
	public function getValue();

	/**
	 * @return string
	 */
	public function __toString();
}