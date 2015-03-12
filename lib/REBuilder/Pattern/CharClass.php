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
 * Represents a character class
 * 
 * @author Marco Marchiò <marco.mm89@gmail.com>
 * 
 * @link http://php.net/manual/en/regexp.reference.character-classes.php
 * 
 * @method PosixCharClass addPosixCharClass()
 *         addPosixCharClass(string $class, bool $negate)
 *         Adds a new Posix character class to this container
 *         @see PosixCharClass::__construct
 * 
 * @method AbstractContainer addPosixCharClassAndContinue()
 *         addPosixCharClassAndContinue(string $class, bool $negate)
 *         Same as addPosixCharClass but it returns the current container
 *         @see PosixCharClass::__construct
 * 
 * @method CharClassRange addCharClassRange()
 *         addCharClassRange()
 *         Adds a new character class range to this container
 *         @see CharClassRange::__construct
 * 
 * @method AbstractContainer addCharClassRangeAndContinue()
 *         addCharClassRangeAndContinue()
 *         Same as addCharClassRange but it returns the current container
 *         @see CharClassRange::__construct
 */
class CharClass extends AbstractContainer
{
    /**
     * Flag that indicates if the container supports anchors
     *
     * @var bool
     */
    protected $_supportsAnchors = false;
    
    /**
     * Negation flag
     * 
     * @var bool
     */
    protected $_negate = false;
    
    /**
     * Constructor
     * 
     * @param string $negate True to create a negative character class
     */
    public function __construct ($negate = false)
    {
        $this->setNegate($negate);
    }
    
    /**
     * If the character class is negated it will match anything but the content
     * of the class
     * 
     * @param bool $negate True to negate the character class
     * 
     * @return CharClass
     */
    public function setNegate ($negate)
    {
        $this->_negate = $negate;
        return $this;
    }

    /**
     * Returns the negate flag
     * 
     * @return bool
     */
    public function getNegate ()
    {
        return $this->_negate;
    }
    
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
        if (!$child->canBeAddedToCharClass()) {
            throw new \REBuilder\Exception\Generic(
                $this->_getClassName($child) .
                " cannot be added to character classes"
            );
        }
        return parent::addChildAt($child, $index);
    }

    /**
     * Returns the string representation of the class
     * 
     * @return string
     */
    public function render ()
    {
        $ret = "[";
        if ($this->getNegate()) {
            $ret .= "^";
        }
        $ret .= $this->renderChildren();
        $ret .= "]";
        $ret .= $this->_renderRepetition();
        return $ret;
    }
}