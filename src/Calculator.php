<?php
/**
 * Calculator.php
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


use SK\Formuls\Calculator\CalculatorAwareInterface;
use SK\Formuls\Calculator\CalculatorInterface;
use SK\Formuls\Calculator\FunctionsAwareInterface;
use SK\Formuls\Calculator\FunctionsAwareTrait;
use SK\Formuls\Calculator\VariablesAwareInterface;
use SK\Formuls\Calculator\VariablesAwareTrait;
use SK\Formuls\Token\LiteralInterface;
use SK\Formuls\Tokenizer\TokenizerAwareTrait;
use SK\Formuls\Token\Literal;
use SK\Formuls\Token\Operator;
use SK\Formuls\Token\OperatorInterface;
use SK\Formuls\Token\TokenInterface;


/**
 * Class Calculator
 * @package SK\Formuls
 */
class Calculator implements CalculatorInterface, FunctionsAwareInterface, VariablesAwareInterface
{
	use VariablesAwareTrait;
	use FunctionsAwareTrait;
	use TokenizerAwareTrait;

	/**
	 * @var string
	 */
	protected $expression;

	/**
	 * @var array
	 */
	protected static $_cacheRPN = [];


	public function __construct()
	{
		$this->registerBasicFunctions();
		$this->registerBasicConstants();
	}

	/**
	 * @return float
	 */
	public function calculate()
	{
		if (!$this->getTokenizer()) {
			$this->setTokenizer($this->createTokenizer());
		}

		return $this->calculateReversePolishNotation();
	}

	/**
	 * @return string
	 */
	public function getExpression()
	{
		return $this->expression;
	}

	/**
	 * @param string $expression
	 * @return self
	 */
	public function setExpression($expression)
	{
		$this->expression = $expression;
		return $this;
	}

	/**
	 * @return void
	 */
	public function clear()
	{
		$this->expression = null;
		$this->clearVariables();
	}

	/**
	 * @return mixed
	 */
	protected function calculateReversePolishNotation()
	{
		$rpn = $this->getReversePolishNotation();
		$stack = [];

		foreach ($rpn as $token) {

			if ($token instanceof CalculatorAwareInterface) {
				$token->setCalculator($this);
			}

			if ($token instanceof LiteralInterface) {
				$stack[] = $token->getValue();
				continue;
			}

			if ($token instanceof OperatorInterface) {

				if ($token instanceof Operator\FunctionCall) {
					$operandsNum = $token->getArgumentsNum();
				} else {
					$operandsNum = $token->getType() === OperatorInterface::TYPE_BINARY ? 2 : 1;
				}

				$stack[] = call_user_func_array([$token, 'execute'], array_splice($stack, -$operandsNum));
			}
		}

		if (count($stack) > 1) {
			throw new \LogicException(sprintf('Unknown Error: Unable to evaluate the expression'));
		}

		return $stack[0];
	}

	/**
	 * @return TokenInterface[] | OperatorInterface[]
	 */
	protected function getReversePolishNotation()
	{
		$expression = $this->getExpression();
		$cacheKey 	= md5(preg_replace('/\s+/', '', $expression));

		if (!empty(static::$_cacheRPN[$cacheKey])) {
			return static::$_cacheRPN[$cacheKey];
		}

		$tokenizer = $this->getTokenizer();
		$tokenizer->setExpression($expression);

		$results = [];
		$operators = [];

		$argumentsNum = 1;

		while (($token = $tokenizer->getToken()) !== false) {


			// Если число или переменная
			if ($this->isNumber($token)) {
				$results[] = $token;
				continue;
			}

			// Если оператор
			if ($token instanceof OperatorInterface) {

				$o1 = $token;

				do {
					/** @var OperatorInterface $o2 */
					if (!(($o2 = end($operators)) instanceof OperatorInterface)) {
						break;
					}

					if ($o1->getAssociation() === OperatorInterface::ASSOC_LEFT && $o1->getPriority() <= $o2->getPriority()) {
						$results[] = array_pop($operators);
					} else if ($o1->getPriority() < $o2->getPriority()) {
						$results[] = array_pop($operators);
					} else {
						break;
					}

				} while(true);

				$operators[] = $o1;
				continue;
			}

			if ($token instanceof Literal\ParenthesisOpen) {
				$operators[] = $token;
				continue;
			}

			if ($token instanceof Literal\ParenthesisClose) {

				$hasParenthesisOpen = false;

				while ($token = array_pop($operators)) {
					if (!($token instanceof Literal\ParenthesisOpen)) {
						$results[] = $token;
					} else {
						$hasParenthesisOpen = true;
						break;
					}
				}

				if (!$hasParenthesisOpen) {
					throw new \RuntimeException('Error: Mismatched parentheses');
				}


				if (end($operators) instanceof Operator\FunctionCall) {
					/** @var Operator\FunctionCall $func */
					$results[] = $func = array_pop($operators);

					if ($argumentsNum > 0) {
						$func->setArgumentsNum($argumentsNum);
						$argumentsNum = 1;
					}
				}

				continue;
			}

			if ($token instanceof Literal\Separate) {
				$argumentsNum++;
			}
		}

		// Выталкиваем все остальные элементы
		while ($token = array_pop($operators)) {

			if ($token instanceof Literal\ParenthesisOpen) {
				throw new \RuntimeException('Error: Mismatched parentheses');
			}

			$results[] = $token;
		}

		return static::$_cacheRPN[$cacheKey] = $results;
	}


	/**
	 * Register Basic math constants
	 * @return void
	 */
	protected function registerBasicConstants()
	{
		$this->setVariables([
			'PI' 	=> M_PI,
			'LN_PI' => M_LNPI,
			'E' 	=> M_E,
			'EULER' => M_EULER
		]);
	}

	/**
	 * Register Basic math functions
	 * @return void
	 */
	protected function registerBasicFunctions()
	{
		$functions = (array)include __DIR__ . '/.functions.php';

		foreach ($functions as $name => $function) {
			$this->registerFunction($name, $function);
		}
	}

	/**
	 * @param $token
	 * @return bool
	 */
	protected function isNumber($token)
	{
		return $token instanceof Literal\Number || $token instanceof Literal\Variable;
	}

	/**
	 * @return Tokenizer
	 */
	private function createTokenizer()
	{
		return new Tokenizer($this);
	}
}