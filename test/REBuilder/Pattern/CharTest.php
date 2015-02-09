<?php
class CharTest extends AbstractTest
{
	public function testGeneratedStructure ()
	{
		$regex = REBuilder::parse("/a/i");
		$this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
		$children = $regex->getChildren();
		$this->assertSame(1, count($children));
		$this->assertInstanceOf("REBuilder_Pattern_Char", $children[0]);
		$this->assertSame("a", $children[0]->getChar());
		$this->assertSame(null, $children[0]->getRepetition());
		$this->assertSame("/a/i", $regex->render());
	}
	
	/**
     * @expectedException REBuilder_Exception_Generic
     */
    public function testInvalidCharException ()
    {
		$char = new REBuilder_Pattern_Char("");
		$char->render();
    }
	
	public function testObjectGeneration ()
	{
		$regex = new REBuilder_Pattern_Regex("#", "i");
		$regex->addChild(new REBuilder_Pattern_Char("abc"));
		$this->assertSame("#abc#i", $regex->render());
	}
	
	public function testRepetition ()
	{
		$regex = REBuilder::parse("/a*/i");
		$children = $regex->getChildren();
		$this->assertSame(1, count($children));
		$this->assertInstanceOf("REBuilder_Pattern_Char", $children[0]);
		$this->assertInstanceOf("REBuilder_Pattern_Repetition_ZeroOrMore", $children[0]->getRepetition());
		$this->assertSame("/a*/i", $regex->render());
	}
	
	public function testMultiCharWithRepetition ()
	{
		$regex = new REBuilder_Pattern_Regex("/", "i");
		$char = new REBuilder_Pattern_Char("abc");
		$char->setRepetition("*");
		$regex->addChild($char);
		$this->assertSame("/(?:abc)*/i", $regex->render());
	}
	
	public function testParseMultiChar ()
	{
		$regex = REBuilder::parse("/abc/i");
		$children = $regex->getChildren();
		$this->assertSame(1, count($children));
		$this->assertInstanceOf("REBuilder_Pattern_Char", $children[0]);
		$this->assertSame("abc", $children[0]->getChar());
		$this->assertSame("/abc/i", $regex->render());
	}
	
	public function testParseMultiCharWithRepetition ()
	{
		$regex = REBuilder::parse("/abc*/i");
		$children = $regex->getChildren();
		$this->assertSame(2, count($children));
		$this->assertInstanceOf("REBuilder_Pattern_Char", $children[0]);
		$this->assertSame("ab", $children[0]->getChar());
		$this->assertSame(null, $children[0]->getRepetition());
		$this->assertInstanceOf("REBuilder_Pattern_Char", $children[1]);
		$this->assertSame("c", $children[1]->getChar());
		$this->assertInstanceOf("REBuilder_Pattern_Repetition_ZeroOrMore", $children[1]->getRepetition());
		$this->assertSame("/abc*/i", $regex->render());
	}
}
