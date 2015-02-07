<?php
/**
 * This class represents a token emitted from the tokenizer
 * 
 * @author Marco Marchiò
 */
class REBuilder_Parser_Token
{
	//Token type constants
	/**
	 * Regex start delimiter token
	 */
	Const TYPE_REGEX_START_DELIMITER = 1;
	
	/**
	 * Regex start delimiter token
	 */
	Const TYPE_REGEX_END_DELIMITER = 2;
	
	/**
	 * Regex modifiers token
	 */
	Const TYPE_REGEX_MODIFIERS = 3;
	
	/**
	 * Simple character
	 */
	Const TYPE_CHAR = 4;
	
	/**
	 * Non-printing character identifier
	 */
	Const TYPE_NON_PRINTING_CHAR = 5;
	
	/**
	 * Generic character type identifier
	 */
	Const TYPE_GENERIC_CHAR_TYPE = 6;
	
	/**
	 * Simple assertion identifier
	 */
	Const TYPE_SIMPLE_ASSERTION = 7;
	
	/**
	 * Control character identifier
	 */
	Const TYPE_CONTROL_CHAR = 8;
	
	/**
	 * Token's type
	 * 
	 * @var int
	 */
	protected $_type;
	
	/**
	 * Token's subject
	 * 
	 * @var string
	 */
	protected $_subject;
	
	/**
	 * Constructor
	 * 
	 * @param int $type       Token's type. It can be one of the current class
	 *                        type constants
	 * @param string $subject Token's subject
	 */
	public function __construct ($type, $subject)
	{
		$this->_type = $type;
		$this->_subject = $subject;
	}
	
	/**
	 * Returns the token type
	 * 
	 * @return int
	 */
	public function getType ()
	{
		return $this->_type;
	}
	
	/**
	 * Returns the token subject
	 * 
	 * @return string
	 */
	public function getSubject()
	{
		return $this->_subject;
	}
}