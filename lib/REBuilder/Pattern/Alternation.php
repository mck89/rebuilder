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
     * Sets the parent
     * 
     * @param REBuilder_Pattern_AbstractContainer $parent Parent container
     * @return REBuilder_Pattern_Abstract
     */
    public function setParent (REBuilder_Pattern_AbstractContainer $parent)
    {
        if (!$parent instanceof REBuilder_Pattern_AlternationGroup) {
            throw new REBuilder_Exception_Generic(
                "Alternations can be added only to alternation groups"
            );
        }
        return parent::setParent($parent);
    }

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