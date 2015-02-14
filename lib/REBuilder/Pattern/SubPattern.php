<?php
/**
 * Represent a capturing or non capturing subpattern
 * 
 * @author Marco Marchiò
 * @link http://php.net/manual/en/regexp.reference.subpatterns.php
 */
class REBuilder_Pattern_SubPattern extends REBuilder_Pattern_Container
{
	/**
	 * Identifies if the subpattern is a capturing subpattern or a non capturing
	 * subpattern
	 * 
	 * @var bool
	 */
	protected $_capture = true;
	
	/**
	 * Subpattern name
	 * 
	 * @var string
	 */
	protected $_name = "";
	
	/**
	 * Subpattern mopdifiers
	 * 
	 * @var string
	 */
	protected $_modifiers = "";
	
	/**
	 * Constructor
	 * 
	 * @param bool   $capture   False to make a non capturing subpattern
	 * @param string $name      Subpattern name
	 * @param string $modifiers Subpattern modifiers
	 */
	public function __construct ($capture = true, $name = null, $modifiers = null)
	{
		$this->setCapture($capture);
		if ($name !== null) {
			$this->setName($name);
		}
		if ($modifiers !== null) {
			$this->setModifiers($modifiers);
		}
	}
	
	/**
	 * Set the subpattern capturing mode. True to make it capturing, false to
	 * make it non capturing
	 * 
	 * @param bool $capture Subpattern capturing mode
	 * @return REBuilder_Pattern_SubPattern
	 */
	public function setCapture ($capture)
	{
		$this->_capture = (bool) $capture;
		return $this;
	}
	
	/**
	 * Returns true if it is a capturing subpattern, otherwise false
	 * 
	 * @return bool
	 */
	public function getCapture ()
	{
		return $this->_capture;
	}
	
	/**
	 * Sets the subpattern name. The name will be used only when subpattern
	 * is a capturing subpattern
	 * 
	 * @param string $name Subpattern name
	 * @return REBuilder_Pattern_SubPattern
	 * @throws REBuilder_Exception_Generic
	 */
	public function setName ($name)
	{
		if (!preg_match("#^\w*$#", $name)) {
			throw new REBuilder_Exception_Generic(
				"Invalid subpattern name '$name'. Subpattern names can contain " .
				"only letters, numbers and underscore"
			);
		}
		$this->_name = (string) $name;
		return $this;
	}
	
	/**
	 * Returns subpattern name
	 * 
	 * @return string
	 */
	public function getName ()
	{
		return $this->_name;
	}
	
	/**
	 * Sets subpattern modifiers. Modifiers work only in non capturing
	 * subpatterns
	 * 
	 * @param string $modifiers Subpattern modifiers
	 * @return REBuilder_Pattern_SubPattern
	 * @throws REBuilder_Exception_Generic
	 */
	public function setModifiers ($modifiers)
	{
		if (!REBuilder_Parser_Rules::validateModifiers($modifiers, $wrongModifier)) {
			throw new REBuilder_Exception_InvalidModifier(
				"Invalid modifier '$wrongModifier'"
			);
		}
		$this->_modifiers = $modifiers;
		return $this;
	}
	
	/**
	 * Returns subpattern modifiers
	 * 
	 * @return string
	 */
	public function getModifiers ()
	{
		return $this->_modifiers;
	}
	
	/**
	 * Returns the string representation of the class
	 * 
	 * @return string
	 */
	public function render ()
	{
		$ret = "(";
		if (!$this->getCapture()) {
			$ret .= "?";
			$ret .= $this->getModifiers();
			$ret .= ":";
		} elseif ($this->getName() !== "") {
			$ret .= "?<" . $this->getName() . ">";
		}
		$ret .= $this->renderChildren();
		$ret .= ")";
		$ret .= $this->_renderRepetition();
		return $ret;
	}
}