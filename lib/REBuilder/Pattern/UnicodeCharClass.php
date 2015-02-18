<?php
/**
 * Represents unicode character classes: \p, \P, \X
 * 
 * @author Marco Marchiò
 * @abstract
 * @link http://php.net/manual/en/regexp.reference.unicode.php
 */
class REBuilder_Pattern_UnicodeCharClass extends REBuilder_Pattern_Abstract
{
	/**
	 * Character class to match
	 * 
	 * @var string
	 */
	protected $_class;
	
	/**
	 * Negation flag
	 * 
	 * @var bool
	 */
	protected $_negate = false;
	
	/**
	 * Constructor
	 * 
	 * @param string $class   Character class to match
	 * @param string $negate  True to create a negative match
	 */
	public function __construct ($class = null, $negate = false)
	{
		if ($class !== null) {
			$this->setClass($class);
		}
		$this->setNegate($negate);
	}
	
	/**
	 * If the unicode character class is not negated (\p) every character that
	 * belongs to that class will be matched, if negated everything is matched
	 * except those characters that belong to that class. Negation is not
	 * supported for extended unicode sequence (\X)
	 * 
	 * @param bool $negate True to negate the match
	 * @throws REBuilder_Exception_Generic
	 */
	public function setNegate ($negate)
	{
		$this->_negate = $negate;
	}
	
	/**
	 * Returns the negate flag
	 * 
	 * @return bool
	 */
	public function getNegate ()
	{
		return $this->_negate;
	}
	
	/**
	 * Sets the character class. It can be any supported unicode property code
	 * or script. If "X" it will be used as extended unicode sequence (\X)
	 * 
	 * @param string $class Character class to match
	 * @return REBuilder_Pattern_UnicodeCharClass
	 * @throws REBuilder_Exception_Generic
	 * @link http://php.net/manual/en/regexp.reference.escape.php
	 */
	public function setClass ($class)
	{
		if ($class !== "X" &&
			!REBuilder_Parser_Rules::validateUnicodePropertyCode($class) &&
			!REBuilder_Parser_Rules::validateUnicodeScript($class)) {
			throw new REBuilder_Exception_Generic(
				"Unknow unicode character class '$class'"
			);
		}
		$this->_class = $class;
	}
	
	/**
	 * Returns the character class to match
	 * 
	 * @return string
	 */
	public function getClass ()
	{
		return $this->_class;
	}
	
	/**
	 * Returns the string representation of the class
	 * 
	 * @return string
	 */
	public function render ()
	{
		if ($this->_class === null) {
			throw new REBuilder_Exception_Generic(
				"No character class has been set"
			);
		}
		$ret = "";
		if ($this->_class === "X") {
			if ($this->_negate) {
				throw new REBuilder_Exception_Generic(
					"Negation is not supported for \X"
				);
			}
			$ret = "\X";
		} else {
			$ret =	"\\" . ($this->_negate ? "P" : "p") .
					"{" . $this->_class . "}";
		}
		$ret .= $this->_renderRepetition();
		return $ret;
	}
}