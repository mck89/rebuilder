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
 * Represents a group of alternations. This container can accept only
 * alternations
 * 
 * @author Marco Marchiò <marco.mm89@gmail.com>
 * 
 * @link http://php.net/manual/en/regexp.reference.alternation.php
 * 
 * @method Alternation addAlternation()
 *         addAlternation()
 *         Adds a new alternation
 *         @see Alternation::__construct
 * 
 * @method Alternation addAlternationAndContinue()
 *         addAlternationAndContinue()
 *         Same as addAlternation but it returns the current container
 *         @see Alternation::__construct
 */
class AlternationGroup extends AbstractContainer
{
    /**
     * Flag that indicates if the container supports anchors
     *
     * @var bool
     */
    protected $_supportsAnchors = false;
    
    /**
     * Adds a child to the class at the given index
     * 
     * @param AbstractPattern $child Child to add
     * 
     * @param int             $index Index
     * 
     * @return AlternationGroup
     * 
     * @throws \REBuilder\Exception\Generic
     */
    public function addChildAt (AbstractPattern $child, $index = null)
    {
        if (!$child instanceof Alternation) {
            throw new \REBuilder\Exception\Generic(
                "Alternation groups can contain only alternations"
            );
        }
        return parent::addChildAt($child, $index);
    }

    /**
     * Returns the string representation of the class
     * 
     * @return string
     */
    public function render ()
    {
        //Render as a non capturing subpattern so that it can handle repetitions
        //and it can be added into a container with other children
        return "(?:" .
                implode("|", $this->getChildren()) .
                ")" . $this->_renderRepetition();
    }
}