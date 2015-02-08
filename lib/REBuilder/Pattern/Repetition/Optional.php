<?php
/**
 * Represents the "?" repetition that matches optionally matches a subject, in
 * other words it matches a subject one or zero times
 * 
 * @author Marco Marchiò
 * @link http://php.net/manual/en/regexp.reference.repetition.php
 */
class REBuilder_Pattern_Repetition_Optional extends REBuilder_Pattern_Repetition_Abstract
{
	/**
	 * Minimum repetition
	 * 
	 * @var int
	 */
	protected $_min = 0;
	
	/**
	 * Maximum repetition
	 * 
	 * @var int
	 */
	protected $_max = 1;
	
	/**
	 * Returns the string representation of the class
	 * 
	 * @return string
	 */
	public function render () {
		return "?";
	}
}