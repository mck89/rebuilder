<?php
/**
 * Represents the hexadecimal character identifier \x
 * 
 * @author Marco Marchiò
 * @abstract
 * @link http://php.net/manual/en/regexp.reference.escape.php
 */
class REBuilder_Pattern_HexChar extends REBuilder_Pattern_AbstractChar
{
    /**
     * Flag that identifies if the pattern can be added to character classes
     * 
     * @var bool
     */
    protected $_canBeAddedToCharClass = true;
    
    /**
     * Flag that identifies if the pattern can be added to character class ranges
     * 
     * @var bool
     */
    protected $_canBeAddedToCharClassRange = true;
    
	/**
	 * Sets the character code . It can be 0, 1 or 2 hexadecimal digits
	 * that represent a character code
	 * 
	 * @param string $char Character to match
	 * @return REBuilder_Pattern_HexChar
	 * @throws REBuilder_Exception_Generic
	 * @link http://php.net/manual/en/regexp.reference.escape.php
	 */
	public function setChar ($char)
	{
		if ($char !== "" &&
			!REBuilder_Parser_Rules::validateHexString($char)) {
			throw new REBuilder_Exception_Generic(
				"Invalid hexadecimal sequence '$char'"
			);
		} elseif (strlen($char) > 2) {
			throw new REBuilder_Exception_Generic(
				"Hexadecimal character can match a maximum of 2 digits"
			);
		}
		return parent::setChar($char);
	}
	
	/**
	 * Returns the string representation of the class
	 * 
	 * @return string
	 */
	public function render ()
	{
		return "\x" . $this->getChar() . $this->_renderRepetition();
	}
}
