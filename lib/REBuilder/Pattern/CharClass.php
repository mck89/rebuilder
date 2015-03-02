<?php
/**
 * Represents a character class
 * 
 * @author Marco Marchiò
 * @link http://php.net/manual/en/regexp.reference.character-classes.php
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
     * Adds a child to the class
     * 
     * @param REBuilder_Pattern_Abstract $child Child to add
     * @return REBuilder_Pattern_CharClass
     */
    public function addChild (REBuilder_Pattern_Abstract $child)
    {
        if (!$child->canBeAddedToCharClass()) {
            $classParts = explode("_", get_class($child));
            throw new REBuilder_Exception_InvalidRepetition(
                $classParts[count($classParts) - 1] . " cannot be added to character classes"
            );
        }
        return parent::addChild($child);
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