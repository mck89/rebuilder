<?php
/**
 * Abstract class for patterns
 * 
 * @author Marco Marchiò
 * @abstract
 */
abstract class REBuilder_Pattern_Abstract
{
    /**
     * Parent
     * 
     * @var REBuilder_Pattern_AbstractContainer 
     */
    protected $_parent;

    /**
     * Flag that identifies if the pattern supports repetitions
     * 
     * @var bool
     */
    protected $_supportsRepetition = true;
    
    /**
     * Flag that identifies if the pattern can be added to character classes
     * 
     * @var bool
     */
    protected $_canBeAddedToCharClass = false;
    
    /**
     * If this property is not empty the current class can be added only
     * to containers of the given instance
     * 
     * @var string
     */
    protected $_limitParent = "";

    /**
     * Repetition
     * 
     * @var REBuilder_Pattern_Repetition_Abstract
     */
    protected $_repetition;

    /**
     * Sets the parent
     * 
     * @param REBuilder_Pattern_AbstractContainer $parent Parent container
     * @return REBuilder_Pattern_Abstract
     */
    public function setParent (REBuilder_Pattern_AbstractContainer $parent)
    {
        //Throw exception if the parent of this type is not supported
        if ($this->_limitParent && !$parent instanceof $this->_limitParent) {
            $thisClass = $this->_getClassName();
            $parentClass = $this->_getClassName($this->_limitParent);
            throw new REBuilder_Exception_Generic(
                "$thisClass can be added only to $parentClass"
            );
        }
        //Before proceed remove it from the previous parent container
        if ($currentParent = $this->getParent()) {
            $currentParent->removeChild($this);
        }
        $this->_parent = $parent;
        return $this;
    }

    /**
     * Returns the parent
     * 
     * @return REBuilder_Pattern_AbstractContainer
     */
    public function getParent ()
    {
        return $this->_parent;
    }
    /**
     * Returns the parent regex
     * 
     * @return REBuilder_Pattern_Regex
     */
    public function getParentRegex ()
    {
        $parent = $this->getParent();
        while ($parent && !$parent instanceof REBuilder_Pattern_Regex) {
            $parent = $parent->getParent();
        }
        return $parent;
    }

    /**
     * Returns true if the pattern supports repetition, otherwise false
     * 
     * @return bool
     */
    public function supportsRepetition ()
    {
        return $this->_supportsRepetition;
    }
    
    /**
     * Returns true if the pattern can be added to character classes
     * 
     * @return bool
     */
    public function canBeAddedToCharClass ()
    {
        return $this->_canBeAddedToCharClass;
    }

    /**
     * Sets the repetition. This function throws an exception if the current
     * class does not handle repetition
     * 
     * @param mixed $repetition Repetition. It can be an instance of any class
     *                          that extends REBuilder_Pattern_Repetition_Abstract.
     *                          If "*", "+" or "?" are passed, the corresponding
     *                          repetitions will be used. If it's a number
     *                          REBuilder_Pattern_Repetition_Number or
     *                          REBuilder_Pattern_Repetition_Range are used
     *                          depending on the second argument
     * @param mixed $max        For "*" and "+" a boolean value is accepted and
     *                          it's used to set the lazy flag. If a number is
     *                          passed as first argument and this argument is
     *                          omitted them a REBuilder_Pattern_Repetition_Number
     *                          is used, otherwise if this argument is null or
     *                          a number a REBuilder_Pattern_Repetition_Range
     *                          will be used
     * @return REBuilder_Pattern_Abstract
     * @throws REBuilder_Exception_Generic
     */
    public function setRepetition ($repetition, $max = null)
    {
        if (!$this->supportsRepetition()) {
            throw new REBuilder_Exception_InvalidRepetition(
                $this->_getClassName() . " cannot handle repetition"
            );
        }
        if (!$repetition instanceof REBuilder_Pattern_Repetition_Abstract) {
            if ($repetition === "*") {
                $repetition = new REBuilder_Pattern_Repetition_ZeroOrMore(
                    is_bool($max) ? $max : false
                );
            } elseif ($repetition === "+") {
                $repetition = new REBuilder_Pattern_Repetition_OneOrMore(
                    is_bool($max) ? $max : false
                );
            } elseif ($repetition === "?") {
                $repetition = new REBuilder_Pattern_Repetition_Optional();
            } elseif (is_numeric($repetition) &&
                      ($max === null || is_numeric($max))) {
                if (func_num_args() === 1) {
                    $repetition = new REBuilder_Pattern_Repetition_Number($repetition);
                } else {
                    $repetition = new REBuilder_Pattern_Repetition_Range($repetition, $max);
                }
            } else {
                throw new REBuilder_Exception_InvalidRepetition(
                    "Invalid repetition '$repetition'"
                );
            }
        }
        $this->_repetition = $repetition;
        return $this;
    }
    
    /**
     * Returns a readable version of the current class
     * 
     * @param REBuilder_Pattern_Abstract $obj If an object is given the function
     *                                        returns the class of that object
     * @return string
     */
    protected function _getClassName ($obj = null)
    {
        if ($obj) {
            $className = is_string($obj) ? $obj : get_class($obj);
        } else {
            $className = get_class($this);
        }
        $classParts = explode("_", $className);
        return $classParts[count($classParts) - 1];
    }

    /**
     * Returns the repetition or null if has not been set
     * 
     * @return REBuilder_Pattern_Repetition_Abstract
     */
    public function getRepetition ()
    {
        return $this->_repetition;
    }

    /**
     * Returns the string representation of the class
     * 
     * @return string
     * @abstract
     */
    abstract public function render ();

    /**
     * Returns the string representation of the class repetition
     * 
     * @return string
     */
    protected function _renderRepetition ()
    {
        return $this->_repetition ? $this->_repetition->render() : "";
    }

    /**
     * Returns the string representation of the class
     * 
     * @return string
     */
    public function __toString ()
    {
        return $this->render();
    }
}