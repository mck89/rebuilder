<?php
/**
 * Represents an alternation identifier "|"
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
	 * Returns the string representation of the class
	 * 
	 * @return string
	 */
	public function render ()
	{
		return	"|";
	}
}