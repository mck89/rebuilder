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
 * Represents the control character identifier \c
 * 
 * @author Marco Marchiò <marco.mm89@gmail.com>
 * 
 * @link http://php.net/manual/en/regexp.reference.escape.php
 */
class ControlChar extends AbstractChar
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
     * Sets the character to match. It can be any character
     * 
     * @param string $char Character to match
     * 
     * @return ControlChar
     * 
     * @throws \REBuilder\Exception\Generic
     * 
     * @link http://php.net/manual/en/regexp.reference.escape.php
     */
    public function setChar ($char)
    {
        $char = "$char";
        if (strlen($char) !== 1) {
            throw new \REBuilder\Exception\Generic(
                "Control character requires a single character"
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
        return "\c" . parent::render() . $this->_renderRepetition();
    }
}