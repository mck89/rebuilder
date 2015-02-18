<?php
class ZeroOrMoreTest extends AbstractTest
{
	public function testGeneratedStructure ()
	{
		$regex = REBuilder::parse("/a*/");
		$this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
		$children = $regex->getChildren();
		$this->assertSame(1, count($children));
		$this->assertInstanceOf("REBuilder_Pattern_Repetition_ZeroOrMore", $children[0]->getRepetition());
		$this->assertSame(false, $children[0]->getRepetition()->getLazy());
	}
	
	public function testGeneratedStructureLazy ()
	{
		$regex = REBuilder::parse("/a*?/");
		$this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
		$children = $regex->getChildren();
		$this->assertSame(1, count($children));
		$this->assertInstanceOf("REBuilder_Pattern_Repetition_ZeroOrMore", $children[0]->getRepetition());
		$this->assertSame(true, $children[0]->getRepetition()->getLazy());
		$this->assertSame("*?", $children[0]->getRepetition() . "");
	}
	
	public function invalidRepetitions ()
	{
		return array(
			array(".*??"),
			array("?"),
			array(".*+")
		);
	}
	
	/**
	 * @dataProvider invalidRepetitions
     * @expectedException REBuilder_Exception_InvalidRepetition
     */
	public function testInvalidRepetition ($pattern)
	{
		REBuilder::parse("/$pattern/");
	}
	
	/**
     * @expectedException REBuilder_Exception_InvalidRepetition
     */
	public function testInvalidRepetitionWithFunction ()
	{
		$char = new REBuilder_Pattern_Char();
		$char->setRepetition("a");
	}
}
