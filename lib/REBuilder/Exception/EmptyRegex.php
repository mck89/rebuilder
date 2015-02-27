<?php
/**
 * Empty regex exception
 * 
 * @author Marco Marchiò
 */
class REBuilder_Exception_EmptyRegex extends REBuilder_Exception_Generic
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