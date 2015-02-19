<?php
/**
 * Represent a capturing or non capturing subpattern.
 * 
 * @author Marco Marchiò
 * @link http://php.net/manual/en/regexp.reference.subpatterns.php
 */
class REBuilder_Pattern_SubPattern extends REBuilder_Pattern_AbstractContainer
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
	 * Subpattern group matches flag
	 * 
	 * @var bool
	 */
	protected $_groupMatches = false;
	
	/**
	 * Once only flag
	 * 
	 * @var bool
	 */
	protected $_onceOnly = false;
	
	/**
	 * Constructor
	 * 
	 * @param bool   $capture      False to make a non capturing subpattern
	 * @param string $name         Subpattern name
	 * @param string $modifiers    Subpattern modifiers
	 * @param string $groupMatches True to group matches so that any alternating
	 *							   child subpattern is stored in the same match
	 *							   number
	 * @param bool   $onceOnly     True to transform the subpattern in a once
	 *							   only subpattern
	 */
	public function __construct ($capture = true, $name = null,
								 $modifiers = null, $groupMatches = false,
								 $onceOnly = false)
	{
		$this->setCapture($capture);
		if ($name !== null) {
			$this->setName($name);
		}
		if ($modifiers !== null) {
			$this->setModifiers($modifiers);
		}
		$this->setGroupMatches($groupMatches);
		$this->setOnceOnly($onceOnly);
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
	 * Sets subpattern modifiers
	 * 
	 * @param string $modifiers Subpattern modifiers
	 * @return REBuilder_Pattern_SubPattern
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
	 * Returns subpattern modifiers
	 * 
	 * @return string
	 */
	public function getModifiers ()
	{
		return $this->_modifiers;
	}
	
	/**
	 * Set the subpattern group matches mode. If true alternating child
	 * subpatterns are stored in the same match number
	 * 
	 * @param bool $groupMatches Subpattern group matches mode
	 * @return REBuilder_Pattern_SubPattern
	 */
	public function setGroupMatches ($groupMatches)
	{
		$this->_groupMatches = (bool) $groupMatches;
		return $this;
	}
	
	/**
	 * Returns true if the group matches mode is enabled
	 * 
	 * @return bool
	 */
	public function getGroupMatches ()
	{
		return $this->_groupMatches;
	}
	
	/**
	 * Set the subpattern once only mode. If true the subpattern will be tested
	 * only one time and if it fails it won't be tested again, this makes a
	 * regex faster but it can cause it to fail is some cases
	 * 
	 * @param bool $onceOnly Subpattern once only mode
	 * @return REBuilder_Pattern_SubPattern
	 * @link http://php.net/manual/en/regexp.reference.onlyonce.php
	 */
	public function setOnceOnly ($onceOnly)
	{
		$this->_onceOnly = (bool) $onceOnly;
		return $this;
	}
	
	/**
	 * Returns true if the once only mode is enabled
	 * 
	 * @return bool
	 */
	public function getOnceOnly ()
	{
		return $this->_onceOnly;
	}
	
	/**
	 * Returns the string representation of the class
	 * 
	 * @return string
	 */
	public function render ()
	{
		$openBrackets = 0;
		$ret = "";
		if ($this->getCapture()) {
			$ret .= "(";
			if ($this->getName() !== "") {
				$ret .= "?<" . $this->getName() . ">";
			}
			$openBrackets++;
		}
		if ($this->getGroupMatches()) {
			$ret .= "(?|";
			$openBrackets++;
		}
		if ($this->getOnceOnly()) {
			$ret .= "(?>";
			$openBrackets++;
		}
		if ($this->getModifiers() || (!$this->getCapture() && !$openBrackets)) {
			$ret .= "(?";
			$ret .= $this->getModifiers();
			$ret .= ":";
			$openBrackets++;
		}
		$ret .= $this->renderChildren();
		$ret .= str_repeat(")", $openBrackets);
		$ret .= $this->_renderRepetition();
		return $ret;
	}
}