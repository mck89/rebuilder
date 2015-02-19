<?php
/**
 * Represents an internal option (?i). This allows to set or subtract modifiers
 * at a certain point of a regex
 * 
 * @author Marco Marchiò
 * @abstract
 * @link http://php.net/manual/en/regexp.reference.internal-options.php
 */
class REBuilder_Pattern_InternalOption extends REBuilder_Pattern_Abstract
{	
	/**
	 * Internal option mopdifiers
	 * 
	 * @var string
	 */
	protected $_modifiers = "";
	
	/**
	 * Constructor
	 * 
	 * @param string $modifiers    Internal option modifiers
	 */
	public function __construct ($modifiers = null)
	{
		if ($modifiers !== null) {
			$this->setModifiers($modifiers);
		}
	}
	
	/**
	 * Sets internal option modifiers
	 * 
	 * @param string $modifiers Internal option modifiers
	 * @return REBuilder_Pattern_InternalOption
	 * @throws REBuilder_Exception_Generic
	 */
	public function setModifiers ($modifiers)
	{
		if (!REBuilder_Parser_Rules::validateModifiers(
				str_replace("-", "", $modifiers),
				$wrongModifier
			)) {
			throw new REBuilder_Exception_InvalidModifier(
				"Invalid modifier '$wrongModifier'"
			);
		}
		$this->_modifiers = $modifiers;
		return $this;
	}
	
	/**
	 * Returns internal option modifiers
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
	 * @param mixed $repetition Repetition. 
	 * @param mixed $max		Max repetition
	 * @throws REBuilder_Exception_Generic
	 */
	public function setRepetition ($repetition, $max = null)
	{
		throw new REBuilder_Exception_InvalidRepetition(
			"Simple assertions cannot handle repetitions"
		);
	}
	
	/**
	 * Returns the string representation of the class
	 * 
	 * @return string
	 */
	public function render ()
	{
		return "(?" . $this->getModifiers() . ")";
	}
}