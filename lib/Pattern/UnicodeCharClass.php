<?php
/**
 * Represents unicode character classes: \p, \P, \X
 * 
 * @author Marco Marchiò
 * @abstract
 * @link http://php.net/manual/en/regexp.reference.unicode.php
 */
class REBuilder_Pattern_UnicodeCharClass extends REBuilder_Pattern_Simple
{
	/**
	 * Negation flag
	 * 
	 * @var bool
	 */
	protected $_negate = false;
	
	/**
	 * Constructor
	 * 
	 * @param string $subject Subject to match
	 * @param string $negate  True to create a negative match
	 */
	public function __construct ($subject, $negate = false)
	{
		$this->setSubject($subject);
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
		if ($negate && $this->_subject === "X") {
			throw new REBuilder_Exception_Generic(
				"Negation is not supported for \X"
			);
		}
		$this->_negate = $negate;
	}
	
	/**
	 * Sets the subject. It can be any supported unicode property code or
	 * script. If "X" it will be used as extended unicode sequence (\X)
	 * 
	 * @param string $subject Subject to match
	 * @return REBuilder_Pattern_UnicodeCharClass
	 * @throws REBuilder_Exception_Generic
	 * @link http://php.net/manual/en/regexp.reference.escape.php
	 */
	public function setSubject ($subject)
	{
		if ($subject !== "X" &&
			!REBuilder_Parser_Rules::validateUnicodePropertyCode($subject) &&
			!!REBuilder_Parser_Rules::validateUnicodeScript($subject)) {
			throw new REBuilder_Exception_Generic(
				"Unknow unicode character class '$subject'"
			);
		}
		$this->_subject = $subject;
	}
	
	/**
	 * Returns the string representation of the class
	 * 
	 * @return string
	 */
	public function render ()
	{
		$ret = "";
		if ($this->_subject === "X") {
			$ret = "\X";
		} else {
			$ret =	"\\" . ($this->_negate ? "P" : "p") .
					"{" . $this->_subject . "}";
		}
		$ret .= $this->_renderRepetition();
		return $ret;
	}
}