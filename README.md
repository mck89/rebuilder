REBuilder
==========

REBuilder is a PHP library to build and parse regular expressions

Parse
-------------

REBuilder's parser builds a tree structure starting from a regular expression.
For example this code:

```php
<?php
require_once "lib\REBuilder\REBuilder.php";

//Register the autoloader
REBuilder\Rebuilder::registerAutoloader();

//Parse the regular expression
$regex = REBuilder\Rebuilder::parse("/parse\s+me/");
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
<?php
require_once "lib\REBuilder\REBuilder.php";

//Register the autoloader
REBuilder\Rebuilder::registerAutoloader();

//Create an empty regular expression object
$regex = REBuilder\Rebuilder::create();

$regex->addCharAndContinue("parse")
      ->addGenericCharType("s")
          ->setRepetition("+")
          ->getParent()
      ->addCharAndContinue("me");

echo $regex->render(); //"/parse\s+me/"
```