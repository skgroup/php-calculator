SKGroup\MathExpression
==============

## Installing ##

Install composer in a common location or in your project:

    curl -s http://getcomposer.org/installer | php

Create the composer.json file as follows:

```
{
	"require": {
		"skgroup/php-calculator": "dev-master"
	}
}
```

Run the composer installer:

```bash
php composer.phar install
```

Add in your the code

    require_once('vendor/autoload.php');