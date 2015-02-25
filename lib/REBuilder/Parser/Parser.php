<?php
/**
 * Parser class
 * 
 * @author Marco Marchiò
 */
class REBuilder_Parser_Parser
{
    /**
     * Parser's tokenizer
     * 
     * @var REBuilder_Parser_Tokenizer 
     */
    protected $_tokenizer;

    /**
     * Parser's builder
     * 
     * @var REBuilder_Parser_Builder 
     */
    protected $_builder;

    /**
     * Constructor
     * 
     * @param string $regex The regular expression to parse
     */
    public function __construct ($regex)
    {
        $this->_builder = new REBuilder_Parser_Builder;
        $this->_tokenizer = new REBuilder_Parser_Tokenizer(
            $regex,
            array($this->_builder, "receiveToken")
        );
    }

    /**
     * Starts the parsing process
     * 
     * @return REBuilder_Pattern_Regex
     */
    public function parse ()
    {
        $this->_tokenizer->tokenize();
        return $this->_builder->getRegexContainer();
    }
}