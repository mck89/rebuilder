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
 * Represents the dot that matches all characters
 * 
 * @author Marco Marchiò <marco.mm89@gmail.com>
 * 
 * @link http://php.net/manual/en/regexp.reference.dot.php
 */
class Dot extends AbstractPattern
{
    /**
     * Returns the string representation of the class
     * 
     * @return string
     */
    public function render ()
    {
        return "." . $this->_renderRepetition();
    }
}