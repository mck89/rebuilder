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
 * Represents a character identified by an octal number
 * 
 * @author Marco Marchiò <marco.mm89@gmail.com>
 * 
 * @link http://php.net/manual/en/regexp.reference.escape.php
 */
class OctalChar extends AbstractChar
{
    /**
     * Flag that identifies if the pattern can be added to character classes
     * 
     * @var bool
     */
    protected $_canBeAddedToCharClass = true;

    /**
     * Flag that identifies if the pattern can be added to character class ranges
     * 
     * @var bool
     */
    protected $_canBeAddedToCharClassRange = true;

    /**
     * Sets the character code. It can be 0, 1 or 2 digits that represents
     * the octal number that identifies the character
     * 
     * @param string $char Character to match
     * 
     * @return OctalChar
     * 
     * @throws \REBuilder\Exception\Generic
     * 
     * @link http://php.net/manual/en/regexp.reference.escape.php
     */
    public function setChar ($char)
    {
        if (!preg_match("#^(?:0[0-7]{1,2}|[0-7]{2,3})$#", $char)) {
            throw new \REBuilder\Exception\Generic(
                "Invalid octal character '$char'"
            );
        }
        return parent::setChar($char);
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
        if ($this->getChar() === null || $this->getChar() === "") {
            throw new \REBuilder\Exception\Generic(
                "Empty octal character"
            );
        }
        return "\\" . $this->getChar();
    }
}