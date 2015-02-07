<?php
/**
 * Represents the control character identifier \c
 * 
 * @author Marco Marchiò
 * @abstract
 * @link http://php.net/manual/en/regexp.reference.escape.php
 */
class REBuilder_Pattern_ControlChar extends REBuilder_Pattern_Simple
{
	/**
	 * Sets the subject. It can be any character
	 * 
	 * @param string $subject Subject to match
	 * @return REBuilder_Pattern_ControlChar
	 * @link http://php.net/manual/en/regexp.reference.escape.php
	 */
	public function setSubject ($subject)
	{
		if (strlen($subject) !== 1) {
			throw new REBuilder_Exception_Generic(
				"Control character requires a single character"
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
		return "\\c" . $this->_subject;
	}
}