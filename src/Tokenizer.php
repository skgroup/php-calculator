<?php
/**
 * Tokenizer.php
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
namespace SK\Formuls;


use SK\Formuls\Tokenizer\TokenizerInterface;

use SK\Formuls\Token\TokenInterface;
use SK\Formuls\Token\LiteralInterface;
use SK\Formuls\Token\OperatorInterface;

use SK\Formuls\Token\Literal;
use SK\Formuls\Token\Operator;

/**
 * Class Tokenizer
 * @package SK\Formuls
 */
class Tokenizer implements TokenizerInterface
{
	// Operator characters
	const CHAR_SPACE 				= ' ';
	const CHAR_PARENTHESIS_OPEN 	= '(';
	const CHAR_PARENTHESIS_CLOSE 	= ')';
	const CHAR_ADDITION 			= '+';
	const CHAR_SUBTRACTION 			= '-';
	const CHAR_MULTIPLICATION 		= '*';
	const CHAR_DIVISION 			= '/';
	const CHAR_MODULUS				= '%';
	const CHAR_EXPONENTIATION		= '^';
	const CHAR_SEPARATE				= ',';
	const CHAR_LITERAL				= '[a-zA-Z0-9_]';

	protected $expression;

	protected $cursor = 0;
	protected $length = 0;

	protected $tokenPrev = false;


	/**
	 * @return TokenInterface[]|LiteralInterface[]|OperatorInterface[]
	 */
	public function getTokens()
	{
		$tokens = [];

		while (($token = $this->getToken()) !== false) {
			$tokens[] = $token;
		}

		return $tokens;
	}

	/**
	 * @return bool|LiteralInterface|OperatorInterface|TokenInterface
	 */
	public function getToken()
	{
		if ($this->cursor + 1 > $this->length) {
			return false;
		}

		$expression = $this->expression;
		$token = $expression{$this->cursor};

		// Read literal
		if ($this->isLiteral($token)) {
			for ($i = $this->cursor + 1, $n = $this->length; $i < $n; $i++) {
				if ($this->isLiteral($nextToken = $expression{$i})) {
					$token .= $nextToken;
				} else {
					break;
				}
			}

			$this->cursor = $i - 1;
		}

		$this->cursor++;
		return $token === static::CHAR_SPACE ? $this->getToken() : $this->prepareToken($token);
	}

	/**
	 * @param string $expression
	 * @return self
	 */
	public function setExpression($expression)
	{
		$this->expression = $this->normalizeExpression($expression);

		$this->cursor = 0;
		$this->length = strlen($this->expression);

		return $this;
	}

	/**
	 * @return $this
	 */
	public function rewind()
	{
		$this->cursor = 0;
		return $this;
	}

	/**
	 * @param $char
	 * @return bool
	 */
	protected function isLiteral($char)
	{
		return preg_match('/' . static::CHAR_LITERAL . '/i', $char);
	}

	/**
	 * @param $token
	 * @return LiteralInterface|OperatorInterface|null
	 */
	protected function prepareToken($token)
	{
		switch ($token) {

			case static::CHAR_ADDITION:
				$token = new Operator\Addition($token);
			break;

			case static::CHAR_SUBTRACTION:
				$token = $this->isUnaryOperation($token) ? new Operator\Negation($token) : new Operator\Subtraction($token);
			break;

			case static::CHAR_MULTIPLICATION:
				$token = new Operator\Multiplication($token);
			break;

			case static::CHAR_DIVISION:
				$token = new Operator\Division($token);
			break;

			case static::CHAR_MODULUS:
				$token = new Operator\Modulus($token);
			break;

			case static::CHAR_EXPONENTIATION:
				$token = new Operator\Exponentiation($token);
			break;

			case static::CHAR_PARENTHESIS_OPEN:
				$token = new Literal\ParenthesisOpen($token);
			break;

			case static::CHAR_PARENTHESIS_CLOSE:
				$token = new Literal\ParenthesisClose($token);
			break;

			case static::CHAR_SEPARATE:
				$token = new Literal\Separate($token);
			break;

			default:

				if (is_numeric($token)) {
					$token = new Literal\Number($token);
					break;
				}

				if ($this->isFunction($token)) {
					$token = new Operator\FunctionCall(strtolower($token));
					break;
				}

				if ($this->isVariable($token)) {
					$token = new Literal\Variable($token);
					break;
				}

				throw new \LogicException('Syntax error: Operator "' . $token . '" not supported');
		}


		if (!$this->checkSyntaxTokens($token)) {
			throw new \LogicException('Syntax error: expression incorrect (offset "' . $this->cursor . '" characters)');
		}

		$this->tokenPrev = $token;
		return $token;
	}

	/**
	 * @param TokenInterface $token
	 * @return bool
	 */
	protected function checkSyntaxTokens(TokenInterface $token)
	{
		// Current token
		$isTokenLiteral 	= $token instanceof LiteralInterface;
		$isTokenNumber	 	= $token instanceof Literal\Number || $token instanceof Literal\Variable;
		$isTokenOperator 	= $token instanceof OperatorInterface;
		$isTokenNegation 	= $token instanceof Operator\Negation;
		$isTokenFunction 	= $token instanceof Operator\FunctionCall;

		// Prev token
		$isTokenPrevNull	 = !$this->tokenPrev;
		$isTokenPrevOperator = $this->tokenPrev instanceof OperatorInterface;
		$isTokenPrevLiteral	 = $this->tokenPrev instanceof LiteralInterface;
		$isTokenPrevNumber	 		= $this->tokenPrev instanceof Literal\Number || $this->tokenPrev instanceof Literal\Variable;
		$isTokenPrevParenthesisOpen = $this->tokenPrev instanceof Literal\ParenthesisOpen;
		$isTokenPrevSeparate 		= $this->tokenPrev instanceof Literal\Separate;


		if ($isTokenNumber) {
			return $isTokenPrevNull || $isTokenPrevOperator || $isTokenPrevParenthesisOpen || $isTokenPrevSeparate;
		}

		if ($isTokenNegation) {
			return ($isTokenPrevNull || $isTokenPrevOperator || $isTokenPrevLiteral) && !$isTokenPrevNumber;
		}

		if ($isTokenFunction) {
			return $isTokenPrevNull || $isTokenPrevOperator;
		}

		if ($isTokenOperator) {
			return $isTokenPrevLiteral;
		}

		return true;
	}

	/**
	 * @return bool
	 * @internal param $token
	 */
	protected function isUnaryOperation()
	{
		$tokenPrev = $this->tokenPrev;

		return !$tokenPrev || !($tokenPrev instanceof Literal\Number || $tokenPrev instanceof Literal\Variable ||
			$tokenPrev instanceof Literal\ParenthesisClose);
	}

	/**
	 * @param $token
	 * @return bool
	 */
	protected function isVariable($token)
	{
		return preg_match('/' . static::CHAR_LITERAL . '+/i', $token);
	}

	/**
	 * @param $token
	 * @return bool
	 */
	protected function isFunction($token)
	{
		$char = $this->expression[$this->cursor];
		return preg_match('/' . static::CHAR_LITERAL . '+/i', $token) && $char === static::CHAR_PARENTHESIS_OPEN;
	}

	/**
	 * @param $expr
	 * @return string
	 */
	protected function normalizeExpression($expr)
	{
		return trim($expr);
	}
}
