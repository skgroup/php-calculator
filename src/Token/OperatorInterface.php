<?php
/**
 * OperatorInterface.php
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
 * Interface OperatorInterface
 * @package SK\Formuls\Token
 */
interface OperatorInterface extends TokenInterface
{
	/**
	 * Polyadic operation
	 */
	const TYPE_POLYADIC = 1;

	/**
	 * Binary operation
	 */
	const TYPE_BINARY = 2;

	/**
	 * Unary operation
	 */
	const TYPE_UNARY = 3;

	/**
	 * Left association
	 */
	const ASSOC_LEFT = 4;

	/**
	 * Right association
	 */
	const ASSOC_RIGHT = 5;

	/**
	 * @return self::TYPE_BINARY | self::TYPE_UNARY
	 */
	public function getType();

	/**
	 * @return int
	 */
	public function getPriority();

	/**
	 * @return self::ASSOC_LEFT | self::ASSOC_RIGHT
	 */
	public function getAssociation();

	/**
	 * @param mixed $a
	 * @param mixed $b
	 * @return mixed
	 */
	public function execute($a, $b = null);

	/**
	 * @return string
	 */
	public function __toString();
}