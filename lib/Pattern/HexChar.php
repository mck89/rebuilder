<?php
/**
 * Represents the hexadecimal character identifier \x
 * 
 * @author Marco Marchiò
 * @abstract
 * @link http://php.net/manual/en/regexp.reference.escape.php
 */
class REBuilder_Pattern_HexChar extends REBuilder_Pattern_Simple
{
	/**
	 * Sets the subject. It can be 0, 1 or 2 hexadecimal digits
	 * 
	 * @param string $subject Subject to match
	 * @return REBuilder_Pattern_HexChar
	 * @throws REBuilder_Exception_Generic
	 * @link http://php.net/manual/en/regexp.reference.escape.php
	 */
	public function setSubject ($subject)
	{
		if ($subject !== "" &&
			!REBuilder_Parser_Rules::validateHexString($subject)) {
			throw new REBuilder_Exception_Generic(
				"Invalid hexadecimal sequence '$subject'"
			);
		} elseif (strlen($subject) > 2) {
			throw new REBuilder_Exception_Generic(
				"Hexadecimal character can match a maximum of 2 digits"
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
		return "\x" . $this->_subject;
	}
}