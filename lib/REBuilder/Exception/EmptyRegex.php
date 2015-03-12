<?php
/**
 * This file is part of the REBuilder package
 *
 * (c) Marco Marchiò <marco.mm89@gmail.com>
 *
 * For the full copyright and license information refer to the LICENSE file
 * distributed with this source code
 */

namespace REBuilder\Exception;

/**
 * Empty regex exception
 * 
 * @author Marco Marchiò <marco.mm89@gmail.com>
 */
class EmptyRegex extends Generic
{
    /**
     * Constructor
     * 
     * @param string $msg Exception message
     */
    public function __construct ($msg = "The regex is empty")
    {
        parent::__construct($msg);
    }
}