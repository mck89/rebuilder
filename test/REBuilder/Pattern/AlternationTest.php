<?php
class AlternationTest extends AbstractTest
{
	public function testGeneratedStructure ()
	{
		$regex = REBuilder::parse("/a|b/");
		$this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
		$children = $regex->getChildren();
		$this->assertSame(3, count($children));
		$this->assertInstanceOf("REBuilder_Pattern_Char", $children[0]);
		$this->assertInstanceOf("REBuilder_Pattern_Alternation", $children[1]);
		$this->assertInstanceOf("REBuilder_Pattern_Char", $children[2]);
		$this->assertSame("/a|b/", $regex->render());
	}
	
	/**
     * @expectedException REBuilder_Exception_InvalidRepetition
     */
    public function testNotSupportsReptetitionOnParse ()
    {
		REBuilder::parse("/a|*/");
    }
	
	/**
     * @expectedException REBuilder_Exception_InvalidRepetition
     */
    public function testNotSupportsReptetition ()
    {
		$alternation = new REBuilder_Pattern_Alternation;
		$alternation->setRepetition("*");
    }
	
	public function testObjectGeneration ()
	{
		$regex = REBuilder::create();
		$regex->addChildren(array(
			new REBuilder_Pattern_Char("a"),
			new REBuilder_Pattern_Alternation(),
			new REBuilder_Pattern_Char("b"),
		));
		$this->assertSame("/a|b/", $regex->render());
	}
}
