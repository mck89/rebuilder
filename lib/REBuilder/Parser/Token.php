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
	 * Extended unicode sequence identifier
	 */
	Const TYPE_EXT_UNICODE_SEQUENCE = 9;
	
	/**
	 * Unicode character class identifier
	 */
	Const TYPE_UNICODE_CHAR_CLASS = 10;
	
	/**
	 * Hexadecimal character identifier
	 */
	Const TYPE_HEX_CHAR = 11;
	
	/**
	 * Repetition identifier
	 */
	Const TYPE_REPETITION = 12;
	
	/**
	 * Token's type
	 * 
	 * @var int
	 */
	protected $_type;
	
	/**
	 * Token's identifier
	 * 
	 * @var string
	 */
	protected $_identifier;
	
	/**
	 * Token's subject
	 * 
	 * @var string
	 */
	protected $_subject;
	
	/**
	 * Constructor
	 * 
	 * @param int $type          Token's type. It can be one of the current
	 *                           class ype constants
	 * @param string $identifier Token's identifier
	 * @param string $subject    Token's subject
	 */
	public function __construct ($type, $identifier, $subject = null)
	{
		if ($subject === null) {
			$subject = $identifier;
		}
		$this->_type = $type;
		$this->_identifier = $identifier;
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
	 * Returns the token identifier
	 * 
	 * @return string
	 */
	public function getIdentifier()
	{
		return $this->_identifier;
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