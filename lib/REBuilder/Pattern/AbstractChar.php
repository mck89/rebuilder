<?php
/**
 * Abstract class for patterns that represent a character
 * 
 * @author Marco Marchiò
 * @abstract
 */
abstract class REBuilder_Pattern_AbstractChar extends REBuilder_Pattern_Abstract
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
     * Sets the character to match.
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
        if ($parentRegex = $this->getParentRegex()) {
            return $parentRegex->quote($this->_char);
        } else {
            return preg_quote($this->_char);
        }
    }
}