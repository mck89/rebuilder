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
 * Represents the "{,}" repetition that matches the subject a variable number
 * of times between a minimum and a maximum value
 * 
 * @author Marco Marchiò <marco.mm89@gmail.com>
 * 
 * @link http://php.net/manual/en/regexp.reference.repetition.php
 */
class Range extends AbstractRepetition
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
     * @param int      $min Minimum number of repetitions
     * @param int|null $max Maximum number of repetitions. If null is passed
     *                 then no maximum limit will be used
     */
    public function __construct ($min = 1, $max = 1)
    {
        $this->setMin($min);
        $this->setMax($max);
    }

    /**
     * Sets the minimum number of repetitions
     * 
     * @param int|null $min Minimum number of repetitions
     * 
     * @return Range
     */
    public function setMin ($min)
    {
        $this->_min = $min;
        return $this;
    }

    /**
     * Sets the maximum number of repetitions
     * 
     * @param int|null $max Maximum number of repetitions. If null is passed
     *                      then no maximum limit will be used
     * 
     * @return Range
     */
    public function setMax ($max)
    {
        $this->_max = $max;
        return $this;
    }

    /**
     * Returns the string representation of the class
     * 
     * @return string
     */
    public function render ()
    {
        return "{" . $this->getMin() . "," .
                ($this->getMax() === null ? "" : $this->getMax())
                . "}";
    }
}