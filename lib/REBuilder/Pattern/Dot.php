<?php
/**
 * Represents the dot that matches all characters
 * 
 * @author Marco Marchiò
 * @abstract
 * @link http://php.net/manual/en/regexp.reference.dot.php
 */
class REBuilder_Pattern_Dot extends REBuilder_Pattern_Abstract
{
    /**
     * Returns the string representation of the class
     * 
     * @return string
     */
    public function render ()
    {
        return "." . $this->_renderRepetition();
    }
}