<?php
/**
 * Represents an alternation. This container can be added only to alternation
 * groups
 * 
 * @author Marco Marchiò
 * @link http://php.net/manual/en/regexp.reference.alternation.php
 */
class REBuilder_Pattern_Alternation extends REBuilder_Pattern_AbstractContainer
{
    /**
     * Flag that identifies if the pattern supports repetitions
     * 
     * @var bool
     */
    protected $_supportsRepetition = false;
    
    /**
     * If this property is not empty the current class can be added only
     * to containers of the given instance
     * 
     * @var string
     */
    protected $_limitParent = "REBuilder_Pattern_AlternationGroup";

    /**
     * Returns the string representation of the class
     * 
     * @return string
     */
    public function render ()
    {
        return $this->renderChildren();
    }
}