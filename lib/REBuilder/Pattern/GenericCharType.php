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
 * Represents generic character types: \d, \D, \h, \H, \s, \S, \v, \V, \w, \W
 * 
 * @author Marco Marchiò <marco.mm89@gmail.com>
 * 
 * @link http://php.net/manual/en/regexp.reference.escape.php
 */
class GenericCharType extends AbstractIdentifier
{
    /**
     * Flag that identifies if the pattern can be added to character classes
     * 
     * @var bool
     */
    protected $_canBeAddedToCharClass = true;
    
    /**
     * Sets the identifier. It can be one of the following:
     * "d", "D", "h", "H", "s", "S", "v", "V", "w", "W"
     * 
     * @param string $identifier Identifier to match
     * 
     * @return GenericCharType
     * 
     * @throws \REBuilder\Exception\Generic
     * 
     * @link http://php.net/manual/en/regexp.reference.escape.php
     */
    public function setIdentifier ($identifier)
    {
        if (!\REBuilder\Parser\Rules::validateGenericCharType($identifier)) {
            throw new \REBuilder\Exception\Generic(
                "'$identifier' is not a valid generic character type identifier"
            );
        }
        return parent::setIdentifier($identifier);
    }
}