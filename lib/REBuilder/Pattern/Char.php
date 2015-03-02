<?php
/**
 * Represents a character or a group of characters that will be matched as they
 * are, like "a" and "bc" in /a.bc/
 * 
 * @author Marco Marchiò
 * @abstract
 */
class REBuilder_Pattern_Char extends REBuilder_Pattern_AbstractChar
{
    /**
     * Flag that identifies if the pattern can be added to character classes
     * 
     * @var bool
     */
    protected $_canBeAddedToCharClass = true;
    
    /**
     * Returns the string representation of the class
     * 
     * @return string
     */
    public function render ()
    {
        $char = parent::render();
        $needsGroup = strlen($this->_char) > 1 && $this->getRepetition();
        return ($needsGroup ? "(?:$char)" : $char) . $this->_renderRepetition();
    }
}