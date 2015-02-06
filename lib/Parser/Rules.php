<?php
/**
 * Utility class for regex rules
 * 
 * @author Marco Marchiò
 */
class REBuilder_Parser_Rules
{
	/**
	 * Key/value pairs of bracket style delimiters, the key represents the start
	 * delimiter, the value represents the end delimiter
	 * 
	 * @var array
	 * @static
	 */
	protected static $_bracketStyleDelimiters = array("(" => ")", "[" => "]", "{" => "}", "<" => ">");
	
	/**
	 * Array of valid modifiers
	 * 
	 * @var array
	 * @static
	 */
	protected static $_modifiers = array("i", "m", "s", "x", "e", "A", "D", "S", "U", "X", "J", "u");
	
	/**
	 * Array of representable non-printing characters identifiers
	 * 
	 * @var array
	 * @static
	 */
	protected static $_nonPrintingChars = array("a", "e", "f", "n", "r", "t");
	
	/**
	 * Array of generic character type identifiers
	 * 
	 * @var array
	 * @static
	 */
	protected static $_genericCharTypes = array(
		"d", "D", "h", "H", "s", "S", "v", "V", "w", "W"
	);
	
	/**
	 * Array of simple assertion identifiers
	 * 
	 * @var array
	 * @static
	 */
	protected static $_simpleAssertions = array("b", "B", "A", "Z", "z", "g");
	
	/**
	 * Return true if the given string is a valid delimiter, otherwise false
	 * 
	 * @param string $delimiter Delimiter to check
	 * @return bool
	 * @static
	 */
	public static function validateDelimiter ($delimiter)
	{
		//A delimiter can be any non-alphanumeric, non-backslash, non-whitespace character. 
		return preg_match("#^[^\\a-z\s]$#i", $delimiter) === 1;
	}
	
	/**
	 * Given a start delimiter it returns the corresponding end delimiter. This
	 * function returns a delimiter different from the given one only if it's a
	 * bracket style delimiter
	 * 
	 * @param string $delimiter Start delimiter
	 * @return string
	 * @static
	 */
	public static function getEndDelimiter ($delimiter)
	{
		if (isset(self::$_bracketStyleDelimiters[$delimiter])) {
			return self::$_bracketStyleDelimiters[$delimiter];
		}
		return $delimiter;
	}
	
	/**
	 * Return true if the given string contains only valid modifiers, otherwise
	 * false
	 * 
	 * @param string $modifiers     Modifiers to check
	 * @param string $wrongModifier If the check fails this variable will
	 *                              contain the invalid modifier
	 * @return boolean
	 * @static
	 */
	public static function validateModifiers ($modifiers, &$wrongModifier = null)
	{
		if ($modifiers !== "") {
			for ($i = 0; $i < strlen($modifiers); $i++) {
				if (!in_array($modifiers[$i], self::$_modifiers)) {
					$wrongModifier = $modifiers[$i];
					return false;
				}
			}
		}
		return true;
	}
	
	/**
	 * Return true if the given string is a valid representable non printing
	 * character indentifier, otherwise false
	 * 
	 * @param string $str String to check
	 * @return string
	 * @static
	 */
	public static function validateNonPrintingChar ($str)
	{
		return in_array($str, self::$_nonPrintingChars);
	}
	
	/**
	 * Return true if the given string is a valid generic character type
	 * identifier, otherwise false
	 * 
	 * @param string $str String to check
	 * @return string
	 * @static
	 */
	public static function validateGenericCharType ($str)
	{
		return in_array($str, self::$_genericCharTypes);
	}
	
	/**
	 * Return true if the given string is a valid simple assertion identifier,
	 * otherwise false
	 * 
	 * @param string $str String to check
	 * @return string
	 * @static
	 */
	public static function validateSimpleAssertion ($str)
	{
		return in_array($str, self::$_simpleAssertions);
	}
}
