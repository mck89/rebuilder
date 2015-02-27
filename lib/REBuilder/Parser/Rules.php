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
	protected static $_bracketStyleDelimiters = array(
		"(" => ")", "[" => "]", "{" => "}", "<" => ">"
	);
	
	/**
	 * Array of valid modifiers
	 * 
	 * @var array
	 * @static
	 */
	protected static $_modifiers = array(
		"i", "m", "s", "x", "e", "A", "D", "S", "U", "X", "J", "u"
	);
	
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
	protected static $_simpleAssertions = array(
		"b", "B", "A", "Z", "z", "G", "Q", "E", "K"
	);
	
	/**
	 * Array of supported unicode property codes
	 * 
	 * @var array
	 * @static
	 */
	protected static $_unicodePropertyCodes = array(
		"C", "Cc", "Cf", "Cn", "Co", "Cs", "L", "Ll", "Lm", "Lo", "Lt", "Lu",
		"M", "Mc", "Me", "Mn", "N", "Nd", "Nl", "No", "P", "Pc", "Pd", "Pe",
		"Pf", "Pi", "Po", "Ps", "S", "Sc", "Sk", "Sm", "So", "Z", "Zl", "Zp",
		"Zs"
	);
	
	/**
	 * Array of supported unicode scripts
	 * 
	 * @var array
	 * @static
	 */
	protected static $_unicodeScripts = array(
		"Arabic", "Armenian", "Avestan", "Balinese", "Bamum", "Batak",
		"Bengali", "Bopomofo", "Brahmi", "Braille", "Buginese", "Buhid",
		"Canadian_Aboriginal", "Carian", "Chakma", "Cham", "Cherokee", "Common",
		"Coptic", "Cuneiform", "Cypriot", "Cyrillic", "Deseret", "Devanagari",
		"Egyptian_Hieroglyphs", "Ethiopic", "Georgian", "Glagolitic", "Gothic",
		"Greek", "Gujarati", "Gurmukhi", "Han", "Hangul", "Hanunoo", "Hebrew",
		"Hiragana", "Imperial_Aramaic", "Inherited", "Inscriptional_Pahlavi",
		"Inscriptional_Parthian", "Javanese", "Kaithi", "Kannada", "Katakana",
		"Kayah_Li", "Kharoshthi", "Khmer", "Lao", "Latin", "Lepcha", "Limbu",
		"Linear_B", "Lisu", "Lycian", "Lydian", "Malayalam", "Mandaic",
		"Meetei_Mayek", "Meroitic_Cursive", "Meroitic_Hieroglyphs", "Miao",
		"Mongolian", "Myanmar", "New_Tai_Lue", "Nko", "Ogham", "Old_Italic",
		"Old_Persian", "Old_South_Arabian", "Old_Turkic", "Ol_Chiki", "Oriya",
		"Osmanya", "Phags_Pa", "Phoenician", "Rejang", "Runic", "Samaritan",
		"Saurashtra", "Sharada", "Shavian", "Sinhala", "Sora_Sompeng",
		"Sundanese", "Syloti_Nagri", "Syriac", "Tagalog", "Tagbanwa", "Tai_Le",
		"Tai_Tham", "Tai_Viet", "Takri", "Tamil", "Telugu", "Thaana", "Thai",
		"Tibetan", "Tifinagh", "Ugaritic", "Vai", "Yi"
	);
	
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
		return preg_match("#^[^\\\\a-z\d\s]$#i", $delimiter) === 1;
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
	
	/**
	 * Return true if the given string is a valid unicode property code,
	 * otherwise false
	 * 
	 * @param string $str String to check
	 * @return string
	 * @static
	 */
	public static function validateUnicodePropertyCode ($str)
	{
		return in_array($str, self::$_unicodePropertyCodes);
	}
	
	/**
	 * Return true if the given string is a valid unicode script, otherwise
	 * false
	 * 
	 * @param string $str String to check
	 * @return string
	 * @static
	 */
	public static function validateUnicodeScript ($str)
	{
		return in_array($str, self::$_unicodeScripts);
	}
	
	/**
	 * Return true if the given string contains only valid hexadecimal digits,
	 * otherwise false
	 * 
	 * @param string $str String to check
	 * @return string
	 * @static
	 */
	public static function validateHexString ($str)
	{
		return preg_match("#^[0-9a-f]+$#i", $str) === 1;
	}
}
