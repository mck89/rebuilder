<?php
/**
 * This class represents the main regex container and will contain the entire
 * regex structure
 * 
 * @author Marco Marchiò
 */
class REBuilder_Pattern_Regex extends REBuilder_Pattern_AbstractContainer
{
    /**
     * Start delimiter
     * 
     * @var string
     */
    protected $_startDelimiter;

    /**
     * End delimiter
     * 
     * @var string
     */
    protected $_endDelimiter;

    /**
     * Modifiers
     * 
     * @var string
     */
    protected $_modifiers;

    /**
     * Flag that identifies if the pattern supports repetitions
     * 
     * @var bool
     */
    protected $_supportsRepetition = false;

    /**
     * Constructor
     * 
     * @param string $modifiers Regex modifiers
     * @param string $delimiter Regex delimiter
     */
    public function __construct ($modifiers = "", $delimiter = "/")
    {
        $this->setModifiers($modifiers);
        $this->setDelimiter($delimiter);
    }

    /**
     * Sets the regex delimiter
     * 
     * @param string $delimiter Regex delimiter
     * @return REBuilder_Pattern_Regex
     * @throws REBuilder_Exception_InvalidDelimiter
     */
    public function setDelimiter ($delimiter)
    {
        if (!REBuilder_Parser_Rules::validateDelimiter($delimiter)) {
            throw new REBuilder_Exception_InvalidDelimiter(
                "Invalid delimiter '$delimiter'"
            );
        }
        $this->_startDelimiter = $delimiter;
        $this->_endDelimiter = REBuilder_Parser_Rules::getEndDelimiter($delimiter);
        return $this;
    }

    /**
     * Returns the regex start delimiter
     * 
     * @return string
     */
    public function getStartDelimiter ()
    {
        return $this->_startDelimiter;
    }

    /**
     * Returns the regex end delimiter
     * 
     * @return string
     */
    public function getEndDelimiter ()
    {
        return $this->_endDelimiter;
    }

    /**
     * Sets regex modifiers
     * 
     * @param string $modifiers Regex modifiers
     * @return REBuilder_Pattern_Regex
     * @throws REBuilder_Exception_InvalidModifier
     */
    public function setModifiers ($modifiers)
    {
        if (!REBuilder_Parser_Rules::validateModifiers($modifiers, $wrongModifier)) {
            throw new REBuilder_Exception_InvalidModifier("Invalid modifier '$wrongModifier'");
        }
        $this->_modifiers = $modifiers;
        return $this;
    }

    /**
     * Returns the regex modifiers
     * 
     * @return string
     */
    public function getModifiers ()
    {
        return $this->_modifiers;
    }

    /**
     * Quotes the given string using current configurations
     * 
     * @return string
     */
    public function quote ($str)
    {
        return preg_quote($str, $this->_startDelimiter);
    }
    
    /**
     * Test if the regex matches the given string
     * 
     * @param string $str    Test string
     * @return bool
     */
    public function test ($str)
    {
        return preg_match($this->render(), $str) === 1;
    }
    
    /**
     * Executes the regex on the given string and return the matches array or
     * null if the string does not match
     * 
     * @param string $str           The string to match
     * @param bool   $setOrder      True to group the matches in sets
     * @param bool   $captureOffset True to capture matches offset too
     * @return array|null
     */
    public function exec ($str, $setOrder = false, $captureOffset = false)
    {
        if ($setOrder) {
            $flags = PREG_SET_ORDER;
        } else {
            $flags = PREG_PATTERN_ORDER;
        }
        if ($captureOffset) {
            $flags = $flags | PREG_OFFSET_CAPTURE;
        }
        if (preg_match_all($this->render(), $str, $matches, $flags)) {
            return $matches;
        }
        return null;
    }
    
    /**
     * Filters an array by removing values that do not match the regex
     * 
     * @param array $array  Array to filter
     * @param bool  $invert If true the behaviour is inverted and this function
     *                      filters out values that match the regex
     * @return array
     */
    public function grep ($array, $invert = false)
    {
        $flags = $invert ? PREG_GREP_INVERT : 0;
        return preg_grep($this->render(), $array, $flags);
    }
    
    /**
     * Splits the given string using the regex
     * 
     * @param string $str           The string to split
     * @param int    $limit         Maximum number of substrings to return
     * @param bool   $noEmpty       If true only non empty substrings are
     *                              returned
     * @param bool   $captureDelim  If true also capturing pattern in the
     *                              delimiter are returned
     * @param bool   $captureOffset If true the offset of each substring is
     *                              returned
     * @return array
     */
    public function split ($str, $limit = null, $noEmpty = false,
                           $captureDelim = false, $captureOffset = false)
    {
        $flags = 0;
        if ($noEmpty) {
            $flags = $flags | PREG_SPLIT_NO_EMPTY;
        }
        if ($captureDelim) {
            $flags = $flags | PREG_SPLIT_DELIM_CAPTURE;
        }
        if ($captureOffset) {
            $flags = $flags | PREG_SPLIT_OFFSET_CAPTURE;
        }
        if ($limit === null) {
            $limit = -1;
        }
        return preg_split($this->render(), $str, $limit, $flags);
    }

    /**
     * Returns the string representation of the class
     * 
     * @return string
     */
    public function render ()
    {
        return $this->_startDelimiter .
               $this->renderChildren() .
               $this->_endDelimiter .
               $this->_modifiers;
    }
}