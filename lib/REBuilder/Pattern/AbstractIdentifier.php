<?php
/**
 * Abstract class for patterns that represents an identifier
 * 
 * @author Marco Marchiò
 * @abstract
 */
abstract class REBuilder_Pattern_AbstractIdentifier extends REBuilder_Pattern_Abstract
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
     * @return REBuilder_Pattern_AbstractIdentifier
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
     */
    public function render ()
    {
        if ($this->_identifier === null) {
            throw new REBuilder_Exception_Generic(
                "No identifier has been set"
            );
        }
        return "\\" . $this->_identifier . $this->_renderRepetition();
    }
}