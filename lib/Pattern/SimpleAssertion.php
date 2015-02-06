<?php
/**
 * Represents simple assertions: "b", "B", "A", "Z", "z", "g", "Q", "E", "K"
 * 
 * @author Marco Marchiò
 * @abstract
 * @link http://php.net/manual/en/regexp.reference.escape.php
 */
class REBuilder_Pattern_SimpleAssertion extends REBuilder_Pattern_GenericCharType
{
	/**
	 * Sets the subject. It can be one of the following identifiers:
	 * "b", "B", "A", "Z", "z", "g", "Q", "E", "K"
	 * 
	 * @param string $subject Subject to match
	 * @return REBuilder_Pattern_SimpleAssertion
	 * @link http://php.net/manual/en/regexp.reference.escape.php
	 */
	public function setSubject ($subject)
	{
		if (!REBuilder_Parser_Rules::validateSimpleAssertion($subject)) {
			throw new REBuilder_Exception_Generic(
				"'$subject' is not a valid simple assertion type identifier"
			);
		}
		$this->_subject = $subject;
	}
}