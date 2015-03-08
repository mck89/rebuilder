<?php
/**
 * Represents a conditional subpattern.
 * 
 * @author Marco Marchiò
 * @link http://php.net/manual/en/regexp.reference.conditional.php
 * 
 * @method REBuilder_Pattern_ConditionalThen addConditionalThen()
 *         addConditionalThen()
 *         Adds a new REBuilder_Pattern_ConditionalThen class instance to this container
 *         @see REBuilder_Pattern_ConditionalThen::__construct
 * 
 * @method REBuilder_Pattern_ConditionalSubPattern addConditionalThenAndContinue()
 *         addConditionalThenAndContinue(string $char)
 *         Same as addConditionalThen but it returns the current container
 *         @see REBuilder_Pattern_ConditionalThen::__construct
 * 
 * @method REBuilder_Pattern_ConditionalElse addConditionalElse()
 *         addConditionalElse()
 *         Adds a new REBuilder_Pattern_ConditionalElse class instance to this container
 *         @see REBuilder_Pattern_ConditionalElse::__construct
 * 
 * @method REBuilder_Pattern_ConditionalSubPattern addConditionalElseAndContinue()
 *         addConditionalElseAndContinue(string $char)
 *         Same as addConditionalElse but it returns the current container
 *         @see REBuilder_Pattern_ConditionalElse::__construct
 * 
 * @method REBuilder_Pattern_Assertion getIf()
 *         getIf()
 *         Returns the "if" part of the conditional subpattern
 * 
 * @method REBuilder_Pattern_ConditionalSubPattern setIf()
 *         setIf(REBuilder_Pattern_Assertion $if)
 *         Sets the "if" part of the conditional subpattern
 * 
 * @method REBuilder_Pattern_ConditionalThen getThen()
 *         getThen()
 *         Returns the "then" part of the conditional subpattern
 * 
 * @method REBuilder_Pattern_ConditionalSubPattern setThen()
 *         setThen(REBuilder_Pattern_ConditionalThen $then)
 *         Sets the "then" part of the conditional subpattern
 * 
 * @method REBuilder_Pattern_ConditionalElse getElse()
 *         getElse()
 *         Returns the "else" part of the conditional subpattern
 * 
 * @method REBuilder_Pattern_ConditionalSubPattern setElse()
 *         setIf(REBuilder_Pattern_ConditionalElse $else)
 *         Sets the "else" part of the conditional subpattern
 */
class REBuilder_Pattern_ConditionalSubPattern extends REBuilder_Pattern_AbstractContainer
{
    /**
     * Defines the order of children based on their classes
     * 
     * @var array
     */
    protected $_childrenOrder = array(
        "if" => "REBuilder_Pattern_Assertion",
        "then" => "REBuilder_Pattern_ConditionalThen",
        "else" => "REBuilder_Pattern_ConditionalElse",
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
     * @return REBuilder_Pattern_Abstract|null
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
     * @param REBuilder_Pattern_Abstract $child Child to add
     * @param int                        $index Index
     * @return REBuilder_Pattern_CharClass
     * @throw REBuilder_Exception_Generic
     */
    public function addChildAt (REBuilder_Pattern_Abstract $child, $index = null)
    {
        $childClass = get_class($child);
        if (!in_array($childClass, $this->_childrenOrder)) {
            throw new REBuilder_Exception_Generic(
                $this->_getClassName($child) . " cannot be added to conditional subpatterns"
            );
        }
        $index = array_search($childClass, array_values($this->_childrenOrder));
        if (!$this->_safeAdd) {
            if (isset($this->_children[$index])) {
                throw new REBuilder_Exception_Generic(
                    $this->_getClassName($child) . " already present in conditional subpattern"
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
     * @return mixed
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
                    throw new REBuilder_Exception_Generic(
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
     */
    public function render ()
    {
        $if = $this->_getConditionalChild("if");
        $then = $this->_getConditionalChild("then");
        $else = $this->_getConditionalChild("else");
        
        if (!$if || !$then) {
            $missing = !$if ? "if" : "then";
            throw new REBuilder_Exception_Generic(
				"Missing '$missing' part in conditional subpattern"
			);
        }
        
        return "(?$if$then" .
               ($else ? "|$else" : "") .
               ")" .
               $this->_renderRepetition();
    }
}