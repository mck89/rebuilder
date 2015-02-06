<?php
/**
 * Represents generic character types: "d", "D", "h", "H", "s", "S", "v", "V",
 * "w", "W"
 * 
 * @author Marco Marchiò
 * @abstract
 * @link http://php.net/manual/en/regexp.reference.escape.php
 */
class REBuilder_Pattern_GenericCharType extends REBuilder_Pattern_Simple
{
	
	/**
	 * Sets the subject. It can be one of the following identifiers:
	 * "d", "D", "h", "H", "s", "S", "v", "V", "w", "W"
	 * 
	 * @param string $subject Subject to match
	 * @return REBuilder_Pattern_GenericCharType
	 * @link http://php.net/manual/en/regexp.reference.escape.php
	 */
	public function setSubject ($subject)
	{
		if (!REBuilder_Parser_Rules::validateGenericCharType($subject)) {
			throw new REBuilder_Exception_Generic(
				"'$subject' is not a valid generic character type identifier"
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
		return "\\" . $this->_subject;
	}
}