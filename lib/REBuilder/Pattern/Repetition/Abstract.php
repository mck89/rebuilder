<?php
/**
 * Abstract class for repetitions
 * 
 * @author Marco Marchiò
 * @abstract
 * @link http://php.net/manual/en/regexp.reference.repetition.php
 */
abstract class REBuilder_Pattern_Repetition_Abstract
{
    /**
     * Minimum repetition
     * 
     * @var int
     */
    protected $_min = 0;

    /**
     * Maximum repetition
     * 
     * @var int
     */
    protected $_max = null;

    /**
     * Flag that indicates if the repetition supports the lazy
     * 
     * @var bool
     */
    protected $_supportsLazy = false;

    /**
     * Lazy flag
     * 
     * @var bool
     */
    protected $_lazy = false;

    /**
     * Returns the minimum repetition
     * 
     * @return int
     */
    public function getMin() {
        return $this->_min;
    }

    /**
     * Returns the maximum repetition. It can be null if there is the repetition
     * means that it matches up to infinite characters
     * 
     * @return int|null
     */
    public function getMax() {
        return $this->_max;
    }

    /**
     * Returns the lazy flag
     * 
     * @return bool
     */
    public function getLazy() {
        return $this->_lazy;
    }

    /**
     * Check if the repetition supports the lazy flag
     * 
     * @return bool
     */
    public function supportsLazy() {
        return $this->_supportsLazy;
    }

    /**
     * Returns the string representation of the class
     * 
     * @return string
     * @abstract
     */
    abstract public function render ();

    /**
     * Returns the string representation of the class
     * 
     * @return string
     */
    public function __toString ()
    {
        return $this->render();
    }
}