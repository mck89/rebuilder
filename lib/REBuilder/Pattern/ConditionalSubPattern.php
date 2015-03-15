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
 * Represents a conditional subpattern.
 * 
 * @author Marco Marchiò <marco.mm89@gmail.com>
 * 
 * @link http://php.net/manual/en/regexp.reference.conditional.php
 * 
 * @method ConditionalThen addConditionalThen()
 *         addConditionalThen()
 *         Adds a new "then" part to the subpattern
 *         {@see REBuilder\Pattern\ConditionalThen::__construct}
 * 
 * @method ConditionalSubPattern addConditionalThenAndContinue()
 *         addConditionalThenAndContinue(string $char)
 *         Same as addConditionalThen but it returns the current container
 *         {@see REBuilder\Pattern\ConditionalThen::__construct}
 * 
 * @method ConditionalElse addConditionalElse()
 *         addConditionalElse()
 *         Adds a new "else" part to the subpattern
 *         {@see REBuilder\Pattern\ConditionalElse::__construct}
 * 
 * @method ConditionalSubPattern addConditionalElseAndContinue()
 *         addConditionalElseAndContinue(string $char)
 *         Same as addConditionalElse but it returns the current container
 *         {@see REBuilder\Pattern\ConditionalElse::__construct}
 * 
 * @method Assertion getIf()
 *         getIf()
 *         Returns the "if" part of the conditional subpattern
 * 
 * @method ConditionalSubPattern setIf()
 *         setIf(Assertion $if)
 *         Sets the "if" part of the conditional subpattern
 * 
 * @method ConditionalThen getThen()
 *         getThen()
 *         Returns the "then" part of the conditional subpattern
 * 
 * @method ConditionalSubPattern setThen()
 *         setThen(ConditionalThen $then)
 *         Sets the "then" part of the conditional subpattern
 * 
 * @method ConditionalElse getElse()
 *         getElse()
 *         Returns the "else" part of the conditional subpattern
 * 
 * @method ConditionalSubPattern setElse()
 *         setIf(ConditionalElse $else)
 *         Sets the "else" part of the conditional subpattern
 */
class ConditionalSubPattern extends AbstractContainer
{
    /**
     * Defines the order of children based on their classes
     * 
     * @var array
     */
    protected $_childrenOrder = array(
        "if" => "REBuilder\Pattern\Assertion",
        "then" => "REBuilder\Pattern\ConditionalThen",
        "else" => "REBuilder\Pattern\ConditionalElse",
    );

    /**
     * Flag that indicates if it is safe to add the child without checking its
     * index
     * 
     * @var bool
     */
    protected $_safeAdd = false;

    /**
     * Returns the children that corresponds to the given type or null
     * if missing
     * 
     * @param string $type Child type
     * 
     * @return AbstractPattern|null
     */
    protected function _getConditionalChild ($type)
    {
        $searchClass = $this->_childrenOrder[$type];
        $index = array_search($searchClass, array_values($this->_childrenOrder));
        return isset($this->_children[$index]) ?
               $this->_children[$index] :
               null;
    }

    /**
     * Adds a child to the class at the given index
     * 
     * @param AbstractPattern $child Child to add
     * @param int             $index Index
     * 
     * @return CharClass
     * 
     * @throws \REBuilder\Exception\Generic
     */
    public function addChildAt (AbstractPattern $child, $index = null)
    {
        $childClass = get_class($child);
        if (!in_array($childClass, $this->_childrenOrder)) {
            throw new \REBuilder\Exception\Generic(
                $this->_getClassName($child) .
                " cannot be added to conditional subpatterns"
            );
        }
        $index = array_search($childClass, array_values($this->_childrenOrder));
        if (!$this->_safeAdd) {
            if (isset($this->_children[$index])) {
                throw new \REBuilder\Exception\Generic(
                    $this->_getClassName($child) .
                    " already present in conditional subpattern"
                );
            }
        }
        $this->_safeAdd = false;
        return parent::addChildAt($child, $index);
    }

    /**
     * Allows to call setIf, getIf, setThen, getThen, setElse and getElse
     * functions
     * 
     * @param string $name      Method name
     * @param array  $arguments Method arguments
     * 
     * @return mixed
     * 
     * @throws \REBuilder\Exception\Generic
     */
    function __call ($name, $arguments)
    {
        if (preg_match("/^(get|set)(If|Then|Else)$/", $name, $match)) {
            $type = strtolower($match[2]);
            $currentChild = $this->_getConditionalChild($type);
            if ($match[1] === "get") {
                return $currentChild;
            } else {
                $testClass = $this->_childrenOrder[$type];
                if (!count($arguments) ||
                    !$arguments[0] instanceof $testClass) {
                    throw new \REBuilder\Exception\Generic(
                        "$name requires a " . $this->_getClassName($testClass)
                    );
                }
                if ($currentChild) {
                    $this->_safeAdd = true;
                    $this->removeChild($currentChild);
                }
                return $this->addChildAt($arguments[0]);
            }
        }
        return parent::__call($name, $arguments);
    }

    /**
     * Returns the string representation of the class
     * 
     * @return string
     * 
     * @throws \REBuilder\Exception\Generic
     */
    public function render ()
    {
        $if = $this->_getConditionalChild("if");
        $then = $this->_getConditionalChild("then");
        $else = $this->_getConditionalChild("else");

        if (!$if || !$then) {
            $missing = !$if ? "if" : "then";
            throw new \REBuilder\Exception\Generic(
                "Missing '$missing' part in conditional subpattern"
            );
        }

        return "(?$if$then" .
               ($else ? "|$else" : "") .
               ")" .
               $this->_renderRepetition();
    }
}