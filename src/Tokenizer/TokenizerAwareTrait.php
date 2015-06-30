<?php
/**
 * TokenizerAwareTrait.php
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
 * Class TokenizerAwareTrait
 * @package SK\Formuls\Tokenizer
 */
trait TokenizerAwareTrait
{
	/**
	 * @var TokenizerInterface
	 */
	private $tokenizer;

	/**
	 * @return TokenizerInterface
	 */
	public function getTokenizer()
	{
		return $this->tokenizer;
	}

	/**
	 * @param TokenizerInterface $tokenizer
	 * @return self
	 */
	public function setTokenizer(TokenizerInterface $tokenizer)
	{
		$this->tokenizer = $tokenizer;
		return $this;
	}
}