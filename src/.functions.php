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
	'abs' 	=> function($a) { return abs($a); },
	'acos' 	=> function($a) { return acos($a); },
	'acosh' => function($a) { return acosh($a); },
	'asin'  => function($a) { return asin($a); },

	'sqrt'  => function($a) { return sqrt($a); },

	'min'  => function($a) { return min(func_get_args()); },
	'max'  => function($a) { return max(func_get_args()); }
];