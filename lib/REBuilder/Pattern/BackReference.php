<?php
/**
 * Represents a back reference
 * 
 * @author Marco Marchiò
 * @abstract
 * @link http://php.net/manual/en/regexp.reference.back-references.php
 */
class REBuilder_Pattern_BackReference extends REBuilder_Pattern_Abstract
{	
	/**
	 * Reference
	 * 
	 * @var string
	 */
	protected $_reference = "";
	
	/**
	 * Constructor
	 * 
	 * @param string $reference Reference
	 */
	public function __construct ($reference = "")
	{
		$this->setReference($reference);
	}
	
	/**
	 * Sets the reference. It can be the index of a subpattern or its name
	 * 
	 * @param string $reference Reference
	 * @return REBuilder_Pattern_BackReference
	 */
	public function setReference ($reference)
	{
		$this->_reference = $reference;
		return $this;
	}
	
	/**
	 * Returns the reference
	 * 
	 * @return string
	 */
	public function getReference ()
	{
		return $this->_reference;
	}
	
	/**
	 * Returns the string representation of the class
	 * 
	 * @return string
	 */
	public function render ()
	{
        if ($this->getReference() === "") {
			throw new REBuilder_Exception_Generic(
				"Empty back reference"
			);
		}
		return "\g{" . $this->getReference() . "}" . $this->_renderRepetition();
	}
}