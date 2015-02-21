<?php
/**
 * Represents a comment
 * 
 * @author Marco Marchiò
 * @abstract
 * @link http://php.net/manual/en/regexp.reference.internal-options.php
 */
class REBuilder_Pattern_Comment extends REBuilder_Pattern_Abstract
{	
	/**
	 * Comment
	 * 
	 * @var string
	 */
	protected $_comment = "";
	
	/**
	 * Flag that identifies if the pattern supports repetitions
	 * 
	 * @var bool
	 */
	protected $_supportsRepetition = false;
	
	/**
	 * Constructor
	 * 
	 * @param string $comment Comment
	 */
	public function __construct ($comment = "")
	{
		$this->setComment($comment);
	}
	
	/**
	 * Sets the comment
	 * 
	 * @param string $comment Comment
	 * @return REBuilder_Pattern_Comment
	 */
	public function setComment ($comment)
	{
		$this->_comment = $comment;
		return $this;
	}
	
	/**
	 * Returns the comment
	 * 
	 * @return string
	 */
	public function getComment ()
	{
		return $this->_comment;
	}
	
	/**
	 * Returns the string representation of the class
	 * 
	 * @return string
	 */
	public function render ()
	{
		return "(?#" . str_replace(")", "", $this->getComment()) . ")";
	}
}