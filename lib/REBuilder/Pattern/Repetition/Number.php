<?php
/**
 * This file is part of the REBuilder package
 *
 * (c) Marco Marchiò <marco.mm89@gmail.com>
 *
 * For the full copyright and license information refer to the LICENSE file
 * distributed with this source code
 */

namespace REBuilder\Pattern\Repetition;

/**
 * Represents the "{}" repetition that matches the subject exactly the given
 * number of times
 * 
 * @author Marco Marchiò <marco.mm89@gmail.com>
 * 
 * @link http://php.net/manual/en/regexp.reference.repetition.php
 */
class Number extends AbstractRepetition
{
    /**
     * Minimum repetition
     * 
     * @var int
     */
    protected $_min = 1;

    /**
     * Maximum repetition
     * 
     * @var int
     */
    protected $_max = 1;

    /**
     * Constructor
     * 
     * @param int $number Number of times the repetition must match
     */
    public function __construct ($number = 1)
    {
        $this->setNumber($number);
    }

    /**
     * Sets the number of times the repetition must match
     * 
     * @param int $number Number of times the repetition must match
     * 
     * @return Number
     */
    public function setNumber ($number)
    {
        $this->_min = $this->_max = $number;
        return $this;
    }

    /**
     * Gets the number of times the repetition must match
     * 
     * @return int
     */
    public function getNumber ()
    {
        return $this->_min;
    }

    /**
     * Returns the string representation of the class
     * 
     * @return string
     */
    public function render ()
    {
        return "{" . $this->getMin() . "}";
    }
}