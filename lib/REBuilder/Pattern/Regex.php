<?php
/**
 * This class represents the main regex container and will contain the entire
 * regex structure
 * 
 * @author Marco Marchiò
 */
class REBuilder_Pattern_Regex extends REBuilder_Pattern_Container
{
	/**
	 * Start delimiter
	 * 
	 * @var string
	 */
	protected $_startDelimiter;
	
	/**
	 * End delimiter
	 * 
	 * @var string
	 */
	protected $_endDelimiter;
	
	/**
	 * Modifiers
	 * 
	 * @var string
	 */
	protected $_modifiers;
	
	/**
	 * Constructor
	 * 
	 * @param type $delimiter Regex delimiter
	 * @param type $modifiers Regex modifiers
	 */
	public function __construct ($delimiter = "/", $modifiers = "")
	{
		$this->setDelimiter($delimiter);
		$this->setModifiers($modifiers);
	}
	
	/**
	 * Sets the regex delimiter
	 * 
	 * @param string $delimiter Regex delimiter
	 * @return REBuilder_Pattern_Regex
	 * @throws REBuilder_Exception_InvalidDelimiter
	 */
	public function setDelimiter ($delimiter)
	{
		if (!REBuilder_Parser_Rules::validateDelimiter($delimiter)) {
			throw new REBuilder_Exception_InvalidDelimiter("Invalid delimiter '$delimiter'");
		}
		$this->_startDelimiter = $delimiter;
		$this->_endDelimiter = REBuilder_Parser_Rules::getEndDelimiter($delimiter);
		return $this;
	}
	
	/**
	 * Returns the regex start delimiter
	 * 
	 * @return string
	 */
	public function getStartDelimiter ()
	{
		return $this->_startDelimiter;
	}
	
	/**
	 * Returns the regex end delimiter
	 * 
	 * @return string
	 */
	public function getEndDelimiter ()
	{
		return $this->_endDelimiter;
	}
	
	/**
	 * Sets regex modifiers
	 * 
	 * @param string $modifiers Regex modifiers
	 * @return REBuilder_Pattern_Regex
	 * @throws REBuilder_Exception_InvalidModifier
	 */
	public function setModifiers ($modifiers)
	{
		if (!REBuilder_Parser_Rules::validateModifiers($modifiers, $wrongModifier)) {
			throw new REBuilder_Exception_InvalidModifier("Invalid modifier '$wrongModifier'");
		}
		$this->_modifiers = $modifiers;
		return $this;
	}
	
	/**
	 * Returns the regex modifiers
	 * 
	 * @return string
	 */
	public function getModifiers ()
	{
		return $this->_modifiers;
	}
	
	/**
	 * Sets the repetition. This class cannot handle repetitions so this
	 * method will throw an exception
	 * 
	 * @throws REBuilder_Exception_Generic
	 */
	public function setRepetition ()
	{
		throw new REBuilder_Exception_InvalidRepetition(
			"Regex container cannot handle repetitions"
		);
	}
	
	/**
	 * Quotes the given string using current configurations
	 * 
	 * @return string
	 */
	public function quote ($str)
	{
		return preg_quote($str, $this->_startDelimiter);
	}
	
	/**
	 * Returns the string representation of the class
	 * 
	 * @return string
	 */
	public function render ()
	{
		return	$this->_startDelimiter .
				$this->renderChildren() .
				$this->_endDelimiter .
				$this->_modifiers;
	}
}