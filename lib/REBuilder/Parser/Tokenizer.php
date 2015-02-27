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
	 * Array of subpattern names
	 * 
	 * @var array
	 */
	protected $_matches = array();
	
	/**
	 * Regex length
	 * 
	 * @var int
	 */
	protected $_length = 0;
	
	/**
	 * Number of open subpatters
	 * 
	 * @var int
	 */
	protected $_openSubpatterns = 0;
	
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
		list($endDelimiter, $rModifiers) = $this->_stripDelimitersAndModifiers();
		
		//Store regex length
		$this->_length = strlen($this->_regex);
		
		//Loop regex characters
		while (($char = $this->_consume()) !== null) {
			//If character is backslash and it's not escaped
			if ($char === "\\" && !$this->_escaped) {
				//Set escaped flag to true
				$this->_escaped  = true;
				continue;
			}
			//If not escaped and it's a dot
			elseif (!$this->_escaped && $char === ".") {
				//Emit a dot token
				$this->_emitToken(
					REBuilder_Parser_Token::TYPE_DOT,
					$char
				);
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
			//If escaped and it's a single byte identifier
			elseif ($this->_escaped && $char === "C") {
				//Emit a single byte identifier token
				$this->_emitToken(
					REBuilder_Parser_Token::TYPE_BYTE,
					$char
				);
			}
			//If not escaped and it's a pipe
			elseif (!$this->_escaped && $char === "|") {
				//Emit an alternation identifier token
				$this->_emitToken(
					REBuilder_Parser_Token::TYPE_ALTERNATION,
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
			//If escaped and it's the hexadecimal  character identifier "x"
			elseif ($this->_escaped && $char === "x") {
				//Find following hexadecimal digits
				$tokenSubject = "";
				for ($i = 0; $i < 2; $i++) {
					$nextChar = $this->_consume();
					if ($nextChar !== null &&
						REBuilder_Parser_Rules::validateHexString($nextChar)) {
						$tokenSubject .= $nextChar;
					} else {
						$nextChar !== null && $this->_unconsume();
						break;
					}
				}
				//Emit the hexadecimal character token
				$this->_emitToken(
					REBuilder_Parser_Token::TYPE_HEX_CHAR,
					$char,
					$tokenSubject
				);
			}
			//If escaped and it's the control character identifier "c"
			elseif ($this->_escaped && $char === "c") {
				//Take the next character
				$nextChar = $this->_consumeIgnoreEscape();
				//If there are no characters left throw an exception
				if ($nextChar === null) {
					throw new REBuilder_Exception_Generic(
						"Character not specified for control character"
					);
				}
				//Otherwise emit the control character token
				$this->_emitToken(
					REBuilder_Parser_Token::TYPE_CONTROL_CHAR,
					$char,
					$nextChar
				);
			}
			//If not escaped and it's a valid repetition identifier
			elseif (!$this->_escaped && ($char === "*" || $char === "+" ||
					$char === "?")) {
				//Emit a repetition token
				$this->_emitToken(
					REBuilder_Parser_Token::TYPE_REPETITION,
					$char
				);
			}
			//If not escaped and it's an open curly brace and the following
			//text identifies a repetition
			elseif (!$this->_escaped && $char === "{" &&
					$nextChars = $this->_consumeRegex("/^\d+(?:,\d*)?\}/")) {
				//Emit a repetition token
				$this->_emitToken(
					REBuilder_Parser_Token::TYPE_REPETITION,
					$char,
					rtrim($nextChars, "}")
				);
			}
			//If not escaped and it's an open round bracket
			elseif (!$this->_escaped && $char === "(") {
				$this->_handleSubpattern();
			}
			//If not escaped and it's a closed round bracket
			elseif (!$this->_escaped && $char === ")") {
				//Throw exception if there are no open subpatterns
				if (!$this->_openSubpatterns) {
					throw new REBuilder_Exception_Generic(
						"Unmatched parenthesis"
					);
				}
				//Emit a subpattern end token
				$this->_emitToken(
					REBuilder_Parser_Token::TYPE_SUBPATTERN_END,
					$char
				);
				$this->_openSubpatterns--;
				$this->_modifiersStack->pop();
			}
            //If escaped and it's g or k
			elseif ($this->_escaped && ($char === "g" || $char === "k")) {
				//It's a back reference. Check for the reference identifier
				if ($char === "g") {
                    $testPattern = "(?|(\d+)|\{(-?\d+|\w+)\})";
                } else {
                    $testPattern = "(<\w+>|'\w+'|\{\w+\})";
                }
                $nextChars = $this->_consumeRegex("/^$testPattern/", 1);
                if ($nextChars === null) {
                    throw new REBuilder_Exception_Generic(
                        "Invalid backreference"
                    );
                }
                if ($char === "k") {
                    $nextChars = substr($nextChars, 1, -1);
                }
                //Check reference validity
                if (!$this->_checkValidReference($nextChars)) {
                    throw new REBuilder_Exception_Generic(
                        "Reference to non-existent subpattern '$nextChars'"
                    );
                }
                //Emit a backreference token
                $this->_emitToken(
                    REBuilder_Parser_Token::TYPE_BACK_REFERENCE,
                    $char,
                    $nextChars
                );
			}
			//If escaped and it's a number
            elseif ($this->_escaped && is_numeric($char)) {
                //If the character is a 0 consume up to 2 octal digits,
                //otherwise consume all the following digits
                if ($char === "0") {
                    $testPattern = "^[0-7]{1,2}";
                } else {
                    $testPattern = "^\d+";
                }
                //Consume following numbers
                $nextChars = $this->_consumeRegex("/^$testPattern/");
                if ($nextChars !== null) {
                    $char .= $nextChars;
                }
                //If the first digit is 0 or its a valid octal number and there
                //are not enough back references
                $hasReference = $this->_checkValidReference($char);
                if ($char[0] === "0" ||
                    (preg_match("/^[0-7]{2,3}$/", $char) && !$hasReference)) {
                    $this->_emitToken(
                        REBuilder_Parser_Token::TYPE_OCTAL_CHAR,
                        $char
                    );
                }
                //If there number corresponds to a subpattern
                elseif ($hasReference) {
                    //Emit a backreference token
                    $this->_emitToken(
                        REBuilder_Parser_Token::TYPE_BACK_REFERENCE,
                        "\\",
                        $char
                    );
                }
                //Otherwise the character does not match any subpattern so
                //throw an exception
                else {
                    throw new REBuilder_Exception_Generic(
                        "Reference to non-existent subpattern '$char'"
                    );
                }
            }
			//If it does not fall in any of the cases above
			else {
				//If the character is not escaped and the "x" modifier is active
				if (!$this->_escaped &&
					strpos($this->_modifiersStack->top(), "x") !== false) {
					//If it is a "#"
					if ($char === "#") {
						//Emit a comment token
						$nextChars = $this->_consumeUntil("\n");
						if ($nextChars === null) {
							$nextChars = $this->_consumeRemaining();
						}
						$this->_emitToken(
							REBuilder_Parser_Token::TYPE_COMMENT,
							$char,
							$nextChars
						);
						continue;
					}
					//If it is a whitespace ignore it
					elseif (preg_match("/\s/", $char)) {
						continue;
					}
				}
				//Emit the character as a simple pattern token
				$this->_emitToken(
					REBuilder_Parser_Token::TYPE_CHAR,
					$char
				);
			}
			
			//Reset the escaped state
			$this->_escaped  = false;
		}
		
		//If the escaped state is already active it means that no end delimiter
		//has been found, so an exception must be thrown
		if ($this->_escaped) {
			throw new REBuilder_Exception_InvalidDelimiter(
				"End delimiter '$endDelimiter' not found"
			);
		}
		
		//Throw exception if there are unclosed subpatterns
		if ($this->_openSubpatterns) {
			throw new REBuilder_Exception_Generic(
				"The regex contains unclosed subpatterns"
			);
		}
		
		//Emit the end delimiter token
		$this->_emitToken(
			REBuilder_Parser_Token::TYPE_REGEX_END_DELIMITER,
			$endDelimiter
		);
		
		//If regex modifiers were specified emit the token
		if ($rModifiers) {
			$this->_emitToken(
				REBuilder_Parser_Token::TYPE_REGEX_MODIFIERS,
				$rModifiers
			);
		}
	}
	
	/**
	 * Handles every case that can happen after a open round bracket
	 * 
	 * @throws REBuilder_Exception_Generic
	 */
	protected function _handleSubpattern ()
	{
		//Don't emit the tokens immediately but store them in an array, in this
		//way they can be handled before emitting
		$tokens = array();
		$tokens[] = array(
			REBuilder_Parser_Token::TYPE_SUBPATTERN_START,
			"("
		);
		$this->_applyModifiers("");
		$this->_openSubpatterns++;
		$nextChar = $this->_consume();
		//Check if the next character is a question mark that identifies
		//group options
		if ($nextChar === "?") {
			//Check if the following characters represent subpattern
			//modifiers and/or non capturing flag
			if ($nextChars = $this->_consumeRegex("/^[a-z\-]*:/i")) {
				//Store a non capturing subpattern token
				$groupModifiers = rtrim($nextChars, ":");
				if ($groupModifiers) {
					$this->_applyModifiers($groupModifiers);
				}
				$tokens[] = array(
					REBuilder_Parser_Token::TYPE_SUBPATTERN_NON_CAPTURING,
					":",
					$groupModifiers
				);
			}
			//Check if the following character is a pipe
			elseif ($nextChar = $this->_consumeIfEquals("|")) {
				//Store a subpattern group matches token
				$tokens[] = array(
					REBuilder_Parser_Token::TYPE_SUBPATTERN_GROUP_MATCHES,
					$nextChar
				);
			}
			//Check if the following character represents the once only
			//subpattern identifier
			elseif ($nextChar = $this->_consumeIfEquals(">")) {
				//Store a subpattern once only token
				$tokens[] = array(
					REBuilder_Parser_Token::TYPE_SUBPATTERN_ONCE_ONLY,
					$nextChar
				);
			}
			//Check if the following characters represent subpattern
			//name
			elseif ($nextChars = $this->_consumeRegex(
					"/^(?|P?<(\w+)>|'(\w+)')/", 1
				)) {
                $this->_matches[] = $nextChars;
				//Store a subpattern name token
				$tokens[] = array(
					REBuilder_Parser_Token::TYPE_SUBPATTERN_NAME,
					"P",
					$nextChars
				);
			}
			//Check if the following characters represent a list of modifiers
			//and followed by a closed round bracket
			elseif ($nextChars = $this->_consumeRegex("/^[a-z\-]*\)/i")) {
				//Remove current tokens, decrement open subpattern
				//count and apply the specified modifiers
				$tokens = array();
				$nextChars = str_replace(")", "", $nextChars);
				$this->_applyModifiers($nextChars);
				$this->_openSubpatterns--;
				//Store an internal option token
				$tokens[] = array(
					REBuilder_Parser_Token::TYPE_INTERNAL_OPTION,
					"(?",
					$nextChars
				);
			}
            //Check if the following character represent a lookahead assertion
			elseif ($nextChar = $this->_consumeIfEquals(array("=", "!"))) {
				//Remove current tokens
				$tokens = array();
				//Store a lookahead assertion token
				$tokens[] = array(
					REBuilder_Parser_Token::TYPE_LOOKAHEAD_ASSERTION,
					"(?" . $nextChar
				);
			}
			//Check if the following character represent a back reference
			elseif ($nextChars = $this->_consumeRegex("/^P=(\w+)\)/", 1)) {
                //Check reference validity
                if (!$this->_checkValidReference($nextChars)) {
                    throw new REBuilder_Exception_Generic(
                        "Reference to non-existent subpattern '$nextChars'"
                    );
                }
				//Remove current tokens, decrement open subpattern
				//count and emit a back reference token
				$tokens = array();
				$this->_openSubpatterns--;
				$tokens[] = array(
					REBuilder_Parser_Token::TYPE_BACK_REFERENCE,
					"(?P=",
					$nextChars
				);
			}
			//Check if the following character represent a lookbehind assertion
			elseif ($nextChars = $this->_consumeRegex("/^<[=!]/")) {
				//Remove current tokens
				$tokens = array();
				//Store a lookbehind assertion token
				$tokens[] = array(
					REBuilder_Parser_Token::TYPE_LOOKBEHIND_ASSERTION,
					"(?" . $nextChars
				);
			}
			//Check if the following character is a "#"
			elseif ($nextChar = $this->_consumeIfEquals("#")) {
				//Get the complete comment
				$nextChars = $this->_consumeUntil(")", true);
				//If the closing bracket has not been found, throw an exception
				if ($nextChars === null) {
					throw new REBuilder_Exception_Generic(
						"Unclosed comment"
					);
				}
				//Remove current tokens
				$tokens = array();
				$this->_openSubpatterns--;
				//Store a comment token
				$tokens[] = array(
					REBuilder_Parser_Token::TYPE_COMMENT,
					"(?" . $nextChar,
					rtrim($nextChars, ")")
				);
			}
			//Syntax error
			elseif (($nextChar = $this->_consume()) !== null) {
				throw new REBuilder_Exception_Generic(
					"Invalid char '$nextChar' in subpattern options"
				);
			}
		} else {
            $this->_matches[] = "";
			$this->_unconsume();
		}
		//Emit the tokens in order
		foreach ($tokens as $token) {
			call_user_func_array(array($this, "_emitToken"), $token);
		}
	}
    
	/**
	 * Check if the given string is a valid reference in the regex
	 * 
	 * @return bool
	 */
	protected function _checkValidReference ($char)
	{
        if (is_numeric($char)) {
            return count($this->_matches) >= abs($char);
        }
        return in_array($char, $this->_matches);
	}
	
	/**
	 * Consumes next character. It returns null if there are no characters left
	 * 
	 * @return string|null
	 */
	protected function _consume ()
	{
		if ($this->_index < $this->_length) {
			return $this->_regex[$this->_index++];
		}
		return null;
	}
	
	/**
	 * Consumes next character only if it is equal to one of the given
	 * characters
	 * 
	 * @param string|array $testChars Characters to test
	 * @return string|null
	 */
	protected function _consumeIfEquals ($testChars = array())
	{
		if (!is_array($testChars)) {
			$testChars = array($testChars);
		}
		$char = $this->_consume();
		if ($char !== null && in_array($char, $testChars)) {
			return $char;
		}
		$this->_unconsume();
		return null;
	}
	
	/**
	 * Consumes the rest of the regex
	 * 
	 * @return string
	 */
	protected function _consumeRemaining ()
	{
		$ret = substr($this->_regex, $this->_index);
		$this->_index = $this->_length;
		return $ret === false ? "" : $ret;
	}
	
	/**
	 * Consumes next character ignoring the escape
	 * 
	 * @return string|null
	 */
	protected function _consumeIgnoreEscape ()
	{
		$char = $this->_consume();
		if ($char !== null) {
			if ($char === "\\") {
				$char = $this->_consume();
			}
			return $char;
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
				$this->_unconsume($number - 1);
				return null;
			} elseif ($nextChar === $char) {
				if (!$includeChar) {
					$this->_unconsume();
				} else {
					$ret .= $nextChar;
				}
				break;
			} else {
				$ret .= $nextChar;
			}
		}
		return $ret;
	}
	
	/**
	 * Tests a regular expression, if it matches it consumes every matched
	 * character, if not it returns null and it does not consume anything
	 * 
	 * @param string $reg         Regular expression to test
	 * @param int    $matchNumber Match number to return
	 * @return string|null
	 */
	protected function _consumeRegex ($reg, $matchNumber = 0)
	{
		if (preg_match($reg, substr($this->_regex, $this->_index),
					   $match, PREG_OFFSET_CAPTURE)) {
			$this->_index += $match[0][1] + strlen($match[0][0]);
			return $match[$matchNumber][0];
		}
		return null;
	}
	
	/**
	 * Strip regex delimiters and modifiers and returns the end delimiter and
	 * the regex modifiers
	 * 
	 * @throws REBuilder_Exception_InvalidDelimiter
	 * @return array
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
		
		return array($endDelimiter, $modifiers);
	}
	
	/**
	 * Apply modifiers to the modifiers stack
	 * 
	 * @param string $modifiers Modifiers to apply
	 */
	protected function _applyModifiers ($modifiers)
	{
		$currentModifiers = $this->_modifiersStack->top();
		//If $modifiers is an empty string just take current modifiers and
		//add them to the modifiers stack
		//Othwerwise
		if ($modifiers !== "") {
			//Explode modifiers with the dash, the first group of modifiers
			//represents the modifiers to add, every following group represents
			//the modifiers to subtract
			$groups = explode("-", $modifiers);
			foreach ($groups as $k => $group) {
				if (!$group) {
					continue;
				}
				$len = strlen($group);
				for ($i = 0; $i < $len; $i++) {
					$contains = strpos($currentModifiers, $group[$i]) !== false;
					if (!$k && !$contains) {
						$currentModifiers .= $group[$i];
					} elseif ($k && $contains) {
						$currentModifiers = str_replace(
							$group[$i], "", $currentModifiers
						);
					}
				}
			}
			//Remove the last entry in modifiers stack
			$this->_modifiersStack->pop();
		}
		//Add calculated modifiers to the top of the modifiers stack
		$this->_modifiersStack->push($currentModifiers);
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