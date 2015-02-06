<?php
/**
 * Represents non printing characters: \a, \e, \f, \n, \r, \t
 * 
 * @author Marco Marchiò
 * @abstract
 * @link http://php.net/manual/en/regexp.reference.escape.php
 */
class REBuilder_Pattern_NonPrintingChar extends REBuilder_Pattern_GenericCharType
{
	
	/**
	 * Sets the subject. It can be one of the following identifiers:
	 * "a", "e", "f", "n", "r", "t".
	 * 
	 * @param string $subject Subject to match
	 * @return REBuilder_Pattern_NonPrintingChar
	 * @link http://php.net/manual/en/regexp.reference.escape.php
	 */
	public function setSubject ($subject)
	{
		if (!REBuilder_Parser_Rules::validateNonPrintingChar($subject)) {
			throw new REBuilder_Exception_Generic(
				"'$subject' is not a valid non-printing character identifier"
			);
		}
		$this->_subject = $subject;
	}
}