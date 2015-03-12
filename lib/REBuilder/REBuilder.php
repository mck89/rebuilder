<?php
/**
 * This file is part of the REBuilder package
 *
 * (c) Marco Marchiò <marco.mm89@gmail.com>
 *
 * For the full copyright and license information refer to the LICENSE file
 * distributed with this source code
 */

namespace REBuilder;

/**
 * Main class of REBuilder library
 * 
 * @author Marco Marchiò <marco.mm89@gmail.com>
 */
class REBuilder
{
    /**
     * Parses the given regular expression and returns its structure
     * 
     * @param string $regex The regular expression to parse
     * 
     * @return Pattern\Regex
     * 
     * @static
     */
    public static function parse ($regex)
    {
        $parser = new Parser\Parser($regex);
        return $parser->parse();
    }

    /**
     * Returns a new empty regex object
     * 
     * @param string $modifiers Regex modifiers
     * @param string $delimiter Regex delimiter
     * 
     * @return Pattern\Regex
     * 
     * @static
     */
    public static function create ($modifiers = "", $delimiter = "/")
    {
        return new Pattern\Regex($modifiers, $delimiter);
    }
    
    /**
     * Registers the class autoloader
     * 
     * @return void
     * 
     * @static
     * @codeCoverageIgnore
     */
    public static function registerAutoloader ()
    {
        spl_autoload_register(array(__CLASS__, "loadClass"));
    }
    
    /**
     * This method is used by autoloader to include class files according
     * to the given class name
     * 
     * @param string $className Class name to load
     * 
     * @return void
     * 
     * @static
     */
    public static function loadClass ($className)
    {
        $parts = explode("\\", $className);
        $base = array_shift($parts);
        if ($base === "REBuilder") {
            $file = __DIR__ . DIRECTORY_SEPARATOR .
                    implode(DIRECTORY_SEPARATOR, $parts) . ".php";
            if (file_exists($file)) {
                include_once $file;
            }
        }
    }
}