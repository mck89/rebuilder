<?php
class DotTest extends AbstractTest
{
	public function testGeneratedStructure ()
	{
		$regex = REBuilder::parse("/.*/i");
		$this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
		$children = $regex->getChildren();
		$this->assertSame(1, count($children));
		$this->assertInstanceOf("REBuilder_Pattern_Dot", $children[0]);
		$this->assertInstanceOf("REBuilder_Pattern_Repetition_ZeroOrMore", $children[0]->getRepetition());
		$this->assertSame("/.*/i", $regex->render());
	}
	
	public function testObjectGeneration ()
	{
		$regex = REBuilder::create("m");
		$regex->addDot()->setRepetition("*");
		$this->assertSame("/.*/m", $regex->render());
	}
}
