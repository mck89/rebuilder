<?php
class ControlCharTest extends AbstractTest
{
	public function testGeneratedStructure ()
	{
		$regex = REBuilder::parse("/\c;+/i");
		$this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
		$children = $regex->getChildren();
		$this->assertSame(1, count($children));
		$this->assertInstanceOf("REBuilder_Pattern_ControlChar", $children[0]);
		$this->assertSame(";", $children[0]->getChar());
		$this->assertInstanceOf("REBuilder_Pattern_Repetition_OneOrMore", $children[0]->getRepetition());
		$this->assertSame("/\c;+/i", $regex->render());
	}
	
	/**
     * @expectedException REBuilder_Exception_Generic
     */
    public function testInvalidCharException ()
    {
		$char = new REBuilder_Pattern_ControlChar();
		$char->setChar("abc");
    }
	
	/**
     * @expectedException REBuilder_Exception_Generic
     */
    public function testInvalidEmptyCharException ()
    {
		$char = new REBuilder_Pattern_ControlChar();
		$char->setChar("");
    }
	
	/**
     * @expectedException REBuilder_Exception_Generic
     */
    public function testInvalidRegexException ()
    {
		$regex = REBuilder::parse("/\c/");
    }
	
	public function testEscapedChar ()
	{
		$regex = REBuilder::parse("/\c\+/i");
		$this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
		$children = $regex->getChildren();
		$this->assertSame(1, count($children));
		$this->assertInstanceOf("REBuilder_Pattern_ControlChar", $children[0]);
		$this->assertSame("+", $children[0]->getChar());
		$this->assertSame(null, $children[0]->getRepetition());
		$this->assertSame("/\c\+/i", $regex->render());
	}
	
	public function testObjectGeneration ()
	{
		$regex = REBuilder::create();
		$regex->addControlChar("a");
		$this->assertSame("/\ca/", $regex->render());
	}
}
