<?php
/**
 * This file is part of the REBuilder package
 *
 * (c) Marco Marchiò <marco.mm89@gmail.com>
 *
 * For the full copyright and license information refer to the LICENSE file
 * distributed with this source code
 */

namespace REBuilder\Pattern;

use REBuilder\Exception;
use REBuilder\Pattern\Repetition;

/**
 * Abstract class for patterns
 * 
 * @author Marco Marchiò <marco.mm89@gmail.com>
 * 
 * @abstract
 */
abstract class AbstractPattern
{
    /**
     * Parent
     * 
     * @var AbstractContainer 
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
     * Flag that identifies if the pattern can be added to character class ranges
     * 
     * @var bool
     */
    protected $_canBeAddedToCharClassRange = false;
    
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
     * @var Repetition\Abstract
     */
    protected $_repetition;

    /**
     * Sets the parent
     * 
     * @param AbstractContainer $parent Parent container
     * 
     * @return AbstractPattern
     * 
     * @throws \REBuilder\Exception\Generic
     */
    public function setParent (AbstractContainer $parent)
    {
        //Throw exception if the parent of this type is not supported
        if ($this->_limitParent && !$parent instanceof $this->_limitParent) {
            $thisClass = $this->_getClassName();
            $parentClass = $this->_getClassName($this->_limitParent);
            throw new Exception\Generic(
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
     * @return AbstractContainer
     */
    public function getParent ()
    {
        return $this->_parent;
    }
    
    /**
     * Returns the parent regex
     * 
     * @return Regex
     */
    public function getParentRegex ()
    {
        $parent = $this->getParent();
        while ($parent && !$parent instanceof Regex) {
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
     * Returns true if the pattern can be added to character class ranges
     * 
     * @return bool
     */
    public function canBeAddedToCharClassRange ()
    {
        return $this->_canBeAddedToCharClassRange;
    }

    /**
     * Sets the repetition. This function throws an exception if the current
     * class does not handle repetition
     * 
     * @param mixed $repetition Repetition. It can be an instance of any class
     *                           that extends AbstractRepetition.
     *                           If "*", "+" or "?" are passed, the
     *                           corresponding repetitions will be used. If
     *                           it's a number, Repetition\Number or
     *                           Repetition\Range are used depending on the
     *                           second argument
     * @param mixed $max        For "*" and "+" a boolean value is accepted and
     *                           it's used to set the lazy flag. If a number is
     *                           passed as first argument and this argument is
     *                           omitted then a Repetition\Number is used,
     *                           otherwise if this argument is null or a number
     *                           a Repetition\Range is used
     * 
     * @return AbstractPattern
     * 
     * @throws \REBuilder\Exception\InvalidRepetition
     */
    public function setRepetition ($repetition, $max = null)
    {
        if (!$this->supportsRepetition()) {
            throw new Exception\InvalidRepetition(
                $this->_getClassName() . " cannot handle repetition"
            );
        }
        if (!$repetition instanceof Repetition\AbstractRepetition) {
            if ($repetition === "*") {
                $repetition = new Repetition\ZeroOrMore(
                    is_bool($max) ? $max : false
                );
            } elseif ($repetition === "+") {
                $repetition = new Repetition\OneOrMore(
                    is_bool($max) ? $max : false
                );
            } elseif ($repetition === "?") {
                $repetition = new Repetition\Optional();
            } elseif (is_numeric($repetition) &&
                      ($max === null || is_numeric($max))) {
                if (func_num_args() === 1) {
                    $repetition = new Repetition\Number($repetition);
                } else {
                    $repetition = new Repetition\Range($repetition, $max);
                }
            } else {
                throw new Exception\InvalidRepetition(
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
     * @param AbstractPattern $obj If an object is given the function
     *                             returns the class of that object
     * 
     * @return string
     */
    protected function _getClassName ($obj = null)
    {
        if ($obj) {
            $className = is_string($obj) ? $obj : get_class($obj);
        } else {
            $className = get_class($this);
        }
        $classParts = explode("\\", $className);
        return $classParts[count($classParts) - 1];
    }

    /**
     * Returns the repetition or null if has not been set
     * 
     * @return Repetition\AbstractRepetition
     */
    public function getRepetition ()
    {
        return $this->_repetition;
    }

    /**
     * Returns the string representation of the class
     * 
     * @return string
     * 
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