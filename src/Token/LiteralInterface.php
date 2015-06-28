<?php
/**
 * LiteralInterface.php
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
namespace SK\Formuls\Token;

/**
 * Interface LiteralInterface
 * @package SK\Formuls\Token
 */
interface LiteralInterface
{
	/**
	 * @return string
	 */
	public function getValue();
}