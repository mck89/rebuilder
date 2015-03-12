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
 * Abstract class for patterns that represent a character
 * 
 * @author Marco Marchiò <marco.mm89@gmail.com>
 * 
 * @abstract
 */
abstract class AbstractChar extends AbstractPattern
{
    /**
     * Character to match
     * 
     * @var string
     */
    protected $_char;

    /**
     * Constructor
     * 
     * @param string $char Character to match
     */
    public function __construct ($char = null)
    {
        if ($char !== null) {
            $this->setChar($char);
        }
    }

    /**
     * Sets the character to match.
     * 
     * @param string $char Character to match
     * 
     * @return AbstractChar
     */
    public function setChar ($char)
    {
        $this->_char = $char;
        return $this;
    }

    /**
     * Returns the character to match
     * 
     * @return string
     */
    public function getChar ()
    {
        return $this->_char;
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
                "No character has been set"
            );
        }
        if ($parentRegex = $this->getParentRegex()) {
            $ret = $parentRegex->quote($this->getChar());
        } else {
            $ret = preg_quote($this->getChar());
        }
        //Escape whitespaces is required to match spaces or newlines in extended
        //mode
        return preg_replace("/(\s)/", "\\\\$1", $ret);
    }
}
