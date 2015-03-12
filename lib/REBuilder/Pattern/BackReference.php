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
 * Represents a back reference
 * 
 * @author Marco Marchiò <marco.mm89@gmail.com>
 * 
 * @link http://php.net/manual/en/regexp.reference.back-references.php
 */
class BackReference extends AbstractPattern
{
    /**
     * Reference
     * 
     * @var string
     */
    protected $_reference = "";

    /**
     * Constructor
     * 
     * @param string $reference Reference
     */
    public function __construct ($reference = "")
    {
        $this->setReference($reference);
    }

    /**
     * Sets the reference. It can be the index of a subpattern or its name
     * 
     * @param string $reference Reference
     * 
     * @return BackReference
     */
    public function setReference ($reference)
    {
        $this->_reference = $reference;
        return $this;
    }

    /**
     * Returns the reference
     * 
     * @return string
     */
    public function getReference ()
    {
        return $this->_reference;
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
        if ($this->getReference() === "") {
            throw new \REBuilder\Exception\Generic(
                "Empty back reference"
            );
        }
        return "\g{" . $this->getReference() . "}" . $this->_renderRepetition();
    }
}