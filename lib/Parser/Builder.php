<?php
/**
 * This class is used to build the regex structure
 * 
 * @author Marco Marchiò
 */
class REBuilder_Parser_Builder
{
	/**
	 * Regex main container
	 * 
	 * @var REBuilder_Pattern_Regex 
	 */
	protected $_regexContainer;
	
	/**
	 * Containers stack
	 * 
	 * @var SplStack 
	 */
	protected $_containersStack;
	
	/**
	 * Current item
	 * 
	 * @var REBuilder_Pattern_Abstract 
	 */
	protected $_currentItem;
	
	/**
	 * Constructor
	 */
	public function __construct ()
	{
		$this->_containersStack = new SplStack();
	}
	
	/**
	 * This functions receive a token and handles it to build the structure
	 * 
	 * @param REBuilder_Parser_Token $token Token
	 */
	public function receiveToken (REBuilder_Parser_Token $token)
	{
		switch ($token->getType()) {
			//Regex start delimiter
			case REBuilder_Parser_Token::TYPE_REGEX_START_DELIMITER:
				//Create the regex container if it does not exists
				if (!$this->_regexContainer) {
					$this->_regexContainer = new REBuilder_Pattern_Regex;
					$this->_containersStack->push($this->_regexContainer);
				}
				//Set the delimiter
				$this->_regexContainer->setDelimiter(
					$token->getPattern()
				);
			break;
			//Regex end delimiter
			case REBuilder_Parser_Token::TYPE_REGEX_END_DELIMITER:
				//No need to handle this token
			break;
			//Regex modifiers
			case REBuilder_Parser_Token::TYPE_REGEX_MODIFIERS:
				//Set the modifiers
				$this->_regexContainer->setModifiers(
					$token->getPattern()
				);
			break;
			//Simple character
			case REBuilder_Parser_Token::TYPE_CHAR:
				//Create a simple character and add it to the current container
				$this->_currentItem = new REBuilder_Pattern_Simple(
					$token->getPattern()
				);
				$this->_containersStack->top()->addChild($this->_currentItem);
			break;
			//Non-printing character identifier
			case REBuilder_Parser_Token::TYPE_NON_PRINTING_CHAR:
				//Create a non-printing character identifier and add it to the
				//current container
				$this->_currentItem = new REBuilder_Pattern_NonPrintingChar(
					$token->getPattern()
				);
				$this->_containersStack->top()->addChild($this->_currentItem);
			break;
			//Generic character type identifier
			case REBuilder_Parser_Token::TYPE_GENERIC_CHAR_TYPE:
				//Create a generic character type identifier and add it to the
				//current container
				$this->_currentItem = new REBuilder_Pattern_GenericCharType(
					$token->getPattern()
				);
				$this->_containersStack->top()->addChild($this->_currentItem);
			break;
		
			//Simple assertion identifier
			case REBuilder_Parser_Token::TYPE_SIMPLE_ASSERTION:
				//Create a simple assertion identifier and add it to the current
				//container
				$this->_currentItem = new REBuilder_Pattern_SimpleAssertion(
					$token->getPattern()
				);
				$this->_containersStack->top()->addChild($this->_currentItem);
			break;
		}
	}
	
	/**
	 * Returns the regex main container
	 * 
	 * @return REBuilder_Pattern_Regex
	 */
	public function getRegexContainer ()
	{
		return $this->_regexContainer;
	}
	
	/**
	 * Returns the current container
	 * 
	 * @return REBuilder_Pattern_Container
	 */
	public function getCurrentContainer ()
	{
		return $this->_regexContainer;
	}
}