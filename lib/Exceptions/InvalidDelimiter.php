<?php
/**
 * Invalid delimiter exception
 * 
 * @author Marco Marchiò
 */
class REBuilder_Exception_InvalidDelimiter extends REBuilder_Exception_Generic
{
	/**
	 * Constructor
	 * 
	 * @param string $msg Exception message
	 */
	public function __construct ($msg = "Invalid regex delimiter")
	{
		parent::__construct($msg);
	}
}