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
 * Represents non printing characters: \a, \e, \f, \n, \r, \t
 * 
 * @author Marco Marchiò <marco.mm89@gmail.com>
 * 
 * @link http://php.net/manual/en/regexp.reference.escape.php
 */
class NonPrintingChar extends AbstractIdentifier
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
     * Sets the identifier. It can be one of the following:
     * "a", "e", "f", "n", "r", "t".
     * 
     * @param string $identifier Identifier to match
     * 
     * @return NonPrintingChar
     * 
     * @throws \REBuilder\Exception\Generic
     * 
     * @link http://php.net/manual/en/regexp.reference.escape.php
     */
    public function setIdentifier ($identifier)
    {
        if (!\REBuilder\Parser\Rules::validateNonPrintingChar($identifier)) {
            throw new \REBuilder\Exception\Generic(
                "'$identifier' is not a valid non-printing character identifier"
            );
        }
        return parent::setIdentifier($identifier);
    }
}