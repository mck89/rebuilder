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
 * Represents a POSIX character class. This class can only be added to
 * character classes
 * 
 * @author Marco Marchiò <marco.mm89@gmail.com>
 * 
 * @link http://php.net/manual/en/regexp.reference.character-classes.php
 */
class PosixCharClass extends AbstractPattern
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
     * If this property is not empty the current class can be added only
     * to containers of the given instance
     * 
     * @var string
     */
    protected $_limitParent = "REBuilder\Pattern\CharClass";
    
    /**
     * Constructor
     * 
     * @param string $class  Posix character class to match
     * @param string $negate True to negate the class
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
     * 
     * @return PosixCharClass
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
     * Sets the POSIX character class. It can be any supported POSIX classes
     * 
     * @param string $class Character class to match
     * 
     * @return PosixCharClass
     * 
     * @throws \REBuilder\Exception\Generic
     * 
     * @link http://php.net/manual/en/regexp.reference.character-classes.php
     */
    public function setClass ($class)
    {
        if (!\REBuilder\Parser\Rules::validatePosixCharClass($class)) {
            throw new \REBuilder\Exception\Generic(
                "Unknow POSIX character class '$class'"
            );
        }
        $this->_class = $class;
        return $this;
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
     * 
     * @throws \REBuilder\Exception\Generic
     */
    public function render ()
    {
        if ($this->getClass() === null) {
            throw new \REBuilder\Exception\Generic(
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