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
namespace SKGroup\MathExpression\Tokenizer;


/**
 * Class TokenizerAwareTrait
 * @package SKGroup\MathExpression\Tokenizer
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