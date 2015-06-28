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

use SK\Formuls\Calculator\CalculatorBaseInterface;
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
	const CHAR_PLUS 				= '+';
	const CHAR_MINUS 				= '-';
	const CHAR_MULTIPLY 			= '*';
	const CHAR_DIVIDE 				= '/';
	const CHAR_MODUlE				= '%';
	const CHAR_EXPONENT				= '^';
	const CHAR_SEPARATE				= ',';
	const CHAR_LITERAL				= '[a-zA-Z0-9_]';


	protected $expression;

	/**
	 * @var CalculatorBaseInterface
	 */
	protected $calculator;

	protected $cursor = 0;
	protected $cursorToken = 0;


	protected $length = 0;

	protected $tokenPrev = false;

	/**
	 * @param CalculatorBaseInterface $calculator
	 */
	public function __construct(CalculatorBaseInterface $calculator)
	{
		$this->calculator = $calculator;
	}


	/**
	 * @return array
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
	 * @return bool|LiteralInterface|OperatorInterface
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

			case static::CHAR_PLUS:
				$token = $this->isUnaryOperation($token) ? new Operator\PlusUnary($token) : new Operator\Plus($token);
			break;

			case static::CHAR_MINUS:
				$token = $this->isUnaryOperation($token) ? new Operator\MinusUnary($token) : new Operator\Minus($token);
			break;

			case static::CHAR_MULTIPLY:
				$token = new Operator\Multiply($token);
			break;

			case static::CHAR_DIVIDE:
				$token = new Operator\Divide($token);
			break;

			case static::CHAR_MODUlE:
				$token = new Operator\Mod($token);
			break;

			case static::CHAR_EXPONENT:
				$token = new Operator\Exponent($token);
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

				throw new \LogicException(sprintf('Syntax error: Expression "%s" not supported', $token));
		}


		$this->tokenPrev = $token;
		return $token;
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
