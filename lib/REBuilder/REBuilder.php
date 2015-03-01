<?php
/**
 * Main class of REBuilder library
 * 
 * @author Marco Marchiò
 */
class REBuilder
{
    /**
     * Parses the given regular expression and returns its structure
     * 
     * @param string $regex The regular expression to parse
     * @return REBuilder_Pattern_Regex
     * @static
     */
    public static function parse ($regex)
    {
        $parser = new REBuilder_Parser_Parser($regex);
        return $parser->parse();
    }

    /**
     * Returns a new regex object
     * 
     * @param string $modifiers Regex modifiers
     * @param string $delimiter Regex delimiter
     * @return REBuilder_Pattern_Regex
     * @static
     */
    public static function create ($modifiers = "", $delimiter = "/")
    {
        return new REBuilder_Pattern_Regex($modifiers, $delimiter);
    }
    
    /**
     * Registers the class autoloader
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
     * @static
     */
    public static function loadClass ($className)
    {
        $parts = explode("_", $className);
        $base = array_shift($parts);
        if ($base === __CLASS__) {
            $file = __DIR__ . DIRECTORY_SEPARATOR .
                    implode(DIRECTORY_SEPARATOR, $parts) . ".php";
            if (file_exists($file)) {
                require_once $file;
            }
        }
    }
}