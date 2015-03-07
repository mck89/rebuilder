<?php
/**
 * Represents a group of alternations. This container can accept only
 * alternations
 * 
 * @author Marco Marchiò
 * @link http://php.net/manual/en/regexp.reference.alternation.php
 * 
 * @method REBuilder_Pattern_Alternation addAlternation()
 *         addAlternation()
 *         Adds a new REBuilder_Pattern_Alternation class instance to this container
 *         @see REBuilder_Pattern_Alternation::__construct
 * 
 * @method REBuilder_Pattern_Alternation addAlternationAndContinue()
 *         addAlternationAndContinue()
 *         Same as addAlternation but it returns the current container
 *         @see REBuilder_Pattern_Alternation::__construct
 */
class REBuilder_Pattern_AlternationGroup extends REBuilder_Pattern_AbstractContainer
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
     * @param REBuilder_Pattern_Abstract $child Child to add
     * @param int                        $index Index
     * @return REBuilder_Pattern_CharClass
     * @throw REBuilder_Exception_Generic
     */
    public function addChildAt (REBuilder_Pattern_Abstract $child, $index = null)
    {
        if (!$child instanceof REBuilder_Pattern_Alternation) {
            throw new REBuilder_Exception_Generic(
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