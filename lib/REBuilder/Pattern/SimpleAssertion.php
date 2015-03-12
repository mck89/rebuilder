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
 * Represents simple assertions: \b, \B, \A, \Z, \z, \G, \Q, \E, \K
 * 
 * @author Marco Marchiò <marco.mm89@gmail.com>
 * 
 * @link http://php.net/manual/en/regexp.reference.escape.php
 */
class SimpleAssertion extends AbstractIdentifier
{
    /**
     * Flag that identifies if the pattern supports repetitions
     * 
     * @var bool
     */
    protected $_supportsRepetition = false;

    /**
     * Sets the identifier. It can be one of the following:
     * "b", "B", "A", "Z", "z", "G", "Q", "E", "K"
     * 
     * @param string $identifier Identifier to match
     * 
     * @return SimpleAssertion
     * 
     * @throws \REBuilder\Exception\Generic
     * 
     * @link http://php.net/manual/en/regexp.reference.escape.php
     */
    public function setIdentifier ($identifier)
    {
        if (!\REBuilder\Parser\Rules::validateSimpleAssertion($identifier)) {
            throw new \REBuilder\Exception\Generic(
                "'$identifier' is not a valid simple assertion type identifier"
            );
        }
        return parent::setIdentifier($identifier);
    }
}
