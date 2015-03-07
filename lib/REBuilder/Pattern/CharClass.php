<?php
/**
 * Represents a character class
 * 
 * @author Marco Marchiò
 * @link http://php.net/manual/en/regexp.reference.character-classes.php
 * 
 * @method REBuilder_Pattern_PosixCharClass addPosixCharClass()
 *         addPosixCharClass(string $class, bool $negate)
 *         Adds a new REBuilder_Pattern_PosixCharClass class instance to this container
 *         @see REBuilder_Pattern_PosixCharClass::__construct
 * 
 * @method REBuilder_Pattern_AbstractContainer addPosixCharClassAndContinue()
 *         addPosixCharClassAndContinue(string $class, bool $negate)
 *         Same as addPosixCharClass but it returns the current container
 *         @see REBuilder_Pattern_PosixCharClass::__construct
 * 
 * @method REBuilder_Pattern_CharClassRange addCharClassRange()
 *         addCharClassRange()
 *         Adds a new REBuilder_Pattern_CharClassRange class instance to this container
 *         @see REBuilder_Pattern_CharClassRange::__construct
 * 
 * @method REBuilder_Pattern_AbstractContainer addCharClassRangeAndContinue()
 *         addCharClassRangeAndContinue()
 *         Same as addCharClassRange but it returns the current container
 *         @see REBuilder_Pattern_CharClassRange::__construct
 */
class REBuilder_Pattern_CharClass extends REBuilder_Pattern_AbstractContainer
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
     * @param string $negate  True to create a negative character class
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
     */
    public function setNegate ($negate)
    {
        $this->_negate = $negate;
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
     * @param REBuilder_Pattern_Abstract $child Child to add
     * @param int                        $index Index
     * @return REBuilder_Pattern_CharClass
     * @throw REBuilder_Exception_Generic
     */
    public function addChildAt (REBuilder_Pattern_Abstract $child, $index = null)
    {
        if (!$child->canBeAddedToCharClass()) {
            throw new REBuilder_Exception_Generic(
                $this->_getClassName($child) . " cannot be added to character classes"
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