<?php
class OneOrMoreTest extends AbstractTest
{
	public function testGeneratedStructure ()
	{
		$regex = REBuilder::parse("/a+/");
		$this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
		$children = $regex->getChildren();
		$this->assertSame(1, count($children));
		$this->assertInstanceOf("REBuilder_Pattern_Repetition_OneOrMore", $children[0]->getRepetition());
		$this->assertSame(false, $children[0]->getRepetition()->getLazy());
	}
	
	public function testGeneratedStructureLazy ()
	{
		$regex = REBuilder::parse("/a+?/");
		$this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
		$children = $regex->getChildren();
		$this->assertSame(1, count($children));
		$this->assertInstanceOf("REBuilder_Pattern_Repetition_OneOrMore", $children[0]->getRepetition());
		$this->assertSame(true, $children[0]->getRepetition()->getLazy());
	}
}
