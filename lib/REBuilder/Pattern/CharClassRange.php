<?php
/**
 * Represents a range of characters in a character class
 * 
 * @author Marco Marchiò
 * @link http://php.net/manual/en/regexp.reference.character-classes.php
 */
class REBuilder_Pattern_CharClassRange extends REBuilder_Pattern_AbstractContainer
{
    /**
     * Flag that identifies if the pattern can be added to character classes
     * 
     * @var bool
     */
    protected $_canBeAddedToCharClass = true;
    
    /**
     * Flag that identifies if the pattern supports repetitions
     * 
     * @var bool
     */
    protected $_supportsRepetition = false;
    
    /**
     * If this property is not empty the current class can be added only
     * to containers of the given instance
     * 
     * @var string
     */
    protected $_limitParent = "REBuilder_Pattern_CharClass";
    
    /**
     * Adds a child to the class
     * 
     * @param REBuilder_Pattern_Abstract $child Child to add
     * @return REBuilder_Pattern_CharClass
     * @throw REBuilder_Exception_Generic
     */
    public function addChild (REBuilder_Pattern_Abstract $child)
    {
        if (!$child->canBeAddedToCharClassRange()) {
            throw new REBuilder_Exception_Generic(
                $this->_getClassName($child) . " cannot be added to character class ranges"
            );
        } elseif (count($this->getChildren()) === 2) {
            throw new REBuilder_Exception_Generic(
                "Character class ranges can contain only 2 children"
            );
        }
        return parent::addChild($child);
    }
    
    /**
     * Returns the string representation of the class
     * 
     * @return string
     * @throw REBuilder_Exception_Generic
     */
    public function render ()
    {
        if (count($this->getChildren()) !== 2) {
            throw new REBuilder_Exception_Generic(
                "Character class ranges must contain 2 children"
            );
        }
        return implode("-", $this->getChildren());
    }
}