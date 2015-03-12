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
 * Represents unicode character classes: \p, \P, \X
 * 
 * @author Marco Marchiò <marco.mm89@gmail.com>
 * 
 * @link http://php.net/manual/en/regexp.reference.unicode.php
 */
class UnicodeCharClass extends AbstractPattern
{
    /**
     * Character class to match
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
     * Constructor
     * 
     * @param string $class  Character class to match
     * @param string $negate True to create a negative match
     */
    public function __construct ($class = null, $negate = false)
    {
        if ($class !== null) {
            $this->setClass($class);
        }
        $this->setNegate($negate);
    }

    /**
     * If the unicode character class is not negated (\p) every character that
     * belongs to that class will be matched, if negated everything is matched
     * except those characters that belong to that class. Negation is not
     * supported for extended unicode sequence (\X)
     * 
     * @param bool $negate True to negate the match
     * 
     * @return UnicodeCharClass
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
     * Sets the character class. It can be any supported unicode property code
     * or script. If "X" it will be used as extended unicode sequence (\X)
     * 
     * @param string $class Character class to match
     * 
     * @return UnicodeCharClass
     * 
     * @throws \REBuilder\Exception\Generic
     * 
     * @link http://php.net/manual/en/regexp.reference.escape.php
     */
    public function setClass ($class)
    {
        if ($class !== "X" &&
            !\REBuilder\Parser\Rules::validateUnicodePropertyCode($class) &&
            !\REBuilder\Parser\Rules::validateUnicodeScript($class)) {
            throw new \REBuilder\Exception\Generic(
                "Unknow unicode character class '$class'"
            );
        }
        $this->_class = $class;
        return $this;
    }

    /**
     * Returns the character class to match
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
                "No character class has been set"
            );
        }
        $ret = "";
        if ($this->getClass() === "X") {
            if ($this->getNegate()) {
                throw new \REBuilder\Exception\Generic(
                    "Negation is not supported for \X"
                );
            }
            $ret = "\X";
        } else {
            $ret = "\\" . ($this->getNegate() ? "P" : "p") .
                   "{" . $this->getClass() . "}";
        }
        $ret .= $this->_renderRepetition();
        return $ret;
    }
}