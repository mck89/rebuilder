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
 * Represents a character or a group of characters that will be matched as they
 * are, like "a" and "bc" in /a.bc/
 * 
 * @author Marco Marchiò <marco.mm89@gmail.com>
 */
class Char extends AbstractChar
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
     * Returns the string representation of the class
     * 
     * @return string
     */
    public function render ()
    {
        $char = parent::render();
        $needsGroup = strlen($this->getChar()) > 1 && $this->getRepetition();
        return ($needsGroup ? "(?:$char)" : $char) . $this->_renderRepetition();
    }
}