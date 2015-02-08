<?php
/**
 * Represents generic character types: \d, \D, \h, \H, \s, \S, \v, \V, \w, \W
 * 
 * @author Marco Marchiò
 * @abstract
 * @link http://php.net/manual/en/regexp.reference.escape.php
 */
class REBuilder_Pattern_GenericCharType extends REBuilder_Pattern_Abstract
{
	/**
	 * Identifier to match
	 * 
	 * @var string
	 */
	protected $_identifier;
	
	/**
	 * Constructor
	 * 
	 * @param string $identifier Identifier to match
	 */
	public function __construct ($identifier = null)
	{
		if ($identifier !== null) {
			$this->setIdentifier($identifier);
		}
	}
	
	/**
	 * Sets the identifier. It can be one of the following:
	 * "d", "D", "h", "H", "s", "S", "v", "V", "w", "W"
	 * 
	 * @param string $identifier Identifier to match
	 * @return REBuilder_Pattern_GenericCharType
	 * @throws REBuilder_Exception_Generic
	 * @link http://php.net/manual/en/regexp.reference.escape.php
	 */
	public function setIdentifier ($identifier)
	{
		if (!REBuilder_Parser_Rules::validateGenericCharType($identifier)) {
			throw new REBuilder_Exception_Generic(
				"'$identifier' is not a valid generic character type identifier"
			);
		}
		$this->_identifier = $identifier;
		return $this;
	}
	
	/**
	 * Returns the identifier to match
	 * 
	 * @return string
	 */
	public function getIdentifier ()
	{
		return $this->_identifier;
	}
	
	/**
	 * Returns the string representation of the class
	 * 
	 * @return string
	 */
	public function render ()
	{
		if ($this->_identifier === null) {
			throw new REBuilder_Exception_Generic(
				"No identifier has been set"
			);
		}
		return "\\" . $this->_identifier . $this->_renderRepetition();
	}
}