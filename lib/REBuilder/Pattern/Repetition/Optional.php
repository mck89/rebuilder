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
 * Represents the "?" repetition that matches optionally matches a subject, in
 * other words it matches a subject one or zero times
 * 
 * @author Marco Marchiò <marco.mm89@gmail.com>
 * 
 * @link http://php.net/manual/en/regexp.reference.repetition.php
 */
class Optional extends AbstractRepetition
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
    protected $_max = 1;

    /**
     * Returns the string representation of the class
     * 
     * @return string
     */
    public function render ()
    {
        return "?";
    }
}