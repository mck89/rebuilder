<?php
/**
 * Represents simple assertions: \b, \B, \A, \Z, \z, \G, \Q, \E, \K
 * 
 * @author Marco Marchiò
 * @abstract
 * @link http://php.net/manual/en/regexp.reference.escape.php
 */
class REBuilder_Pattern_SimpleAssertion extends REBuilder_Pattern_AbstractIdentifier
{
	/**
	 * Flag that identifies if the pattern supports repetitions
	 * 
	 * @var bool
	 */
	protected $_supportsRepetition = false;
	
	/**
	 * Sets the identifier. It can be one of the following:
	 * "b", "B", "A", "Z", "z", "G", "Q", "E", "K"
	 * 
	 * @param string $identifier Identifier to match
	 * @return REBuilder_Pattern_SimpleAssertion
	 * @link http://php.net/manual/en/regexp.reference.escape.php
	 */
	public function setIdentifier ($identifier)
	{
		if (!REBuilder_Parser_Rules::validateSimpleAssertion($identifier)) {
			throw new REBuilder_Exception_Generic(
				"'$identifier' is not a valid simple assertion type identifier"
			);
		}
		return parent::setIdentifier($identifier);
	}
}