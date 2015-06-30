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
namespace SK\Formuls\Tokenizer;

/**
 * Interface TokenizerAwareInterface
 * @package SK\Formuls\Tokenizer
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