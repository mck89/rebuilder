<?php
/**
 * Represents the identifier \C that matches a single byte
 * 
 * @author Marco Marchiò
 * @abstract
 * @link http://php.net/manual/en/regexp.reference.dot.php
 */
class REBuilder_Pattern_Byte extends REBuilder_Pattern_Abstract
{
    /**
     * Returns the string representation of the class
     * 
     * @return string
     */
    public function render ()
    {
        return "\C" . $this->_renderRepetition();
    }
}