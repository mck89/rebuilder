<?php
/**
 * Represents a range of characters in a character class
 * 
 * @author Marco Marchiò
 * @link http://php.net/manual/en/regexp.reference.character-classes.php
 * 
 * @method REBuilder_Pattern_Abstract getStart()
 *         getStart()
 *         Returns the start pattern of the range
 * 
 * @method REBuilder_Pattern_CharClassRange setStart()
 *         setStart(REBuilder_Pattern_Abstract $pattern)
 *         Sets the start pattern of the range
 * 
 * @method REBuilder_Pattern_Abstract getEnd()
 *         getEnd()
 *         Returns the end pattern of the range
 * 
 * @method REBuilder_Pattern_CharClassRange setEnd()
 *         setEnd(REBuilder_Pattern_Abstract $pattern)
 *         Sets the end pattern of the range
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
     * Adds a child to the class at the given index
     * 
     * @param REBuilder_Pattern_Abstract $child Child to add
     * @param int                        $index Index
     * @return REBuilder_Pattern_CharClass
     * @throw REBuilder_Exception_Generic
     */
    public function addChildAt (REBuilder_Pattern_Abstract $child, $index = null)
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
        return parent::addChildAt($child, $index);
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
    
    /**
     * Allows to call setStart, getStart, setEnd and getEnd functions
     * 
     * @param string $name      Method name
     * @param array  $arguments Method arguments
     * @return mixed
     */
    function __call ($name, $arguments)
    {
        if (preg_match("/^(get|set)(Start|End)$/", $name, $match)) {
            $index = $match[2] === "Start" ? 0 : 1;
            if ($match[1] === "get") {
                return isset($this->_children[$index]) ?
                       $this->_children[$index] :
                       null;
            } else {
                if (!count($arguments) ||
                    !$arguments[0] instanceof REBuilder_Pattern_Abstract) {
                    throw new REBuilder_Exception_Generic(
                        "$name requires a pattern"
                    );
                } elseif (!$arguments[0]->canBeAddedToCharClassRange()) {
                    throw new REBuilder_Exception_Generic(
                        $this->_getClassName($arguments[0]) . " cannot be added to character class ranges"
                    );
                }
                return $this->removeChildAt($index)
                            ->addChildAt($arguments[0], $index);
            }
        }
        return parent::__call($name, $arguments);
    }
}