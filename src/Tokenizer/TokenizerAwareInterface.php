<?php
/**
 * TokenizerAwareInterface.php
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

/**
 * Interface TokenizerAwareInterface
 * @package SKGroup\MathExpression\Tokenizer
 */
interface TokenizerAwareInterface
{
	/**
	 * @return TokenizerInterface
	 */
	public function getTokenizer();

	/**
	 * @param TokenizerInterface $tokenizer
	 * @return self
	 */
	public function setTokenizer(TokenizerInterface $tokenizer);
}