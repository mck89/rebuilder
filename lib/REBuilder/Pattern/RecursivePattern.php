<?php
/**
 * Represents a recursive pattern
 * 
 * @author Marco Marchiò
 * @abstract
 * @link http://php.net/manual/en/regexp.reference.recursive.php
 */
class REBuilder_Pattern_RecursivePattern extends REBuilder_Pattern_Abstract
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
        $ref = $this->getReference();
        if ($ref === "") {
			throw new REBuilder_Exception_Generic(
				"Empty reference"
			);
		}
        if (is_numeric($ref) || $ref === "R") {
            return "(?$ref)";
        } else {
            return "(?P>$ref)";
        }
	}
}