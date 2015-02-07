<?php
/**
 * This class is used to emit token from regex parsing
 * 
 * @author Marco Marchiò
 */
class REBuilder_Parser_Tokenizer
{
	/**
	 * Regex to tokenize
	 * 
	 * @var string
	 */
	protected $_regex;
	
	/**
	 * Regex modifiers stack
	 * 
	 * @var SplStack 
	 */
	protected $_modifiersStack;
	
	/**
	 * Function that will receive the emitted tokens
	 * 
	 * @var callable
	 */
	protected $_receiver;
	
	/**
	 * Regex escaped flag
	 * 
	 * @var bool
	 */
	protected $_escaped = false;
	
	/**
	 * Current index in the regexp
	 * 
	 * @var int
	 */
	protected $_index = 0;
	
	/**
	 * Regex length
	 * 
	 * @var int
	 */
	protected $_length = 0;
	
	/**
	 * Constructor
	 * 
	 * @param string   $regex    The regex to tokenize
	 * @param callable $receiver Function that will receive the emitted tokens
	 * @throws REBuilder_Exception_EmptyRegex
	 */
	public function __construct ($regex, callable $receiver)
	{
		//Check if the regex is empty
		if ($regex === "") {
			throw new REBuilder_Exception_EmptyRegex();
		}
		
		$this->_regex = $regex;
		$this->_receiver = $receiver;
		$this->_modifiersStack = new SplStack;
	}
	
	/**
	 * Starts the tokenization proces
	 */
	public function tokenize ()
	{
		//Since delimiters are the only exception to the normal regex syntax and
		//the tokenizer needs to know regex modifiers to handle some situations,
		//parse them immediately and strip them from the regex
		$endDelimiter = $this->_stripDelimitersAndModifiers();
		
		//Store regex length
		$this->_length = strlen($this->_regex);
		
		//Loop regex characters
		while ($char = $this->_consume()) {
			//If character is backslash and it's not escaped
			if ($char === "\\" && !$this->_escaped) {
				//Set escaped flag to true
				$this->_escaped  = true;
			}
			//If escaped and it's a generic character type identifier
			elseif ($this->_escaped &&
					REBuilder_Parser_Rules::validateGenericCharType($char)) {
				//Emit a generic character type token
				$this->_emitToken(
					REBuilder_Parser_Token::TYPE_GENERIC_CHAR_TYPE,
					$char
				);
			}
			//If escaped and it's a simple assertion identifier
			elseif ($this->_escaped &&
					REBuilder_Parser_Rules::validateSimpleAssertion($char)) {
				//Emit a simple assertion token
				$this->_emitToken(
					REBuilder_Parser_Token::TYPE_SIMPLE_ASSERTION,
					$char
				);
			}
			//If escaped and it's a non-printing character identifier
			elseif ($this->_escaped &&
					REBuilder_Parser_Rules::validateNonPrintingChar($char)) {
				//Emit a non-printing character token
				$this->_emitToken(
					REBuilder_Parser_Token::TYPE_NON_PRINTING_CHAR,
					$char
				);
			}
			//If escaped and it's the extended unicode sequence identifier
			elseif ($this->_escaped && $char === "X") {
				//Emit a extended unicode sequence token
				$this->_emitToken(
					REBuilder_Parser_Token::TYPE_EXT_UNICODE_SEQUENCE,
					$char
				);
			}
			//If escaped and it's the unicode character class identifier
			elseif ($this->_escaped && ($char === "p" || $char === "P")) {
				//Take the next character
				$nextChar = $this->_consume();
				//If there are no characters left throw an exception
				if ($nextChar === null) {
					throw new REBuilder_Exception_Generic(
						"Unspecified character class form \\" . $char
					);
				}
				//If the next char is not { emit the token
				elseif ($nextChar !== "{") {
					$this->_emitToken(
						REBuilder_Parser_Token::TYPE_UNICODE_CHAR_CLASS,
						$char,
						$nextChar
					);
				}
				//If the next char is {
				else {
					//Find everything until the closing bracket
					$nextChars = $this->_consumeUntil("}", true);
					//If the closing bracket has not been found throw an
					//exception
					if ($nextChars === null) {
						throw new REBuilder_Exception_Generic(
							"Unclosed \\" . $char . " character class"
						);
					}
					//Otherwise emit the unicode char class token
					else {
						$this->_emitToken(
							REBuilder_Parser_Token::TYPE_UNICODE_CHAR_CLASS,
							$char,
							$nextChar . $nextChars
						);
					}
				}
			}
			//If escaped and it's the control character identifier "c"
			elseif ($this->_escaped && $char === "c") {
				//Take the next character
				$nextChar = $this->_consume();
				//If there are no characters left throw an exception
				if ($nextChar === null) {
					throw new REBuilder_Exception_Generic(
						"Character not specified for \c"
					);
				}
				//Otherwise emit the control character token
				$this->_emitToken(
					REBuilder_Parser_Token::TYPE_CONTROL_CHAR,
					$char,
					$nextChar
				);
			}
			//If it does not fall in any of the cases above
			else {
				//emit the character as a simple pattern token
				$this->_emitToken(
					REBuilder_Parser_Token::TYPE_CHAR,
					$char
				);
			}
		}
		
		//Emit the end delimiter token
		$this->_emitToken(
			REBuilder_Parser_Token::TYPE_REGEX_END_DELIMITER,
			$endDelimiter
		);
		
		//If regex modifiers were specified emit the token
		if ($this->_modifiersStack->bottom()) {
			$this->_emitToken(
				REBuilder_Parser_Token::TYPE_REGEX_MODIFIERS,
				$this->_modifiersStack->bottom()
			);
		}
	}
	
	/**
	 * Consumes next character. It returns null if there are no characters left
	 * 
	 * @return string|null
	 */
	protected function _consume ()
	{
		if ($this->_index < $this->_length - 1) {
			return $this->_regex[$this->_index++];
		}
		return null;
	}
	
	/**
	 * Unconsumes the given number of characters
	 * 
	 * @param int $number Number of characters to unconsume
	 */
	protected function _unconsume ($number = 1)
	{
		$this->_index = max($this->_index - $number, 0);
	}
	
	/**
	 * Consumes everything until the given character. If the character has not
	 * been found it returns null
	 * 
	 * @param string $char        Character to find
	 * @param bool   $includeChar True to includ the searched character at the
	 *                            end of the result
	 * @return string|null
	 */
	protected function _consumeUntil ($char, $includeChar = false)
	{
		$ret = "";
		$number = 0;
		
		while (true) {
			$number++;
			$nextChar = $this->_consume();
			//If there are no more characters reset the index and return null
			if ($nextChar === null) {
				$this->_unconsume($number);
				return null;
			} elseif ($nextChar === $char) {
				if (!$includeChar) {
					$this->_unconsume();
				} else {
					$ret .= $nextChar;
				}
				return $ret;
			} else {
				$ret .= $nextChar;
			}
		}
	}
	
	/**
	 * Strip regex delimiters and modifiers and returns the end delimiter
	 * 
	 * @throws REBuilder_Exception_InvalidDelimiter
	 * @return string
	 */
	protected function _stripDelimitersAndModifiers ()
	{
		//Emit the regex delimiter token and strip it from the beginning of the
		//regex
		$delimiter = $this->_regex[0];
		$this->_emitToken(
			REBuilder_Parser_Token::TYPE_REGEX_START_DELIMITER,
			$delimiter
		);
		$this->_regex = substr($this->_regex, 1);
		
		//Get the right end delimiter and strip it from the end of the regex,
		//then get the modifiers
		$endDelimiter = REBuilder_Parser_Rules::getEndDelimiter($delimiter);
		$endDelimiterPos = strrpos($this->_regex, $endDelimiter);
		if ($endDelimiterPos === false) {
			throw new REBuilder_Exception_InvalidDelimiter(
				"End delimiter '$endDelimiter' not found"
			);
		}
		$modifiers = substr($this->_regex, $endDelimiterPos + 1);
		$this->_regex = substr($this->_regex, 0, $endDelimiterPos);
		$this->_modifiersStack->push($modifiers);
		
		return $endDelimiter;
	}
	
	/**
	 * Emits a token to the receiver function
	 * 
	 * @param int    $type       Token's type
	 * @param string $identifier Token's identifier
	 * @param string $subject    Token's subject
	 */
	protected function _emitToken ($type, $identifier, $subject = null)
	{
		$token = new REBuilder_Parser_Token($type, $identifier, $subject);
		call_user_func($this->_receiver, $token);
	}
}