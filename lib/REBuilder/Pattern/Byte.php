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
 * Represents the identifier \C that matches a single byte
 * 
 * @author Marco Marchiò <marco.mm89@gmail.com>
 * 
 * @link http://php.net/manual/en/regexp.reference.dot.php
 */
class Byte extends AbstractPattern
{
    /**
     * Returns the string representation of the class
     * 
     * @return string
     */
    public function render ()
    {
        return "\C" . $this->_renderRepetition();
    }
}