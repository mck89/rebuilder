<?php
/**
 * Represents a character or a group of characters that will be matched as they
 * are, like "a" and "bc" in /a.bc/
 * 
 * @author Marco Marchiò
 * @abstract
 */
class REBuilder_Pattern_Simple extends REBuilder_Pattern_Abstract
{
	/**
	 * Subject to match
	 * 
	 * @var string
	 */
	protected $_subject;
	
	/**
	 * Constructor
	 * 
	 * @param string $subject Subject to match
	 */
	public function __construct ($subject)
	{
		$this->setSubject($subject);
	}
	
	/**
	 * Sets the subject. It can be one ore more characters.
	 * 
	 * @param string $subject Subject to match
	 * @return REBuilder_Pattern_Abstract
	 */
	public function setSubject ($subject)
	{
		$this->_subject = $subject;
		return $this;
	}
	
	/**
	 * Returns the subject to match
	 * 
	 * @return string
	 */
	public function getSubject ()
	{
		return $this->_subject;
	}
	
	/**
	 * Returns the string representation of the class
	 * 
	 * @return string
	 */
	public function render ()
	{
		$multiChar = strlen($this->_subject) > 1;
		$subject = $this->getParentRegex()->quote($this->_subject);
		//@TODO add repetition. Use non matching group only if repetition is set
		return $multiChar ? "(?:$subject)" : $subject;
	}
}