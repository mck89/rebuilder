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
 * Represents the hexadecimal character identifier \x
 * 
 * @author Marco Marchiò <marco.mm89@gmail.com>
 * 
 * @link http://php.net/manual/en/regexp.reference.escape.php
 */
class HexChar extends AbstractChar
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
     * Sets the character code . It can be 0, 1 or 2 hexadecimal digits
     * that represent a character code
     * 
     * @param string $char Character to match
     * 
     * @return HexChar
     * 
     * @throws \REBuilder\Exception\Generic
     * 
     * @link http://php.net/manual/en/regexp.reference.escape.php
     */
    public function setChar ($char)
    {
        if ($char !== "" &&
            !\REBuilder\Parser\Rules::validateHexString($char)) {
            throw new \REBuilder\Exception\Generic(
                "Invalid hexadecimal sequence '$char'"
            );
        } elseif (strlen($char) > 2) {
            throw new \REBuilder\Exception\Generic(
                "Hexadecimal character can match a maximum of 2 digits"
            );
        }
        return parent::setChar($char);
    }

    /**
     * Returns the string representation of the class
     * 
     * @return string
     */
    public function render ()
    {
        return "\x" . $this->getChar() . $this->_renderRepetition();
    }
}
