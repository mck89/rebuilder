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
 * Represents a recursive pattern
 * 
 * @author Marco Marchiò <marco.mm89@gmail.com>
 * 
 * @link http://php.net/manual/en/regexp.reference.recursive.php
 */
class RecursivePattern extends AbstractPattern
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
     * @return RecursivePattern
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
        $ref = $this->getReference();
        if ($ref === "") {
            throw new \REBuilder\Exception\Generic(
                "Empty reference"
            );
        }
        if (is_numeric($ref) || $ref === "R") {
            return "(?$ref)";
        } else {
            return "(?P>$ref)";
        }
    }
}