<?php
/**
 * Represents a character or a group of characters that will be matched as they
 * are, like "a" and "bc" in /a.bc/
 * 
 * @author Marco Marchiò
 * @abstract
 */
class REBuilder_Pattern_Char extends REBuilder_Pattern_Abstract
{
	/**
	 * Character to match
	 * 
	 * @var string
	 */
	protected $_char;
	
	/**
	 * Constructor
	 * 
	 * @param string $char Character to match
	 */
	public function __construct ($char = null)
	{
		if ($char !== null) {
			$this->setChar($char);
		}
	}
	
	/**
	 * Sets the character to match. It can handle multiple characters too.
	 * 
	 * @param string $char Character to match
	 * @return REBuilder_Pattern_Abstract
	 */
	public function setChar ($char)
	{
		$this->_char = $char;
		return $this;
	}
	
	/**
	 * Returns the character to match
	 * 
	 * @return string
	 */
	public function getChar ()
	{
		return $this->_char;
	}
	
	/**
	 * Returns the string representation of the class
	 * 
	 * @return string
	 */
	public function render ()
	{
		if ($this->_char === null || $this->_char === "") {
			throw new REBuilder_Exception_Generic(
				"No character has been set"
			);
		}
		$needsGroup = strlen($this->_char) > 1 && $this->getRepetition();
		$char = $this->getParentRegex()->quote($this->_char);
		return ($needsGroup ? "(?:$char)" : $char) . $this->_renderRepetition();
	}
}