<?php
/**
 * Represents a character identified by an octal number
 * 
 * @author Marco Marchiò
 * @abstract
 * @link http://php.net/manual/en/regexp.reference.escape.php
 */
class REBuilder_Pattern_OctalChar extends REBuilder_Pattern_AbstractChar
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
	 * Sets the character code . It can be 0, 1 or 2 digits that represents
	 * the octal number that identifies the character
	 * 
	 * @param string $char Character to match
	 * @return REBuilder_Pattern_OctalChar
	 * @throws REBuilder_Exception_Generic
	 * @link http://php.net/manual/en/regexp.reference.escape.php
	 */
	public function setChar ($char)
	{
		if (!preg_match("#^(?:0[0-7]{1,2}|[0-7]{2,3})$#", $char)) {
			throw new REBuilder_Exception_Generic(
				"Invalid octal character '$char'"
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
        if ($this->getChar() === null || $this->getChar() === "") {
			throw new REBuilder_Exception_Generic(
				"Empty octal character"
			);
		}
		return "\\" . $this->getChar();
	}
}