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
 * Represents the "*" repetition that matches the subject zero or more times
 * 
 * @author Marco Marchiò <marco.mm89@gmail.com>
 * 
 * @link http://php.net/manual/en/regexp.reference.repetition.php
 */
class ZeroOrMore extends AbstractRepetition
{
    /**
     * Minimum repetition
     * 
     * @var int
     */
    protected $_min = 0;

    /**
     * Flag that indicates if the repetition supports the lazy
     * 
     * @var bool
     */
    protected $_supportsLazy = true;

    /**
     * Constructor
     * 
     * @param bool $lazy True if the repetition must be lazy
     */
    public function __construct ($lazy = false)
    {
        $this->setLazy($lazy);
    }

    /**
     * Sets the lazy flag
     * 
     * @param bool $lazy True if the repetition must be lazy
     * 
     * @return ZeroOrMore
     */
    public function setLazy ($lazy)
    {
        $this->_lazy = $lazy;
        return $this;
    }

    /**
     * Returns the string representation of the class
     * 
     * @return string
     */
    public function render ()
    {
        return "*" . ($this->getLazy() ? "?" : "");
    }
}