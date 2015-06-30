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
namespace SKGroup\MathExpression\Tokenizer;


use SKGroup\MathExpression\Token\TokenInterface;

/**
 * Interface TokenizerInterface
 * @package SKGroup\MathExpression\Tokenizer
 */
interface TokenizerInterface
{
	/**
	 * @return TokenInterface|false
	 */
	public function getToken();

	/**
	 * @return TokenInterface[]
	 */
	public function getTokens();

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