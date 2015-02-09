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
	 * Tokens stack
	 * 
	 * @var SplStack 
	 */
	protected $_tokensStack;
	
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
		$this->_tokensStack = new SplStack();
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
					$token->getIdentifier()
				);
				$this->_currentItem = $this->_regexContainer;
			break;
			//Regex end delimiter
			case REBuilder_Parser_Token::TYPE_REGEX_END_DELIMITER:
				//No need to handle this token
			break;
			//Regex modifiers
			case REBuilder_Parser_Token::TYPE_REGEX_MODIFIERS:
				//Set the modifiers
				$this->_regexContainer->setModifiers(
					$token->getIdentifier()
				);
			break;
			//Simple character
			case REBuilder_Parser_Token::TYPE_CHAR:
				//If the current item is already a char append data to it
				if ($this->_currentItem instanceof REBuilder_Pattern_Char) {
					$this->_currentItem->setChar(
						$this->_currentItem->getChar() . $token->getIdentifier()
					);
				} else {
					//Otherwise create a simple character and add it to the
					//current container
					$this->_currentItem = new REBuilder_Pattern_Char(
						$token->getIdentifier()
					);
					$this->_containersStack->top()->addChild($this->_currentItem);
				}
			break;
			//Non-printing character identifier
			case REBuilder_Parser_Token::TYPE_NON_PRINTING_CHAR:
				//Create a non-printing character identifier and add it to the
				//current container
				$this->_currentItem = new REBuilder_Pattern_NonPrintingChar(
					$token->getIdentifier()
				);
				$this->_containersStack->top()->addChild($this->_currentItem);
			break;
			//Generic character type identifier
			case REBuilder_Parser_Token::TYPE_GENERIC_CHAR_TYPE:
				//Create a generic character type identifier and add it to the
				//current container
				$this->_currentItem = new REBuilder_Pattern_GenericCharType(
					$token->getIdentifier()
				);
				$this->_containersStack->top()->addChild($this->_currentItem);
			break;
			//Simple assertion identifier
			case REBuilder_Parser_Token::TYPE_SIMPLE_ASSERTION:
				//Create a simple assertion identifier and add it to the current
				//container
				$this->_currentItem = new REBuilder_Pattern_SimpleAssertion(
					$token->getIdentifier()
				);
				$this->_containersStack->top()->addChild($this->_currentItem);
			break;
			//Control character identifier
			case REBuilder_Parser_Token::TYPE_CONTROL_CHAR:
				//Create a control character identifier and add it to the
				//current container
				$this->_currentItem = new REBuilder_Pattern_ControlChar(
					$token->getSubject()
				);
				$this->_containersStack->top()->addChild($this->_currentItem);
			break;
			//Extended unicode sequence identifier
			case REBuilder_Parser_Token::TYPE_EXT_UNICODE_SEQUENCE:
				//Create an extended unicode sequence identifier and add it to
				//the current container
				$this->_currentItem = new REBuilder_Pattern_UnicodeCharClass(
					$token->getIdentifier()
				);
				$this->_containersStack->top()->addChild($this->_currentItem);
			break;
			//Unicode character class identifier
			case REBuilder_Parser_Token::TYPE_UNICODE_CHAR_CLASS:
				//Create a unicode character class identifier and add it to
				//the current container
				$this->_currentItem = new REBuilder_Pattern_UnicodeCharClass(
					rtrim(ltrim($token->getSubject(), "{"), "}"),
					$token->getIdentifier() === "P"
				);
				$this->_containersStack->top()->addChild($this->_currentItem);
			break;
			//Hexadecimal character identifier
			case REBuilder_Parser_Token::TYPE_HEX_CHAR:
				//Create a hexadecimal character identifier and add it to the
				//current container
				$this->_currentItem = new REBuilder_Pattern_HexChar(
					$token->getSubject()
				);
				$this->_containersStack->top()->addChild($this->_currentItem);
			break;
			//Repetition identifier
			case REBuilder_Parser_Token::TYPE_REPETITION:
				$this->_handleRepetition($token);
			break;
		}
		
		//Push the token in the tokens stack
		$this->_tokensStack->push($token);
	}
	
	/**
	 * Handles a repetition token
	 * 
	 * @param REBuilder_Parser_Token $token Token
	 */
	protected function _handleRepetition (REBuilder_Parser_Token $token)
	{
		//Repetitions are allowed only after certain tokens, so check the last
		//emitted token
		$lastToken = $this->_tokensStack->top();
		switch ($lastToken->getType()) {
			//Handle lazy repetition
			case REBuilder_Parser_Token::TYPE_REPETITION:
				$prevLastToken = $this->_tokensStack->offsetGet(
					$this->_tokensStack->count() - 2
				);
				//if this token is "?" and follows a repetition token that
				//does not come after another repetition token set the lazy flag
				if ($token->getIdentifier() === "?" &&
					$prevLastToken->getType() !== REBuilder_Parser_Token::TYPE_REPETITION) {
					//Check if last repetition supports the lazy flag
					$lastRepetition = $this->_currentItem->getRepetition();
					if ($lastRepetition->supportsLazy()) {
						$lastRepetition->setLazy(true);
					}
					return;
				} else {
					throw new REBuilder_Exception_InvalidRepetition(
						"Nothing to repeat"
					);
				}
			break;
			//@TODO add allowed tokens
			//@TODO emit repetition as simple character if inside a character class
			//@TODO do not render repetition if character is inside a character class
			//Tokens that can handle the repetition
			case REBuilder_Parser_Token::TYPE_NON_PRINTING_CHAR:
			case REBuilder_Parser_Token::TYPE_GENERIC_CHAR_TYPE:
			case REBuilder_Parser_Token::TYPE_CONTROL_CHAR:
			case REBuilder_Parser_Token::TYPE_EXT_UNICODE_SEQUENCE:
			case REBuilder_Parser_Token::TYPE_UNICODE_CHAR_CLASS:
			case REBuilder_Parser_Token::TYPE_HEX_CHAR:
			break;
			//When simple characters are grouped, repetition is valid only
			//for the last one, so it needs to be splitted so that the last
			//character belongs to a different object
			case REBuilder_Parser_Token::TYPE_CHAR:
				$chars = $this->_currentItem->getChar();
				if (strlen($chars) > 1) {
					$this->_currentItem->setChar(substr($chars, 0, -1));
					$this->_currentItem = new REBuilder_Pattern_Char(
						$chars[strlen($chars) - 1]
					);
					$this->_containersStack->top()->addChild($this->_currentItem);
				}
			break;
			default:
				throw new REBuilder_Exception_InvalidRepetition(
					"Repetition cannot be inserted at this point"
				);
			break;
		}
		
		//Get the right repetition class
		switch ($token->getIdentifier()) {
			case "*":
				$repetition = new REBuilder_Pattern_Repetition_ZeroOrMore();
			break;
			case "+":
				$repetition = new REBuilder_Pattern_Repetition_OneOrMore();
			break;
			case "?":
				$repetition = new REBuilder_Pattern_Repetition_Optional();
			break;
			case "{":
				//Check if {}
				if (strpos($token->getSubject(), ",") === false) {
					$repetition = new REBuilder_Pattern_Repetition_Number(
						$token->getSubject()
					);
				} else {
					$limits = explode(",", $token->getSubject());
					$repetition = new REBuilder_Pattern_Repetition_Range(
						$limits[0],
						$limits[1] === "" ? null : $limits[1]
					);
				}
			break;
		}

		//Set the repetition on the current item
		$this->_currentItem->setRepetition($repetition);
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