<?php
/**
 * Represents the "*" repetition that matches the subject one or more times
 * 
 * @author Marco Marchiò
 * @link http://php.net/manual/en/regexp.reference.repetition.php
 */
class REBuilder_Pattern_Repetition_OneOrMore extends REBuilder_Pattern_Repetition_Abstract
{
	/**
	 * Minimum repetition
	 * 
	 * @var int
	 */
	protected $_min = 1;
	
	/**
	 * Flag that indicates if the repetition supports the lazy
	 * 
	 * @var bool
	 */
	protected $_supportsLazy = true;
	
	/**
	 * Constructor
	 * 
	 * @param bool $lazy True if the repetition must be lazy
	 */
	public function __construct ($lazy = false)
	{
		$this->setLazy($lazy);
	}
	
	/**
	 * Sets the lazy flag
	 * 
	 * @param bool $lazy True if the repetition must be lazy
	 */
	public function setLazy ($lazy)
	{
		$this->_lazy = $lazy;
		return $this;
	}
	
	/**
	 * Returns the string representation of the class
	 * 
	 * @return string
	 */
	public function render () {
		return "+" . ($this->_lazy ? "?" : "");
	}
}