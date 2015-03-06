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
     * Flag that indicates if an end anchor must be added
     * 
     * @var bool
     */
    protected $_pendingEndAnchor = false;

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
                $this->_currentItem = null;
            break;
            //Regex end delimiter
            case REBuilder_Parser_Token::TYPE_REGEX_END_DELIMITER:
                //Anchor the regex if required
                if ($this->_pendingEndAnchor) {
                    $this->_containersStack->top()->setEndAnchored(true);
                }
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
                if ($this->_currentItem &&
                    $this->_currentItem instanceof REBuilder_Pattern_Char &&
                    $this->_tokensStack->top()->getType() === REBuilder_Parser_Token::TYPE_CHAR) {
					$this->_currentItem->setChar(
						$this->_currentItem->getChar() . $token->getSubject()
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
			//Dot
			case REBuilder_Parser_Token::TYPE_DOT:
				//Create a dot and add it to the current container
				$this->_currentItem = new REBuilder_Pattern_Dot;
				$this->_containersStack->top()->addChild($this->_currentItem);
			break;
			//Single byte identifier
			case REBuilder_Parser_Token::TYPE_BYTE:
				//Create a single byte identifier and add it to the current
				//container
				$this->_currentItem = new REBuilder_Pattern_Byte;
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
			//Octal character identifier
			case REBuilder_Parser_Token::TYPE_OCTAL_CHAR:
				//Create a octal character identifier and add it to the
				//current container
				$this->_currentItem = new REBuilder_Pattern_OctalChar(
					$token->getSubject()
				);
				$this->_containersStack->top()->addChild($this->_currentItem);
			break;
			//Back reference identifier
			case REBuilder_Parser_Token::TYPE_BACK_REFERENCE:
				//Create a back reference identifier and add it to the
				//current container
				$this->_currentItem = new REBuilder_Pattern_BackReference(
					$token->getSubject()
				);
				$this->_containersStack->top()->addChild($this->_currentItem);
			break;
			//Recursive pattern identifier
			case REBuilder_Parser_Token::TYPE_RECURSIVE_PATTERN:
				//Create a recursive pattern identifier and add it to the
				//current container
				$this->_currentItem = new REBuilder_Pattern_RecursivePattern(
					$token->getSubject()
				);
				$this->_containersStack->top()->addChild($this->_currentItem);
			break;
			//Alternation identifier
			case REBuilder_Parser_Token::TYPE_ALTERNATION:
                if ($this->_containersStack->top() instanceof REBuilder_Pattern_Alternation) {
                    //If already inside an alternation group, create a new
                    //alternation
                    $currentAlternation = $this->_containersStack->pop();
                    $newAlternation = new REBuilder_Pattern_Alternation;
                    $currentAlternation->getParent()->addChild($newAlternation);
                    //Anchor the current alternation if required
                    if ($this->_pendingEndAnchor) {
                        $currentAlternation->setEndAnchored(true);
                    }
                } else {
                    //Create a new alternation and move all the children from
                    //the current container to the new alternation
                    $currentContainer = $this->_containersStack->top();
                    $children = $currentContainer->getChildren();
                    //Create the alternation group structure
                    $alternationGroup = new REBuilder_Pattern_AlternationGroup;
                    $alternation = new REBuilder_Pattern_Alternation;
                    $alternation->addChildren($children);
                    $alternationGroup->addChild($alternation);
                    $currentContainer->addChild($alternationGroup);
                    $newAlternation = new REBuilder_Pattern_Alternation;
                    $alternationGroup->addChild($newAlternation);
                    //Move the start anchor from the container to the
                    //alternation that contains its children
                    if ($currentContainer->getStartAnchored()) {
                        $currentContainer->setStartAnchored(false);
                        $alternation->setStartAnchored(true);
                    }
                }
                $this->_containersStack->push($newAlternation);
                $this->_currentItem = null;
            break;
            //Subpattern start character
            case REBuilder_Parser_Token::TYPE_SUBPATTERN_START:
                //Create a new subpattern and add it to the container stack
                $subPattern = new REBuilder_Pattern_SubPattern;
                $this->_containersStack->top()->addChild($subPattern);
                $this->_containersStack->push($subPattern);
                $this->_currentItem = null;
            break;
            //Subpattern end character
            case REBuilder_Parser_Token::TYPE_SUBPATTERN_END:
                //Anchor the container if required
                if ($this->_pendingEndAnchor) {
                    $this->_containersStack->top()->setEndAnchored(true);
                }
                //If the current container is an alternation remove it first
                if ($this->_containersStack->top() instanceof REBuilder_Pattern_Alternation) {
                    $this->_containersStack->pop();
                }
				//Remove the subpattern from the container stack and make it
				//the current item
				$this->_currentItem = $this->_containersStack->pop();
			break;
			//Subpattern non capturing flag and modifiers
			case REBuilder_Parser_Token::TYPE_SUBPATTERN_NON_CAPTURING:
				//Set the subpattern as non capturing and set its modifiers if
				//present
				$this->_containersStack->top()->setCapture(false);
				if ($token->getSubject()) {
					$this->_containersStack->top()->setModifiers(
						$token->getSubject()
					);
				}
			break;
			//Subpattern group matches identifier
			case REBuilder_Parser_Token::TYPE_SUBPATTERN_GROUP_MATCHES:
				//Enable subpattern group matches mode and make the subpattern
				//non capturing
				$this->_containersStack->top()
					 ->setGroupMatches(true)
					 ->setCapture(false);
			break;
			//Subpattern once only identifier
			case REBuilder_Parser_Token::TYPE_SUBPATTERN_ONCE_ONLY:
				//Enable once only mode and make the subpattern non capturing
				$this->_containersStack->top()
					 ->setOnceOnly(true)
					 ->setCapture(false);
			break;
			//Subpattern name
			case REBuilder_Parser_Token::TYPE_SUBPATTERN_NAME:
				//Set the subpattern name
				$this->_containersStack->top()->setName($token->getSubject());
			break;
			//Internal option identifier
			case REBuilder_Parser_Token::TYPE_INTERNAL_OPTION:
				//Create an internal option and add it to the current container
				$this->_containersStack->top()->addChild(
					new REBuilder_Pattern_InternalOption($token->getSubject())
				);
				$this->_currentItem = null;
			break;
			//Assertion identifier
			case REBuilder_Parser_Token::TYPE_LOOKAHEAD_ASSERTION:
			case REBuilder_Parser_Token::TYPE_LOOKBEHIND_ASSERTION:
				$assertion = new REBuilder_Pattern_Assertion(
					strpos($token->getIdentifier(), "<") === false,
					strpos($token->getIdentifier(), "!") !== false
				);
				$this->_containersStack->top()->addChild($assertion);
				$this->_containersStack->push($assertion);
				$this->_currentItem = null;
			break;
			//Comment
			case REBuilder_Parser_Token::TYPE_COMMENT:
				//Create comment and add it to the current container
				$this->_containersStack->top()->addChild(
					new REBuilder_Pattern_Comment($token->getSubject())
				);
			break;
			//Repetition identifier
			case REBuilder_Parser_Token::TYPE_REPETITION:
				$this->_handleRepetition($token);
			break;
			//Start anchor identifier
			case REBuilder_Parser_Token::TYPE_START_ANCHOR:
                //Ignore if the container already contains children
                if (!$this->_containersStack->top()->hasChildren()) {
                    $this->_containersStack->top()->setStartAnchored(true);
                }
				$this->_currentItem = null;
			break;
			//End anchor identifier
			case REBuilder_Parser_Token::TYPE_END_ANCHOR:
                //Set only the pending end anchor flag. It will be unset when
                //another token is emitted and it will be evaluated only when
                //a container is closed
                $this->_pendingEndAnchor = true;
            break;
			//Start char class identifier
			case REBuilder_Parser_Token::TYPE_CHAR_CLASS_START:
                //Create a new character class and add it to the container stack
                $charClass = new REBuilder_Pattern_CharClass;
                $this->_containersStack->top()->addChild($charClass);
                $this->_containersStack->push($charClass);
                $this->_currentItem = null;
            break;
			//Char class negation identifier
            case REBuilder_Parser_Token::TYPE_CHAR_CLASS_NEGATE:
				//Negate the current char class
				$this->_containersStack->top()->setNegate(true);
			break;
			//End char class identifier
            case REBuilder_Parser_Token::TYPE_CHAR_CLASS_END:
				//Remove the char class from the container stack and make it
				//the current item
				$this->_currentItem = $this->_containersStack->pop();
			break;
			//Posix char class identifier
            case REBuilder_Parser_Token::TYPE_POSIX_CHAR_CLASS:
				//Remove the char class from the container stack and make it
				//the current item
                $negate = false;
                $class = $token->getSubject();
                if (strpos($class, "^") === 0) {
                    $negate = true;
                    $class = ltrim($class, "^");
                }
				//Create a posix char class and add it to the current container
				$this->_containersStack->top()->addChild(
					new REBuilder_Pattern_PosixCharClass($class, $negate)
				);
			break;
			//Char class range identifier
            case REBuilder_Parser_Token::TYPE_CHAR_CLASS_RANGE:
				//Create a new character class range and add it to the
                //container stack, move the last inserted item to the range
                $currentChildren = $this->_containersStack->top()->getChildren();
                $range = new REBuilder_Pattern_CharClassRange;
                $range->addChild($currentChildren[count($currentChildren) - 1]);
                $this->_containersStack->top()->addChild($range);
                $this->_containersStack->push($range);
                $this->_currentItem = null;
			break;
		}
		
		//Push the token in the tokens stack
		$this->_tokensStack->push($token);
        
        //Unset the pending end anchor flag if the token is not an end anchor
        if ($token->getType() !== REBuilder_Parser_Token::TYPE_END_ANCHOR) {
            $this->_pendingEndAnchor = false;
        }
        //If the current container is a character class range and the current
        //token is not the char class range identifier, remove the char class
        //range from the container stack
        if ($this->_containersStack->top() instanceof REBuilder_Pattern_CharClassRange &&
                $token->getType() !== REBuilder_Parser_Token::TYPE_CHAR_CLASS_RANGE) {
            $this->_containersStack->pop();
        }
	}
	
	/**
	 * Handles a repetition token
	 * 
	 * @param REBuilder_Parser_Token $token Token
	 */
	protected function _handleRepetition (REBuilder_Parser_Token $token)
	{
		//If there is no current item, throw exception
		if ($this->_currentItem === null) {
			throw new REBuilder_Exception_InvalidRepetition(
				"Nothing to repeat"
			);
		}
		
		//Repetitions are allowed only after certain tokens, so check the last
		//emitted token
		$lastToken = $this->_tokensStack->top();
		switch ($lastToken->getType()) {
			//Handle lazy repetition
			case REBuilder_Parser_Token::TYPE_REPETITION:
				$prevLastToken = $this->_tokensStack->offsetGet(1);
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
			case REBuilder_Parser_Token::TYPE_DOT:
			case REBuilder_Parser_Token::TYPE_BYTE:
			case REBuilder_Parser_Token::TYPE_SUBPATTERN_END:
			case REBuilder_Parser_Token::TYPE_COMMENT:
			case REBuilder_Parser_Token::TYPE_OCTAL_CHAR:
			case REBuilder_Parser_Token::TYPE_BACK_REFERENCE:
			case REBuilder_Parser_Token::TYPE_CHAR_CLASS_END:
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
}
