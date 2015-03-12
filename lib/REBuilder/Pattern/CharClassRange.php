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

/**
 * Represents a range of characters in a character class
 * 
 * @author Marco Marchiò <marco.mm89@gmail.com>
 * 
 * @link http://php.net/manual/en/regexp.reference.character-classes.php
 * 
 * @method AbstractPattern getStart()
 *         getStart()
 *         Returns the start pattern of the range
 * 
 * @method CharClassRange setStart()
 *         setStart(AbstractPattern $pattern)
 *         Sets the start pattern of the range
 * 
 * @method AbstractPattern getEnd()
 *         getEnd()
 *         Returns the end pattern of the range
 * 
 * @method CharClassRange setEnd()
 *         setEnd(AbstractPattern $pattern)
 *         Sets the end pattern of the range
 */
class CharClassRange extends AbstractContainer
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
    protected $_limitParent = "REBuilder\Pattern\CharClass";
    
    /**
     * Adds a child to the class at the given index
     * 
     * @param AbstractPattern $child Child to add
     * @param int             $index Index
     * 
     * @return CharClass
     * 
     * @throws \REBuilder\Exception\Generic
     */
    public function addChildAt (AbstractPattern $child, $index = null)
    {
        if (!$child->canBeAddedToCharClassRange()) {
            throw new \REBuilder\Exception\Generic(
                $this->_getClassName($child) .
                " cannot be added to character class ranges"
            );
        } elseif (count($this->getChildren()) === 2) {
            throw new \REBuilder\Exception\Generic(
                "Character class ranges can contain only 2 children"
            );
        }
        return parent::addChildAt($child, $index);
    }
    
    /**
     * Returns the string representation of the class
     * 
     * @return string
     * 
     * @throws \REBuilder\Exception\Generic
     */
    public function render ()
    {
        if (count($this->getChildren()) !== 2) {
            throw new \REBuilder\Exception\Generic(
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
     * 
     * @return mixed
     * 
     * @throws \REBuilder\Exception\Generic
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
                    !$arguments[0] instanceof AbstractPattern) {
                    throw new \REBuilder\Exception\Generic(
                        "$name requires a pattern"
                    );
                } elseif (!$arguments[0]->canBeAddedToCharClassRange()) {
                    throw new \REBuilder\Exception\Generic(
                        $this->_getClassName($arguments[0]) .
                        " cannot be added to character class ranges"
                    );
                }
                return $this->removeChildAt($index)
                            ->addChildAt($arguments[0], $index);
            }
        }
        return parent::__call($name, $arguments);
    }
}