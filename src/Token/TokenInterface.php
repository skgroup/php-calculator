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
namespace SK\Formuls\Token;


/**
 * Interface TokenInterface
 * @package SK\Formuls
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