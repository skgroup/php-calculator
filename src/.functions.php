<?php
/**
 * .functions.php
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
return [

	// Operations
	'abs' 	=> function($a) { return abs($a); },
	'sqrt'  => function($a, $exp = 2) { return $exp > 2 ? pow($a, 1 / $exp) : sqrt($a); },
	'ln' 	=> function($a) { return log($a); },
	'lg' 	=> function($a) { return log10($a); },
	'lb' 	=> function($a) { return log($a, 2); },
	'log' 	=> function($a, $base = M_E) { return log($a, $base); },

	// Functions
	'min'  => function($a) { return min(func_get_args()); },
	'max'  => function($a) { return max(func_get_args()); },
	'ceil' => function($a) { return ceil($a); },
	'floor' => function($a) { return floor($a); },
	'round' => function($a, $precision = 0) { return round($a, $precision); },

	// Trigonometric functions
	'sin' 	=> function($a) { return sin($a); },
	'cos' => function($a) { return cos($a); }
];