<?php
/**
 * Invalid modifier exception
 * 
 * @author Marco Marchiò
 */
class REBuilder_Exception_InvalidModifier extends REBuilder_Exception_Generic
{
	/**
	 * Constructor
	 * 
	 * @param string $msg Exception message
	 */
	public function __construct ($msg = "Invalid regex modifier")
	{
		parent::__construct($msg);
	}
}