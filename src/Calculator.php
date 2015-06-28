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


use SK\Formuls\Calculator\CalculatorInterface;
use SK\Formuls\Token\Literal;
use SK\Formuls\Token\Operator;
use SK\Formuls\Token\OperatorInterface;
use SK\Formuls\Token\TokenInterface;


/**
 * Class Calculator
 * @package SK\Formuls
 */
class Calculator implements CalculatorInterface
{
	/**
	 * @var TokenizerInterface
	 */
	protected $tokenizer;

	/**
	 * @var array
	 */
	protected $variables = [];

	/**
	 * @var array
	 */
	protected $functions = [];

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
	 * @param array $variables
	 * @param bool $merge
	 * @return $this
	 */
	public function setVariables(Array $variables, $merge = true)
	{
		$this->variables = $merge ? array_merge($this->variables, $variables) : $variables;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getVariables()
	{
		return $this->variables;
	}

	/**
	 * @param string $name
	 * @param float|int $value
	 * @return $this
	 */
	public function setVariable($name, $value)
	{
		$this->variables[$name] = $value;
		return $this;
	}

	/**
	 * @param string $name
	 * @return float|int|false
	 */
	public function getVariable($name)
	{
		if ($this->hasVariable($name)) {
			return $this->variables[$name];
		} else {
			return $this->getConstant($name);
		}
	}

	/**
	 * @param $name
	 * @return bool
	 */
	public function hasVariable($name)
	{
		return isset($this->variables[$name]) || $this->hasConstant($name);
	}



	/**
	 * @return void
	 */
	public function clear()
	{
		$this->expression = null;
		$this->variables  = [];
	}

	/**
	 * @return TokenizerInterface
	 */
	public function getTokenizer()
	{
		if (!$this->tokenizer) {
			$this->tokenizer = $this->createTokenizer();
		}

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

	/**
	 * @param $name
	 * @param callable $callback
	 * @return self
	 */
	public function registerFunction($name, callable $callback)
	{
		$this->functions[$name] = $callback;
		return $this;
	}

	/**
	 * @param $name
	 * @return callable
	 */
	public function getFunction($name)
	{
		return $this->functions[$name];
	}

	/**
	 * @return callable[]
	 */
	public function getFunctions()
	{
		return $this->functions;
	}

	/**
	 * @param $name
	 * @return bool
	 */
	public function hasFunction($name)
	{
		return isset($this->functions[$name]) && is_callable($this->functions[$name]);
	}

	/**
	 * @return mixed
	 */
	protected function calculateReversePolishNotation()
	{
		$rpn = $this->getReversePolishNotation();
		$stack = [];

		foreach ($rpn as $token) {
			if ($this->isNumber($token)) {
				$stack[] = $token->getValue();
			} else {

				if ($token->getType() === OperatorInterface::TYPE_BINARY) {
					$b = array_pop($stack);
					$a = array_pop($stack);
					$stack[] = $token->execute($a, $b);
				} else if ($token->getType() === OperatorInterface::TYPE_UNARY) {
					$stack[] = $token->execute(array_pop($stack));
					continue;
				} else if ($token instanceof Operator\FunctionCall) {
					$token->setCalculator($this);
					$stack[] = call_user_func_array([$token, 'execute'], array_splice($stack, -$token->getArgumentsNum()));
				}
			}
		}


		if (count($stack) > 10) {
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
					//TODO: Бросить исключение..
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
			$results[] = $token;
		}

		return static::$_cacheRPN[$cacheKey] = $results;
	}

	/**
	 * @return Tokenizer
	 */
	protected function createTokenizer()
	{
		return new Tokenizer($this);

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
		$functions = (array) include __DIR__ . '/mathFunction.php';

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
}