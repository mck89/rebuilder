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
	 * Token's pattern
	 * 
	 * @var string
	 */
	protected $_pattern;
	
	/**
	 * Constructor
	 * 
	 * @param int $type       Token's type. It can be one of the current class
	 *                        type constants
	 * @param string $pattern Token's pattern
	 */
	public function __construct ($type, $pattern)
	{
		$this->_type = $type;
		$this->_pattern = $pattern;
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
	 * Returns the token pattern
	 * 
	 * @return string
	 */
	public function getPattern ()
	{
		return $this->_pattern;
	}
}