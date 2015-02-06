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
	 * Returns the string representation of the class
	 * 
	 * @return string
	 * @abstract
	 */
	abstract public function render ();
	
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