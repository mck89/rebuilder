<?php
/**
 * Abstract class for patterns
 * 
 * @author Marco Marchiò
 * @abstract
 */
abstract class REBuilder_Pattern_Abstract
{
	/**
	 * Parent container
	 * 
	 * @var REBuilder_Pattern_Container 
	 */
	protected $_parentContainer;
	
	/**
	 * Repetition
	 * 
	 * @var REBuilder_Pattern_Repetition_Abstract 
	 */
	protected $_repetition;
	
	/**
	 * Sets the parent container
	 * 
	 * @param REBuilder_Pattern_Container $parent Parent container
	 * @return REBuilder_Pattern_Abstract
	 */
	public function setParentContainer (REBuilder_Pattern_Container $parent)
	{
		//Before proceed remove it from the previous parent container
		if ($currentParent = $this->getParentContainer()) {
			$currentParent->removeChild($this);
		}
		$this->_parentContainer = $parent;
		return $this;
	}
	
	/**
	 * Returns the parent container
	 * 
	 * @return REBuilder_Pattern_Container
	 */
	public function getParentContainer ()
	{
		return $this->_parentContainer;
	}
	/**
	 * Returns the parent regex
	 * 
	 * @return REBuilder_Pattern_Regex
	 */
	public function getParentRegex ()
	{
		$parent = $this->getParentContainer();
		while ($parent && !$parent instanceof REBuilder_Pattern_Regex) {
			$parent = $parent->getParentContainer();
		}
		return $parent;
	}
	
	/**
	 * Sets the repetition
	 * 
	 * @param mixed $repetition Repetition. It can be an instance of any class
	 *							that extends REBuilder_Pattern_Repetition_Abstract.
	 *							If "*", "+" or "?" are passed, the corresponding
	 *							repetitions will be used. If it's a number
	 *							REBuilder_Pattern_Repetition_Number or
	 *							REBuilder_Pattern_Repetition_Range are used
	 *							depending on the second argument
	 * @param mixed $max		For "*" and "+" a boolean value is accepted and
	 *							it's used to set the lazy flag. If a number is
	 *							passed as first argument and this argument is
	 *							omitted them a REBuilder_Pattern_Repetition_Number
	 *							is used, otherwise if this argument is null or
	 *							a number a REBuilder_Pattern_Repetition_Range
	 *							will be used
	 * @return REBuilder_Pattern_Abstract
	 */
	public function setRepetition ($repetition, $max = null)
	{
		if (!$repetition instanceof REBuilder_Pattern_Repetition_Abstract) {
			if ($repetition === "*") {
				$repetition = REBuilder_Pattern_Repetition_ZeroOrMore(
					is_bool($max) ? $max : false
				);
			} elseif ($repetition === "+") {
				$repetition = REBuilder_Pattern_Repetition_OneOrMore(
					is_bool($max) ? $max : false
				);
			} elseif ($repetition === "?") {
				$repetition = REBuilder_Pattern_Repetition_Optional();
			} elseif (is_numeric($repetition) &&
					  ($max === null || is_numeric($max))) {
				if (func_num_args() === 1) {
					$repetition = REBuilder_Pattern_Repetition_Number($repetition);
				} else {
					$repetition = REBuilder_Pattern_Repetition_Range($repetition, $max);
				}
			} else {
				throw new REBuilder_Exception_InvalidRepetition(
					"Invalid repetition '$repetition'"
				);
			}
		}
		$this->_repetition = $repetition;
		return $this;
	}
	
	/**
	 * Returns the repetition or null if has not been set
	 * 
	 * @return REBuilder_Pattern_Repetition_Abstract
	 */
	public function getRepetition ()
	{
		return $this->_repetition;
	}
	
	/**
	 * Returns the string representation of the class
	 * 
	 * @return string
	 * @abstract
	 */
	abstract public function render ();
	
	/**
	 * Returns the string representation of the class repetition
	 * 
	 * @return string
	 */
	protected function _renderRepetition ()
	{
		return $this->_repetition ? $this->_repetition->render() : "";
	}
	
	/**
	 * Returns the string representation of the class
	 * 
	 * @return string
	 */
	public function __toString ()
	{
		return $this->render();
	}
}