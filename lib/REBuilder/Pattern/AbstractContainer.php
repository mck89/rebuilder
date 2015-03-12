<?php
/**
 * This file is part of the REBuilder package
 *
 * (c) Marco Marchiò <marco.mm89@gmail.com>
 *
 * For the full copyright and license information refer to the LICENSE file
 * distributed with this source code
 */

namespace REBuilder\Pattern;

/**
 * Abstract class for classes that can contain child patterns
 * 
 * @author Marco Marchiò <marco.mm89@gmail.com>
 * 
 * @abstract
 * 
 * @method Char addChar()
 *         addChar(string $char)
 *         Adds a new character to this container
 *         @see Char::__construct
 * 
 * @method AbstractContainer addCharAndContinue()
 *         addCharAndContinue(string $char)
 *         Same as addChar but it returns the current container
 *         @see Char::__construct
 * 
 * @method ControlChar addControlChar()
 *         addControlChar(string $char)
 *         Adds a new control character to this container
 *         @see ControlChar::__construct
 * 
 * @method AbstractContainer addControlCharAndContinue()
 *         addControlCharAndContinue(string $char)
 *         Same as addControlChar but it returns the current container
 *         @see ControlChar::__construct
 * 
 * @method HexChar addHexChar()
 *         addHexChar(string $char)
 *         Adds a new hexadecimal character to this container
 *         @see HexChar::__construct
 * 
 * @method AbstractContainer addHexCharAndContinue()
 *         addHexCharAndContinue(string $char)
 *         Same as addHexChar but it returns the current container
 *         @see HexChar::__construct
 * 
 * @method GenericCharType addGenericCharType()
 *         addGenericCharType(string $identifier)
 *         Adds a new generic character type to this container
 *         @see GenericCharType::__construct
 * 
 * @method AbstractContainer addGenericCharTypeAndContinue()
 *         addGenericCharTypeAndContinue(string $identifier)
 *         Same as addGenericCharType but it returns the current container
 *         @see GenericCharType::__construct
 * 
 * @method NonPrintingChar addNonPrintingChar()
 *         addNonPrintingChar(string $identifier)
 *         Adds a new non printing character to this container
 *         @see NonPrintingChar::__construct
 * 
 * @method AbstractContainer addNonPrintingCharAndContinue()
 *         addNonPrintingCharAndContinue(string $identifier)
 *         Same as addNonPrintingChar but it returns the current container
 *         @see NonPrintingChar::__construct
 * 
 * @method SimpleAssertion addSimpleAssertion()
 *         addSimpleAssertion(string $identifier)
 *         Adds a new simple assertion to this container
 *         @see SimpleAssertion::__construct
 * 
 * @method AbstractContainer addSimpleAssertionAndContinue()
 *         addSimpleAssertionAndContinue(string $identifier)
 *         Same as addSimpleAssertion but it returns the current container
 *         @see SimpleAssertion::__construct
 * 
 * @method Dot addDot()
 *         addDot()
 *         Adds a new dot to this container
 *         @see Dot::__construct
 * 
 * @method AbstractContainer addDotAndContinue()
 *         addDotAndContinue()
 *         Same as addDot but it returns the current container
 *         @see Dot::__construct
 * 
 * @method Byte addByte()
 *         addByte()
 *         Adds a new byte to this container
 *         @see Byte::__construct
 * 
 * @method AbstractContainer addByteAndContinue()
 *         addByteAndContinue()
 *         Same as addByte but it returns the current container
 *         @see Byte::__construct
 * 
 * @method UnicodeCharClass addUnicodeCharClass()
 *         addUnicodeCharClass(string $class, bool $negate)
 *         Adds a new unicode char class to this container
 *         @see UnicodeCharClass::__construct
 * 
 * @method AbstractContainer addUnicodeCharClassAndContinue()
 *         addUnicodeCharClassAndContinue(string $class, bool $negate)
 *         Same as addUnicodeCharClass but it returns the current container
 *         @see UnicodeCharClass::__construct
 * 
 * @method InternalOption addInternalOption()
 *         addInternalOption(string $modifiers)
 *         Adds a new internal option to this container
 *         @see InternalOption::__construct
 * 
 * @method AbstractContainer addInternalOptionAndContinue()
 *         addInternalOptionAndContinue(string $modifiers)
 *         Same as addInternalOption but it returns the current container
 *         @see InternalOption::__construct
 * 
 * @method Comment addComment()
 *         addComment(string $comment)
 *         Adds a new comment to this container
 *         @see Comment::__construct
 * 
 * @method AbstractContainer addCommentAndContinue()
 *         addCommentAndContinue(string $comment)
 *         Same as addComment but it returns the current container
 *         @see Comment::__construct
 * 
 * @method OctalChar addOctalChar()
 *         addOctalChar(string $char)
 *         Adds a new octal character to this container
 *         @see OctalChar::__construct
 * 
 * @method AbstractContainer addOctalCharAndContinue()
 *         addOctalCharAndContinue(string $char)
 *         Same as addOctalChar but it returns the current container
 *         @see OctalChar::__construct
 * 
 * @method BackReference addBackReference()
 *         addBackReference(string $reference)
 *         Adds a new back reference to this container
 *         @see BackReference::__construct
 * 
 * @method AbstractContainer addBackReferenceAndContinue()
 *         addBackReferenceAndContinue(string $reference)
 *         Same as addBackReference but it returns the current container
 *         @see BackReference::__construct
 * 
 * @method RecursivePattern addRecursivePattern()
 *         addRecursivePattern(string $reference)
 *         Adds a new recursive pattern to this container
 *         @see RecursivePattern::__construct
 * 
 * @method AbstractContainer addRecursivePatternAndContinue()
 *         addRecursivePatternAndContinue(string $reference)
 *         Same as addRecursivePattern but it returns the current container
 *         @see RecursivePattern::__construct
 * 
 * @method AlternationGroup addAlternationGroup()
 *         addAlternationGroup()
 *         Adds a new alternation group to this container
 *         @see AlternationGroup::__construct
 * 
 * @method AlternationGroup addAlternationGroupAndContinue()
 *         addAlternationGroupAndContinue()
 *         Same as addAlternationGroup but it returns the current container
 *         @see AlternationGroup::__construct
 * 
 * @method SubPattern addSubPattern()
 *         addSubPattern(bool $capture, string $name, string $modifiers,
 *                       bool $groupMatches, bool $onceOnly)
 *         Adds a new sub pattern to this container
 *         @see SubPattern::__construct
 * 
 * @method AbstractContainer addSubPatternAndContinue()
 *         addSubPatternAndContinue(bool $capture, string $name, string $modifiers,
 *                                  bool $groupMatches, bool $onceOnly)
 *         Same as addSubPattern but it returns the current container
 *         @see SubPattern::__construct
 * 
 * @method Assertion addAssertion()
 *         addAssertion(bool $lookahead, bool $negate)
 *         Adds a new assertion to this container
 *         @see Assertion::__construct
 * 
 * @method AbstractContainer addAssertionAndContinue()
 *         addAssertion(bool $lookahead, bool $negate)
 *         Same as addAssertion but it returns the current container
 *         @see Assertion::__construct
 * 
 * @method CharClass addCharClass()
 *         addCharClass(bool $negate)
 *         Adds a new character class to this container
 *         @see CharClass::__construct
 * 
 * @method AbstractContainer addCharClassAndContinue()
 *         addCharClassAndContinue(bool $negate)
 *         Same as addCharClass but it returns the current container
 *         @see CharClass::__construct
 * 
 * @method ConditionalSubPattern addConditionalSubPattern()
 *         addConditionalSubPattern()
 *         Adds a new conditional sub pattern to this container
 *         @see ConditionalSubPattern::__construct
 * 
 * @method AbstractContainer addConditionalSubPatternAndContinue()
 *         addConditionalSubPatternAndContinue(bool $negate)
 *         Same as addConditionalSubPattern but it returns the current container
 *         @see ConditionalSubPattern::__construct
 */
abstract class AbstractContainer extends AbstractPattern
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
     * @param AbstractPattern $child Child to add
     * 
     * @return AbstractContainer
     */
    public function addChild (AbstractPattern $child)
    {
        return $this->addChildAt($child);
    }

    /**
     * Adds a child to the class at the given index
     * 
     * @param AbstractPattern $child Child to add
     * @param int             $index Index
     * 
     * @return AbstractContainer
     */
    public function addChildAt (AbstractPattern $child, $index = null)
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
     * 
     * @return AbstractContainer
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
     * @param AbstractPattern $child Child to remove
     * 
     * @return AbstractContainer
     */
    public function removeChild (AbstractPattern $child)
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
     * 
     * @return AbstractContainer
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
     *                             start anchored
     * 
     * @return AbstractContainer
     * 
     * @throws \REBuilder\Exception\Generic
     */
    public function setStartAnchored ($startAnchored)
    {
        if (!$this->supportsAnchors()) {
            throw new \REBuilder\Exception\Generic(
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
     * 
     * @return AbstractContainer
     * 
     * @throws \REBuilder\Exception\Generic
     */
    public function setEndAnchored ($endAnchored)
    {
        if (!$this->supportsAnchors()) {
            throw new \REBuilder\Exception\Generic(
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
     * 
     * @return mixed
     * 
     * @throws \BadMethodCallException
     */
    function __call ($name, $arguments)
    {
        $error = true;
        //Add entity shortcut
        if (strpos($name, "add") === 0) {
            $className = str_replace("add", "", $name);
            $continue = strpos($name, "AndContinue") !== false;
            if ($continue) {
                $className = str_replace("AndContinue", "", $className);
            }
            $className = "REBuilder\\Pattern\\$className";
            if (class_exists($className)) {
                $abstractClasName = "REBuilder\Pattern\AbstractPattern";
                $class = new \ReflectionClass($className);
                if (!$class->isAbstract() &&
                    $class->isSubclassOf($abstractClasName)) {
                    $instance = $class->newInstanceArgs($arguments);
                    $this->addChild($instance);
                    $error = false;
                    $ret = $continue ? $this : $instance;
                }
            }
        }
        if ($error) {
            throw new \BadMethodCallException(
               "Undefined method: $name"
            );
        }
        return $ret;
    }
}
