<?php
/**
 * Represents generic character types: \d, \D, \h, \H, \s, \S, \v, \V, \w, \W
 * 
 * @author Marco Marchiò
 * @abstract
 * @link http://php.net/manual/en/regexp.reference.escape.php
 */
class REBuilder_Pattern_GenericCharType extends REBuilder_Pattern_AbstractIdentifier
{
    /**
     * Sets the identifier. It can be one of the following:
     * "d", "D", "h", "H", "s", "S", "v", "V", "w", "W"
     * 
     * @param string $identifier Identifier to match
     * @return REBuilder_Pattern_GenericCharType
     * @throws REBuilder_Exception_Generic
     * @link http://php.net/manual/en/regexp.reference.escape.php
     */
    public function setIdentifier ($identifier)
    {
        if (!REBuilder_Parser_Rules::validateGenericCharType($identifier)) {
            throw new REBuilder_Exception_Generic(
                "'$identifier' is not a valid generic character type identifier"
            );
        }
        return parent::setIdentifier($identifier);
    }
}