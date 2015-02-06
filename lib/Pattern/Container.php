<?php
/**
 * Abstract class for pattern containers
 * 
 * @author Marco Marchiò
 * @abstract
 */
abstract class REBuilder_Pattern_Container extends REBuilder_Pattern_Abstract
{
	/**
	 * Class children
	 *
	 * @var array
	 */
	protected $_children = array();
	
	/**
	 * Adds a child to the class
	 * 
	 * @param REBuilder_Pattern_Abstract $child Child to add
	 * @return REBuilder_Pattern_Container
	 */
	public function addChild (REBuilder_Pattern_Abstract $child)
	{
		$child->setParentContainer($this);
		$this->_children[] = $child;
		return $this;
	}
	
	/**
	 * Removes a child from the class
	 * 
	 * @param REBuilder_Pattern_Abstract $child Child to remove
	 * @return REBuilder_Pattern_Container
	 */
	public function removeChild (REBuilder_Pattern_Abstract $child)
	{
		if ($this->hasChildren()) {
			$index = null;
			foreach ($this->_children as $k => $c) {
				if (spl_object_hash($c) === spl_object_hash($child)) {
					$index = $k;
					break;
				}
			}
			if ($index !== null) {
				array_splice($this->_children, $index, 1);
			}
		}
		return $this;
	}
	
	/**
	 * Returns true if the class has at least one child
	 * 
	 * @return bool
	 */
	public function hasChildren ()
	{
		return count($this->_children) !== 0;
	}
	
	/**
	 * Returns the string representation of class children
	 * 
	 * @return string
	 */
	public function renderChildren ()
	{
		$ret = "";
		if ($this->hasChildren()) {
			foreach ($this->_children as $child) {
				$ret .= $child->render();
			}
		}
		return $ret;
	}
}