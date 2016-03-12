REBuilder
==========

REBuilder is a PHP 5.3+ library to build and parse regular expressions

Installation
-------------

Include the following requirement to your composer.json:
```
{
	"require": {
		"mck89/rebuilder": "dev-master"
	}
}
```

Run `composer install` and include the autoloader:

```php
require_once "vendor/autoload.php";
```

Parse
-------------

REBuilder's parser builds a tree structure starting from a regular expression.
For example this code:

```php
//Parse the regular expression
$regex = REBuilder\REBuilder::parse("/parse\s+me/");
```

Generates this structure:

```
REBuilder\Pattern\Regex
    getStartDelimiter() => "/"
    getEndDelimiter() => "/"
    getChildren() => array(
        REBuilder\Pattern\Char
            getChar() => "parse"
            
        REBuilder\Pattern\GenericCharType
            getIdentifier() => "s"
            getRepetition() => REBuilder\Pattern\Repetition\OneOrMore
            
        REBuilder\Pattern\Char
            getChar() => "me"
    )
```
    
Build
-------------

REBuilder allows you to build regular expressions with object oriented PHP:

```php
//Create an empty regular expression object
$regex = REBuilder\REBuilder::create();

$regex->addCharAndContinue("parse")
      ->addGenericCharType("s")
          ->setRepetition("+")
          ->getParent()
      ->addCharAndContinue("me");

echo $regex->render(); //"/parse\s+me/"
```