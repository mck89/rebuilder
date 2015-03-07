<?php
/**
 * Abstract class for classes that can contain child patterns
 * 
 * @author Marco Marchiò
 * @abstract
 * 
 * @method REBuilder_Pattern_Char addChar()
 *         addChar(string $char)
 *         Adds a new REBuilder_Pattern_Char class instance to this container
 *         @see REBuilder_Pattern_Char::__construct
 * 
 * @method REBuilder_Pattern_AbstractContainer addCharAndContinue()
 *         addCharAndContinue(string $char)
 *         Same as addChar but it returns the current container
 *         @see REBuilder_Pattern_Char::__construct
 * 
 * @method REBuilder_Pattern_ControlChar addControlChar()
 *         addControlChar(string $char)
 *         Adds a new REBuilder_Pattern_ControlChar class instance to this container
 *         @see REBuilder_Pattern_ControlChar::__construct
 * 
 * @method REBuilder_Pattern_AbstractContainer addControlCharAndContinue()
 *         addControlCharAndContinue(string $char)
 *         Same as addControlChar but it returns the current container
 *         @see REBuilder_Pattern_ControlChar::__construct
 * 
 * @method REBuilder_Pattern_HexChar addHexChar()
 *         addHexChar(string $char)
 *         Adds a new REBuilder_Pattern_HexChar class instance to this container
 *         @see REBuilder_Pattern_HexChar::__construct
 * 
 * @method REBuilder_Pattern_AbstractContainer addHexCharAndContinue()
 *         addHexCharAndContinue(string $char)
 *         Same as addHexChar but it returns the current container
 *         @see REBuilder_Pattern_HexChar::__construct
 * 
 * @method REBuilder_Pattern_GenericCharType addGenericCharType()
 *         addGenericCharType(string $identifier)
 *         Adds a new REBuilder_Pattern_GenericCharType class instance to this container
 *         @see REBuilder_Pattern_GenericCharType::__construct
 * 
 * @method REBuilder_Pattern_AbstractContainer addGenericCharTypeAndContinue()
 *         addGenericCharTypeAndContinue(string $identifier)
 *         Same as addGenericCharType but it returns the current container
 *         @see REBuilder_Pattern_GenericCharType::__construct
 * 
 * @method REBuilder_Pattern_NonPrintingChar addNonPrintingChar()
 *         addNonPrintingChar(string $identifier)
 *         Adds a new REBuilder_Pattern_NonPrintingChar class instance to this container
 *         @see REBuilder_Pattern_NonPrintingChar::__construct
 * 
 * @method REBuilder_Pattern_AbstractContainer addNonPrintingCharAndContinue()
 *         addNonPrintingCharAndContinue(string $identifier)
 *         Same as addNonPrintingChar but it returns the current container
 *         @see REBuilder_Pattern_NonPrintingChar::__construct
 * 
 * @method REBuilder_Pattern_SimpleAssertion addSimpleAssertion()
 *         addSimpleAssertion(string $identifier)
 *         Adds a new REBuilder_Pattern_SimpleAssertion class instance to this container
 *         @see REBuilder_Pattern_SimpleAssertion::__construct
 * 
 * @method REBuilder_Pattern_AbstractContainer addSimpleAssertionAndContinue()
 *         addSimpleAssertionAndContinue(string $identifier)
 *         Same as addSimpleAssertion but it returns the current container
 *         @see REBuilder_Pattern_SimpleAssertion::__construct
 * 
 * @method REBuilder_Pattern_Dot addDot()
 *         addDot()
 *         Adds a new REBuilder_Pattern_Dot class instance to this container
 *         @see REBuilder_Pattern_Dot::__construct
 * 
 * @method REBuilder_Pattern_AbstractContainer addDotAndContinue()
 *         addDotAndContinue()
 *         Same as addDot but it returns the current container
 *         @see REBuilder_Pattern_Dot::__construct
 * 
 * @method REBuilder_Pattern_Byte addByte()
 *         addByte()
 *         Adds a new REBuilder_Pattern_Byte class instance to this container
 *         @see REBuilder_Pattern_Byte::__construct
 * 
 * @method REBuilder_Pattern_AbstractContainer addByteAndContinue()
 *         addByteAndContinue()
 *         Same as addByte but it returns the current container
 *         @see REBuilder_Pattern_Byte::__construct
 * 
 * @method REBuilder_Pattern_UnicodeCharClass addUnicodeCharClass()
 *         addUnicodeCharClass(string $class, bool $negate)
 *         Adds a new REBuilder_Pattern_UnicodeCharClass class instance to this container
 *         @see REBuilder_Pattern_UnicodeCharClass::__construct
 * 
 * @method REBuilder_Pattern_AbstractContainer addUnicodeCharClassAndContinue()
 *         addUnicodeCharClassAndContinue(string $class, bool $negate)
 *         Same as addUnicodeCharClass but it returns the current container
 *         @see REBuilder_Pattern_UnicodeCharClass::__construct
 * 
 * @method REBuilder_Pattern_InternalOption addInternalOption()
 *         addInternalOption(string $modifiers)
 *         Adds a new REBuilder_Pattern_InternalOption class instance to this container
 *         @see REBuilder_Pattern_InternalOption::__construct
 * 
 * @method REBuilder_Pattern_AbstractContainer addInternalOptionAndContinue()
 *         addInternalOptionAndContinue(string $modifiers)
 *         Same as addInternalOption but it returns the current container
 *         @see REBuilder_Pattern_InternalOption::__construct
 * 
 * @method REBuilder_Pattern_Comment addComment()
 *         addComment(string $comment)
 *         Adds a new REBuilder_Pattern_Comment class instance to this container
 *         @see REBuilder_Pattern_Comment::__construct
 * 
 * @method REBuilder_Pattern_AbstractContainer addCommentAndContinue()
 *		   addCommentAndContinue(string $comment)
 *		   Same as addComment but it returns the current container
 *		   @see REBuilder_Pattern_Comment::__construct
 * 
 * @method REBuilder_Pattern_OctalChar addOctalChar()
 *		   addOctalChar(string $char)
 *		   Adds a new REBuilder_Pattern_OctalChar class instance to this container
 *		   @see REBuilder_Pattern_OctalChar::__construct
 * 
 * @method REBuilder_Pattern_AbstractContainer addOctalCharAndContinue()
 *		   addOctalCharAndContinue(string $char)
 *		   Same as addOctalChar but it returns the current container
 *		   @see REBuilder_Pattern_OctalChar::__construct
 * 
 * @method REBuilder_Pattern_BackReference addBackReference()
 *		   addBackReference(string $reference)
 *		   Adds a new REBuilder_Pattern_BackReference class instance to this container
 *		   @see REBuilder_Pattern_BackReference::__construct
 * 
 * @method REBuilder_Pattern_AbstractContainer addBackReferenceAndContinue()
 *		   addBackReferenceAndContinue(string $reference)
 *		   Same as addBackReference but it returns the current container
 *		   @see REBuilder_Pattern_BackReference::__construct
 * 
 * @method REBuilder_Pattern_RecursivePattern addRecursivePattern()
 *		   addRecursivePattern(string $reference)
 *		   Adds a new REBuilder_Pattern_RecursivePattern class instance to this container
 *		   @see REBuilder_Pattern_RecursivePattern::__construct
 * 
 * @method REBuilder_Pattern_AbstractContainer addRecursivePatternAndContinue()
 *		   addRecursivePatternAndContinue(string $reference)
 *		   Same as addRecursivePattern but it returns the current container
 *		   @see REBuilder_Pattern_RecursivePattern::__construct
 * 
 * @method REBuilder_Pattern_AlternationGroup addAlternationGroup()
 *         addAlternationGroup()
 *         Adds a new REBuilder_Pattern_AlternationGroup class instance to this container
 *         @see REBuilder_Pattern_AlternationGroup::__construct
 * 
 * @method REBuilder_Pattern_AlternationGroup addAlternationGroupAndContinue()
 *         addAlternationGroupAndContinue()
 *         Same as addAlternationGroup but it returns the current container
 *         @see REBuilder_Pattern_AlternationGroup::__construct
 * 
 * @method REBuilder_Pattern_SubPattern addSubPattern()
 *         addSubPattern(bool $capture, string $name, string $modifiers,
 *                       bool $groupMatches, bool $onceOnly)
 *         Adds a new REBuilder_Pattern_SubPattern class instance to this container
 *         @see REBuilder_Pattern_SubPattern::__construct
 * 
 * @method REBuilder_Pattern_AbstractContainer addSubPatternAndContinue()
 *         addSubPatternAndContinue(bool $capture, string $name, string $modifiers,
 *                                  bool $groupMatches, bool $onceOnly)
 *         Same as addSubPattern but it returns the current container
 *         @see REBuilder_Pattern_SubPattern::__construct
 * 
 * @method REBuilder_Pattern_Assertion addAssertion()
 *         addAssertion(bool $lookahead, bool $negate)
 *         Adds a new REBuilder_Pattern_Assertion class instance to this container
 *         @see REBuilder_Pattern_Assertion::__construct
 * 
 * @method REBuilder_Pattern_AbstractContainer addAssertionAndContinue()
 *         addAssertion(bool $lookahead, bool $negate)
 *         Same as addAssertion but it returns the current container
 *         @see REBuilder_Pattern_Assertion::__construct
 * 
 * @method REBuilder_Pattern_Assertion addCharClass()
 *         addCharClass(bool $negate)
 *         Adds a new REBuilder_Pattern_CharClass class instance to this container
 *         @see REBuilder_Pattern_CharClass::__construct
 * 
 * @method REBuilder_Pattern_AbstractContainer addCharClassAndContinue()
 *         addCharClassAndContinue(bool $negate)
 *         Same as addCharClass but it returns the current container
 *         @see REBuilder_Pattern_CharClass::__construct
 */
abstract class REBuilder_Pattern_AbstractContainer extends REBuilder_Pattern_Abstract
{
    /**
     * Class children
     *
     * @var array
     */
    protected $_children = array();
    
    /**
     * Flag that indicates if the container is anchored to the start
     *
     * @var bool
     */
    protected $_startAnchored = false;
    
    /**
     * Flag that indicates if the container is anchored to the end
     *
     * @var bool
     */
    protected $_endAnchored = false;
    
    /**
     * Flag that indicates if the container supports anchors
     *
     * @var bool
     */
    protected $_supportsAnchors = true;

    /**
     * Adds a child to the class
     * 
     * @param REBuilder_Pattern_Abstract $child Child to add
     * @return REBuilder_Pattern_AbstractContainer
     */
    public function addChild (REBuilder_Pattern_Abstract $child)
    {
        return $this->addChildAt($child);
    }
    
    /**
     * Adds a child to the class at the given index
     * 
     * @param REBuilder_Pattern_Abstract $child Child to add
     * @param int                        $index Index
     * @return REBuilder_Pattern_AbstractContainer
     */
    public function addChildAt (REBuilder_Pattern_Abstract $child, $index = null)
    {
        $child->setParent($this);
        if ($index === null) {
            $this->_children[] = $child;
        } elseif (isset($this->_children[$index])) {
            array_splice($this->_children, $index, 0, array($child));
        } else {
            $this->_children[$index] = $child;
            ksort($this->_children);
        }
        return $this;
    }

    /**
     * Adds an array of children to the class
     * 
     * @param array $children Array of children to add
     * @return REBuilder_Pattern_AbstractContainer
     */
    public function addChildren ($children)
    {
        foreach ($children as $child) {
            $this->addChild($child);
        }
        return $this;
    }

    /**
     * Removes a child from the class
     * 
     * @param REBuilder_Pattern_Abstract $child Child to remove
     * @return REBuilder_Pattern_AbstractContainer
     */
    public function removeChild (REBuilder_Pattern_Abstract $child)
    {
        $index = null;
        if ($this->hasChildren()) {
            foreach ($this->getChildren() as $k => $c) {
                if (spl_object_hash($c) === spl_object_hash($child)) {
                    $index = $k;
                    break;
                }
            }
        }
        return $index !== null ? $this->removeChildAt($index) : $this;
    }
    
    /**
     * Removes the child at the given index
     * 
     * @param int $index Index of the child to remove
     * @return REBuilder_Pattern_AbstractContainer
     */
    public function removeChildAt ($index)
    {
        if (isset($this->_children[$index])) {
            array_splice($this->_children, $index, 1);
        }
        return $this;
    }

    /**
     * Returns children array
     * 
     * @return array
     */
    public function getChildren ()
    {
        return $this->_children;
    }

    /**
     * Returns true if the class has at least one child
     * 
     * @return bool
     */
    public function hasChildren ()
    {
        return count($this->getChildren()) !== 0;
    }

    /**
     * Returns the string representation of class children
     * 
     * @return string
     */
    public function renderChildren ()
    {
        $ret = $this->getStartAnchored() ? "^" : "";
        if ($this->hasChildren()) {
            foreach ($this->getChildren() as $child) {
                $ret .= $child->render();
            }
        }
        if ($this->getEndAnchored()) {
            $ret .= "$";
        }
        return $ret;
    }
    
    /**
     * Returns true if the pattern supports anchors, otherwise false
     * 
     * @return bool
     */
    public function supportsAnchors ()
    {
        return $this->_supportsAnchors;
    }
    
    /**
     * Sets or unsets the start anchor
     * 
     * @param bool $startAnchored Boolean that indicates if the container is
     *                            start anchored
     * @return REBuilder_Pattern_AbstractContainer
     * @throws REBuilder_Exception_Generic
     */
    public function setStartAnchored ($startAnchored)
    {
        if (!$this->supportsAnchors()) {
            throw new REBuilder_Exception_Generic(
                $this->_getClassName() . " cannot handle anchors"
            );
        }
        $this->_startAnchored = (bool) $startAnchored;
        return $this;
    }
    
    /**
     * Returns true if the container is anchored to the start, otherwise false
     * 
     * @return bool
     */
    public function getStartAnchored ()
    {
        return $this->_startAnchored;
    }
    
    /**
     * Sets or unsets the end anchor
     * 
     * @param bool $endAnchored Boolean that indicates if the container is
     *                          end anchored
     * @return REBuilder_Pattern_AbstractContainer
     * @throws REBuilder_Exception_Generic
     */
    public function setEndAnchored ($endAnchored)
    {
        if (!$this->supportsAnchors()) {
            throw new REBuilder_Exception_Generic(
                $this->_getClassName() . " cannot handle anchors"
            );
        }
        $this->_endAnchored = (bool) $endAnchored;
        return $this;
    }
    
    /**
     * Returns true if the container is anchored to the end, otherwise false
     * 
     * @return bool
     */
    public function getEndAnchored ()
    {
        return $this->_endAnchored;
    }

    /**
     * Allows to call functions in the form of addClass and addClassAndContinue
     * 
     * @param string $name      Method name
     * @param array  $arguments Method arguments
     * @return mixed
     * @throws BadMethodCallException
     */
    function __call ($name, $arguments)
    {
        $error = true;
        //Add entity shortcut
        if (strpos($name, "add") === 0) {
            $name = str_replace("add", "", $name);
            $continue = strpos($name, "AndContinue") !== false;
            if ($continue) {
                $name = str_replace("AndContinue", "", $name);
            }
            $className = "REBuilder_Pattern_$name";
            if (class_exists($className)) {
                $class = new ReflectionClass($className);
                if (!$class->isAbstract() &&
                    $class->isSubclassOf("REBuilder_Pattern_Abstract")) {
                    $instance = $class->newInstanceArgs($arguments);
                    $this->addChild($instance);
                    $error = false;
                    $ret = $continue ? $this : $instance;
                }
            }
        }
        if ($error) {
            throw new BadMethodCallException(
               "Undefined method: $name"
            );
        }
        return $ret;
    }
}
