<?php
/**
 * Represents a POSIX character class. This class can only be added to
 * character classes
 * 
 * @author Marco Marchiò
 * @link http://php.net/manual/en/regexp.reference.character-classes.php
 */
class REBuilder_Pattern_PosixCharClass extends REBuilder_Pattern_Abstract
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
     * POSIX character class to match
     * 
     * @var string
     */
    protected $_class;

    /**
     * Negation flag
     * 
     * @var bool
     */
    protected $_negate = false;

    /**
     * Sets the parent
     * 
     * @param REBuilder_Pattern_AbstractContainer $parent Parent container
     * @return REBuilder_Pattern_Abstract
     */
    public function setParent (REBuilder_Pattern_AbstractContainer $parent)
    {
        if (!$parent instanceof REBuilder_Pattern_CharClass) {
            throw new REBuilder_Exception_Generic(
                "POSIX character classes can be added only to character classes"
            );
        }
        return parent::setParent($parent);
    }
    
    /**
     * Constructor
     * 
     * @param string $class   POSIX Character class to match
     * @param string $negate  True to negate the class
     */
    public function __construct ($class = null, $negate = false)
    {
        if ($class !== null) {
            $this->setClass($class);
        }
        $this->setNegate($negate);
    }

    /**
     * Set the negation flag for the POSIX character class. If the class is
     * negated anything but its characters are matched
     * 
     * @param bool $negate True to negate the match
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
     * Sets the POSIX character class. It can be any supported POSIX classes
     * 
     * @param string $class Character class to match
     * @return REBuilder_Pattern_PosixCharClass
     * @throws REBuilder_Exception_Generic
     * @link http://php.net/manual/en/regexp.reference.character-classes.php
     */
    public function setClass ($class)
    {
        if (!REBuilder_Parser_Rules::validatePosixCharClass($class)) {
            throw new REBuilder_Exception_Generic(
                "Unknow POSIX character class '$class'"
            );
        }
        $this->_class = $class;
    }

    /**
     * Returns the POSIX character class to match
     * 
     * @return string
     */
    public function getClass ()
    {
        return $this->_class;
    }

    /**
     * Returns the string representation of the class
     * 
     * @return string
     */
    public function render ()
    {
        if ($this->_class === null) {
            throw new REBuilder_Exception_Generic(
                "No POSIX character class has been set"
            );
        }
        $ret = "[:";
        if ($this->getNegate()) {
            $ret .= "^";
        }
        $ret .= $this->getClass();
        $ret .= ":]";
        return $ret;
    }
}