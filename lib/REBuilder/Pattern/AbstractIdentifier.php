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
 * Abstract class for patterns that represents an identifier
 * 
 * @author Marco Marchiò <marco.mm89@gmail.com>
 * 
 * @abstract
 */
abstract class AbstractIdentifier extends AbstractPattern
{
    /**
     * Identifier to match
     * 
     * @var string
     */
    protected $_identifier;

    /**
     * Constructor
     * 
     * @param string $identifier Identifier to match
     */
    public function __construct ($identifier = null)
    {
        if ($identifier !== null) {
            $this->setIdentifier($identifier);
        }
    }

    /**
     * Sets the identifier.
     * 
     * @param string $identifier Identifier to match
     * 
     * @return AbstractIdentifier
     */
    public function setIdentifier ($identifier)
    {
        $this->_identifier = $identifier;
        return $this;
    }

    /**
     * Returns the identifier to match
     * 
     * @return string
     */
    public function getIdentifier ()
    {
        return $this->_identifier;
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
        if ($this->getIdentifier() === null) {
            throw new \REBuilder\Exception\Generic(
                "No identifier has been set"
            );
        }
        return "\\" . $this->getIdentifier() . $this->_renderRepetition();
    }
}