<?php
class ByteTest extends AbstractTest
{
	public function testGeneratedStructure ()
	{
		$regex = REBuilder::parse("/\C*/i");
		$this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
		$children = $regex->getChildren();
		$this->assertSame(1, count($children));
		$this->assertInstanceOf("REBuilder_Pattern_Byte", $children[0]);
		$this->assertInstanceOf("REBuilder_Pattern_Repetition_ZeroOrMore", $children[0]->getRepetition());
		$this->assertSame("/\C*/i", $regex->render());
	}
	
	public function testObjectGeneration ()
	{
		$regex = REBuilder::create("x");
		$regex->addByte()->setRepetition("*");
		$this->assertSame("/\C*/x", $regex->render());
	}
}
