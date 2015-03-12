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

/**
 * Parser class
 * 
 * @author Marco Marchiò <marco.mm89@gmail.com>
 */
class Parser
{
    /**
     * Parser's tokenizer
     * 
     * @var Tokenizer 
     */
    protected $_tokenizer;

    /**
     * Parser's builder
     * 
     * @var Builder 
     */
    protected $_builder;

    /**
     * Constructor
     * 
     * @param string $regex The regular expression to parse
     */
    public function __construct ($regex)
    {
        $this->_builder = new Builder;
        $this->_tokenizer = new Tokenizer(
            $regex,
            array($this->_builder, "receiveToken")
        );
    }

    /**
     * Starts the parsing process
     * 
     * @return REBuilder\Pattern\Regex
     */
    public function parse ()
    {
        $this->_tokenizer->tokenize();
        return $this->_builder->getRegexContainer();
    }
}