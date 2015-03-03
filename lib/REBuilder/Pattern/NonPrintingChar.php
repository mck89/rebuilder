<?php
/**
 * Represents non printing characters: \a, \e, \f, \n, \r, \t
 * 
 * @author Marco Marchiò
 * @abstract
 * @link http://php.net/manual/en/regexp.reference.escape.php
 */
class REBuilder_Pattern_NonPrintingChar extends REBuilder_Pattern_AbstractIdentifier
{
    /**
     * Flag that identifies if the pattern can be added to character classes
     * 
     * @var bool
     */
    protected $_canBeAddedToCharClass = true;
    
    /**
     * Flag that identifies if the pattern can be added to character class ranges
     * 
     * @var bool
     */
    protected $_canBeAddedToCharClassRange = true;
    
    /**
     * Sets the identifier. It can be one of the following:
     * "a", "e", "f", "n", "r", "t".
     * 
     * @param string $identifier Identifier to match
     * @return REBuilder_Pattern_NonPrintingChar
     * @throws REBuilder_Exception_Generic
     * @link http://php.net/manual/en/regexp.reference.escape.php
     */
    public function setIdentifier ($identifier)
    {
        if (!REBuilder_Parser_Rules::validateNonPrintingChar($identifier)) {
            throw new REBuilder_Exception_Generic(
                "'$identifier' is not a valid non-printing character identifier"
            );
        }
        return parent::setIdentifier($identifier);
    }
}