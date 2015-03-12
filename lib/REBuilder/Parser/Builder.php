<?php
/**
 * This file is part of the REBuilder package
 *
 * (c) Marco Marchiò <marco.mm89@gmail.com>
 *
 * For the full copyright and license information refer to the LICENSE file
 * distributed with this source code
 */

namespace REBuilder\Parser;

use REBuilder\Pattern;
use REBuilder\Exception;

/**
 * This class handles the tokens emitted from the tokenizer and builds the
 * regex structure
 * 
 * @author Marco Marchiò <marco.mm89@gmail.com>
 */
class Builder
{
    /**
     * Regex main container
     * 
     * @var REBuilder\Pattern\Regex
     */
    protected $_regexContainer;

    /**
     * Containers stack
     * 
     * @var \SplStack
     */
    protected $_containersStack;

    /**
     * Tokens stack
     * 
     * @var \SplStack
     */
    protected $_tokensStack;

    /**
     * Current item
     * 
     * @var REBuilder\Pattern\AbstractPattern
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
        $this->_containersStack = new \SplStack();
        $this->_tokensStack = new \SplStack();
    }

    /**
     * This functions receives a token and handles it to build the structure
     * 
     * @param Token $token Token
     * 
     * @return void
     */
    public function receiveToken (Token $token)
    {
        switch ($token->getType())
        {
            //Regex start delimiter
            case Token::TYPE_REGEX_START_DELIMITER:
                //Create the regex container if it does not exists
                if (!$this->_regexContainer) {
                    $this->_regexContainer = new Pattern\Regex;
                    $this->_containersStack->push($this->_regexContainer);
                }
                //Set the delimiter
                $this->_regexContainer->setDelimiter(
                    $token->getIdentifier()
                );
                $this->_currentItem = null;
            break;
            //Regex end delimiter
            case Token::TYPE_REGEX_END_DELIMITER:
                //Anchor the regex if required
                if ($this->_pendingEndAnchor) {
                    $this->_containersStack->top()->setEndAnchored(true);
                }
            break;
            //Regex modifiers
            case Token::TYPE_REGEX_MODIFIERS:
                //Set the modifiers
                $this->_regexContainer->setModifiers(
                    $token->getIdentifier()
                );
            break;
            //Simple character
            case Token::TYPE_CHAR:
                //If the current item is already a char append data to it
                if ($this->_currentItem &&
                    $this->_currentItem instanceof Pattern\Char &&
                    $this->_tokensStack->top()->getType() === Token::TYPE_CHAR) {
                    $this->_currentItem->setChar(
                        $this->_currentItem->getChar() . $token->getSubject()
                    );
                } else {
                    //Otherwise create a simple character and add it to the
                    //current container
                    $this->_currentItem = new Pattern\Char(
                        $token->getIdentifier()
                    );
                    $this->_containersStack->top()->addChild($this->_currentItem);
                }
            break;
            //Non-printing character identifier
            case Token::TYPE_NON_PRINTING_CHAR:
                //Create a non-printing character identifier and add it to the
                //current container
                $this->_currentItem = new Pattern\NonPrintingChar(
                    $token->getIdentifier()
                );
                $this->_containersStack->top()->addChild($this->_currentItem);
            break;
            //Generic character type identifier
            case Token::TYPE_GENERIC_CHAR_TYPE:
                //Create a generic character type identifier and add it to the
                //current container
                $this->_currentItem = new Pattern\GenericCharType(
                    $token->getIdentifier()
                );
                $this->_containersStack->top()->addChild($this->_currentItem);
            break;
            //Simple assertion identifier
            case Token::TYPE_SIMPLE_ASSERTION:
                //Create a simple assertion identifier and add it to the current
                //container
                $this->_currentItem = new Pattern\SimpleAssertion(
                    $token->getIdentifier()
                );
                $this->_containersStack->top()->addChild($this->_currentItem);
            break;
            //Dot
            case Token::TYPE_DOT:
                //Create a dot and add it to the current container
                $this->_currentItem = new Pattern\Dot;
                $this->_containersStack->top()->addChild($this->_currentItem);
            break;
            //Single byte identifier
            case Token::TYPE_BYTE:
                //Create a single byte identifier and add it to the current
                //container
                $this->_currentItem = new Pattern\Byte;
                $this->_containersStack->top()->addChild($this->_currentItem);
            break;
            //Control character identifier
            case Token::TYPE_CONTROL_CHAR:
                //Create a control character identifier and add it to the
                //current container
                $this->_currentItem = new Pattern\ControlChar(
                    $token->getSubject()
                );
                $this->_containersStack->top()->addChild($this->_currentItem);
            break;
            //Extended unicode sequence identifier
            case Token::TYPE_EXT_UNICODE_SEQUENCE:
                //Create an extended unicode sequence identifier and add it to
                //the current container
                $this->_currentItem = new Pattern\UnicodeCharClass(
                    $token->getIdentifier()
                );
                $this->_containersStack->top()->addChild($this->_currentItem);
            break;
            //Unicode character class identifier
            case Token::TYPE_UNICODE_CHAR_CLASS:
                //Create a unicode character class identifier and add it to
                //the current container
                $this->_currentItem = new Pattern\UnicodeCharClass(
                    rtrim(ltrim($token->getSubject(), "{"), "}"),
                    $token->getIdentifier() === "P"
                );
                $this->_containersStack->top()->addChild($this->_currentItem);
            break;
            //Hexadecimal character identifier
            case Token::TYPE_HEX_CHAR:
                //Create a hexadecimal character identifier and add it to the
                //current container
                $this->_currentItem = new Pattern\HexChar(
                    $token->getSubject()
                );
                $this->_containersStack->top()->addChild($this->_currentItem);
            break;
            //Octal character identifier
            case Token::TYPE_OCTAL_CHAR:
                //Create a octal character identifier and add it to the
                //current container
                $this->_currentItem = new Pattern\OctalChar(
                    $token->getSubject()
                );
                $this->_containersStack->top()->addChild($this->_currentItem);
            break;
            //Back reference identifier
            case Token::TYPE_BACK_REFERENCE:
                //Create a back reference identifier and add it to the
                //current container
                $this->_currentItem = new Pattern\BackReference(
                    $token->getSubject()
                );
                $this->_containersStack->top()->addChild($this->_currentItem);
            break;
            //Recursive pattern identifier
            case Token::TYPE_RECURSIVE_PATTERN:
                //Create a recursive pattern identifier and add it to the
                //current container
                $this->_currentItem = new Pattern\RecursivePattern(
                    $token->getSubject()
                );
                $this->_containersStack->top()->addChild($this->_currentItem);
            break;
            //Alternation identifier
            case Token::TYPE_ALTERNATION:
                if ($this->_containersStack->top() instanceof Pattern\Alternation) {
                    //If already inside an alternation group, create a new
                    //alternation
                    $currentAlternation = $this->_containersStack->pop();
                    $newAlternation = new Pattern\Alternation;
                    $currentAlternation->getParent()->addChild($newAlternation);
                    //Anchor the current alternation if required
                    if ($this->_pendingEndAnchor) {
                        $currentAlternation->setEndAnchored(true);
                    }
                }
                //If inside a conditional subpattern
                elseif ($this->_containersStack->top() instanceof Pattern\ConditionalThen) {
                    //Remove the "then" part and add the "else" part
                    $this->_containersStack->pop();
                    $newAlternation = new Pattern\ConditionalElse;
                    $this->_containersStack->top()->addChild($newAlternation);
                }
                else {
                    //Create a new alternation and move all the children from
                    //the current container to the new alternation
                    $currentContainer = $this->_containersStack->top();
                    $children = $currentContainer->getChildren();
                    //Create the alternation group structure
                    $alternationGroup = new Pattern\AlternationGroup;
                    $alternation = new Pattern\Alternation;
                    $alternation->addChildren($children);
                    $alternationGroup->addChild($alternation);
                    $currentContainer->addChild($alternationGroup);
                    $newAlternation = new Pattern\Alternation;
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
            case Token::TYPE_SUBPATTERN_START:
                //Create a new subpattern and add it to the container stack
                $subPattern = new Pattern\SubPattern;
                $this->_containersStack->top()->addChild($subPattern);
                $this->_containersStack->push($subPattern);
                $this->_currentItem = null;
            break;
            //Subpattern end character
            case Token::TYPE_SUBPATTERN_END:
                //Anchor the container if required
                if ($this->_pendingEndAnchor) {
                    $this->_containersStack->top()->setEndAnchored(true);
                }
                //If the current container is an alternation remove it first
                if ($this->_containersStack->top() instanceof Pattern\Alternation) {
                    $this->_containersStack->pop();
                }
                //Remove the subpattern from the container stack and make it
                //the current item
                $this->_currentItem = $this->_containersStack->pop();
                //If inside a conditional subpattern
                if ($this->_containersStack->top() instanceof Pattern\ConditionalSubPattern) {
                    //If the pattern was an assertion
                    if ($this->_currentItem instanceof Pattern\Assertion) {
                        //Add the "then" part
                        $then = new Pattern\ConditionalThen;
                        $this->_containersStack->top()->addChild($then);
                        $this->_containersStack->push($then);
                        $this->_currentItem = null;
                    }
                    //Othwerwise remove the conditional subpattern too
                    else {
                        $this->_currentItem = $this->_containersStack->pop();
                    }
                }
            break;
            //Subpattern non capturing flag and modifiers
            case Token::TYPE_SUBPATTERN_NON_CAPTURING:
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
            case Token::TYPE_SUBPATTERN_GROUP_MATCHES:
                //Enable subpattern group matches mode and make the subpattern
                //non capturing
                $this->_containersStack->top()
                     ->setGroupMatches(true)
                     ->setCapture(false);
            break;
            //Subpattern once only identifier
            case Token::TYPE_SUBPATTERN_ONCE_ONLY:
                //Enable once only mode and make the subpattern non capturing
                $this->_containersStack->top()
                     ->setOnceOnly(true)
                     ->setCapture(false);
            break;
            //Subpattern name
            case Token::TYPE_SUBPATTERN_NAME:
                //Set the subpattern name
                $this->_containersStack->top()->setName($token->getSubject());
            break;
            //Internal option identifier
            case Token::TYPE_INTERNAL_OPTION:
                //Create an internal option and add it to the current container
                $this->_containersStack->top()->addChild(
                    new Pattern\InternalOption($token->getSubject())
                );
                $this->_currentItem = null;
            break;
            //Assertion identifier
            case Token::TYPE_LOOKAHEAD_ASSERTION:
            case Token::TYPE_LOOKBEHIND_ASSERTION:
                $assertion = new Pattern\Assertion(
                    strpos($token->getIdentifier(), "<") === false,
                    strpos($token->getIdentifier(), "!") !== false
                );
                $this->_containersStack->top()->addChild($assertion);
                $this->_containersStack->push($assertion);
                $this->_currentItem = null;
            break;
            //Comment
            case Token::TYPE_COMMENT:
                //Create comment and add it to the current container
                $this->_containersStack->top()->addChild(
                    new Pattern\Comment($token->getSubject())
                );
            break;
            //Repetition identifier
            case Token::TYPE_REPETITION:
                $this->_handleRepetition($token);
            break;
            //Start anchor identifier
            case Token::TYPE_START_ANCHOR:
                //Ignore if the container already contains children
                if (!$this->_containersStack->top()->hasChildren()) {
                    $this->_containersStack->top()->setStartAnchored(true);
                }
                $this->_currentItem = null;
            break;
            //End anchor identifier
            case Token::TYPE_END_ANCHOR:
                //Set only the pending end anchor flag. It will be unset when
                //another token is emitted and it will be evaluated only when
                //a container is closed
                $this->_pendingEndAnchor = true;
            break;
            //Start char class identifier
            case Token::TYPE_CHAR_CLASS_START:
                //Create a new character class and add it to the container stack
                $charClass = new Pattern\CharClass;
                $this->_containersStack->top()->addChild($charClass);
                $this->_containersStack->push($charClass);
                $this->_currentItem = null;
            break;
            //Char class negation identifier
            case Token::TYPE_CHAR_CLASS_NEGATE:
                //Negate the current char class
                $this->_containersStack->top()->setNegate(true);
            break;
            //End char class identifier
            case Token::TYPE_CHAR_CLASS_END:
                //Remove the char class from the container stack and make it
                //the current item
                $this->_currentItem = $this->_containersStack->pop();
            break;
            //Posix char class identifier
            case Token::TYPE_POSIX_CHAR_CLASS:
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
                    new Pattern\PosixCharClass($class, $negate)
                );
            break;
            //Char class range identifier
            case Token::TYPE_CHAR_CLASS_RANGE:
                //Create a new character class range and add it to the
                //container stack, move the last inserted item to the range
                $currentChildren = $this->_containersStack->top()->getChildren();
                $range = new Pattern\CharClassRange;
                $range->addChild($currentChildren[count($currentChildren) - 1]);
                $this->_containersStack->top()->addChild($range);
                $this->_containersStack->push($range);
                $this->_currentItem = null;
            break;
            //Conditional subpattern identifier
            case Token::TYPE_CONDITIONAL_SUBPATTERN:
                //Create a new conditional subpattern and add it to the
                //container stack
                $subPattern = new Pattern\ConditionalSubPattern;
                $this->_containersStack->top()->addChild($subPattern);
                $this->_containersStack->push($subPattern);
                $this->_currentItem = null;
            break;
        }

        //Push the token in the tokens stack
        $this->_tokensStack->push($token);

        //Unset the pending end anchor flag if the token is not an end anchor
        if ($token->getType() !== Token::TYPE_END_ANCHOR) {
            $this->_pendingEndAnchor = false;
        }
        //If the current container is a character class range and the current
        //token is not the char class range identifier, remove the char class
        //range from the container stack
        if ($this->_containersStack->top() instanceof Pattern\CharClassRange &&
            $token->getType() !== Token::TYPE_CHAR_CLASS_RANGE) {
            $this->_containersStack->pop();
        }
    }
    
    /**
     * Handles a repetition token
     * 
     * @param Token $token Token
     * 
     * @return void
     */
    protected function _handleRepetition (Token $token)
    {
        //If there is no current item, throw exception
        if ($this->_currentItem === null) {
            throw new Exception\InvalidRepetition(
                "Nothing to repeat"
            );
        }

        //Repetitions are allowed only after certain tokens, so check the last
        //emitted token
        $lastToken = $this->_tokensStack->top();
        switch ($lastToken->getType())
        {
            //Handle lazy repetition
            case Token::TYPE_REPETITION:
                $prevLastToken = $this->_tokensStack->offsetGet(1);
                //if this token is "?" and follows a repetition token that
                //does not come after another repetition token set the lazy flag
                if ($token->getIdentifier() === "?" &&
                    $prevLastToken->getType() !== Token::TYPE_REPETITION) {
                    //Check if last repetition supports the lazy flag
                    $lastRepetition = $this->_currentItem->getRepetition();
                    if ($lastRepetition->supportsLazy()) {
                        $lastRepetition->setLazy(true);
                    }
                    return;
                } else {
                    throw new Exception\InvalidRepetition(
                        "Nothing to repeat"
                    );
                }
            break;
            //Tokens that can handle the repetition
            case Token::TYPE_NON_PRINTING_CHAR:
            case Token::TYPE_GENERIC_CHAR_TYPE:
            case Token::TYPE_CONTROL_CHAR:
            case Token::TYPE_EXT_UNICODE_SEQUENCE:
            case Token::TYPE_UNICODE_CHAR_CLASS:
            case Token::TYPE_HEX_CHAR:
            case Token::TYPE_DOT:
            case Token::TYPE_BYTE:
            case Token::TYPE_SUBPATTERN_END:
            case Token::TYPE_COMMENT:
            case Token::TYPE_OCTAL_CHAR:
            case Token::TYPE_BACK_REFERENCE:
            case Token::TYPE_CHAR_CLASS_END:
            case Token::TYPE_RECURSIVE_PATTERN:
            break;
            //When simple characters are grouped, repetition is valid only
            //for the last one, so it needs to be splitted so that the last
            //character belongs to a different object
            case Token::TYPE_CHAR:
                $chars = $this->_currentItem->getChar();
                if (strlen($chars) > 1) {
                    $this->_currentItem->setChar(substr($chars, 0, -1));
                    $this->_currentItem = new Pattern\Char(
                        $chars[strlen($chars) - 1]
                    );
                    $this->_containersStack->top()->addChild($this->_currentItem);
                }
            break;
            default:
                throw new Exception\InvalidRepetition(
                    "Repetition cannot be inserted at this point"
                );
            break;
        }

        //Get the right repetition class
        switch ($token->getIdentifier())
        {
            case "*":
                $repetition = new Pattern\Repetition\ZeroOrMore();
            break;
            case "+":
                $repetition = new Pattern\Repetition\OneOrMore();
            break;
            case "?":
                $repetition = new Pattern\Repetition\Optional();
            break;
            case "{":
                //Check if {}
                if (strpos($token->getSubject(), ",") === false) {
                    $repetition = new Pattern\Repetition\Number(
                        $token->getSubject()
                    );
                } else {
                    $limits = explode(",", $token->getSubject());
                    $repetition = new Pattern\Repetition\Range(
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
     * @return Pattern\Regex
     */
    public function getRegexContainer ()
    {
        return $this->_regexContainer;
    }
}
