<?php
class SimpleAssertionTest extends AbstractTest
{
	public function testGeneratedStructure ()
	{
		$regex = REBuilder::parse("/\bword\b/i");
		$this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
		$children = $regex->getChildren();
		$this->assertSame(3, count($children));
		$this->assertInstanceOf("REBuilder_Pattern_SimpleAssertion", $children[0]);
		$this->assertSame("b", $children[0]->getIdentifier());
		$this->assertInstanceOf("REBuilder_Pattern_Char", $children[1]);
		$this->assertSame("word", $children[1]->getChar());
		$this->assertInstanceOf("REBuilder_Pattern_SimpleAssertion", $children[2]);
		$this->assertSame("b", $children[2]->getIdentifier());
		$this->assertSame("/\bword\b/i", $regex->render());
	}
	
	public function validIdentifiersProvider () {
		return array(
			array("b"),
			array("B"),
			array("A"),
			array("Z"),
			array("z"),
			array("g"),
			array("Q"),
			array("E"),
			array("K")
		);
	}
	
	/**
	 * @dataProvider validIdentifiersProvider
     */
    public function testValidIdentifierException ($identifier)
    {
		$char = new REBuilder_Pattern_SimpleAssertion($identifier);
		$this->assertSame($identifier, $char->getIdentifier());
    }
	
	
	public function invalidIdentifiersProvider () {
		return array(
			array("a"),
			array("2"),
			array("."),
			array("@")
		);
	}
	
	/**
	 * @dataProvider invalidIdentifiersProvider
     * @expectedException REBuilder_Exception_Generic
     */
    public function testInvalidIdentifierException ($identifier)
    {
		$char = new REBuilder_Pattern_SimpleAssertion($identifier);
		$char->render();
    }
	
	public function testObjectGeneration ()
	{
		$regex = new REBuilder_Pattern_Regex("#", "i");
		$regex->addChild(new REBuilder_Pattern_SimpleAssertion("b"));
		$regex->addChild(new REBuilder_Pattern_Char("abc"));
		$this->assertSame("#\babc#i", $regex->render());
	}
	
	/**
     * @expectedException REBuilder_Exception_Generic
     */
	public function testRepetitionNotAllowedOnParse ()
	{
		REBuilder::parse("/\b*/i");
	}
	
	/**
     * @expectedException REBuilder_Exception_Generic
     */
	public function testRepetitionNotAllowedOnGeneration ()
	{
		$assertion = new REBuilder_Pattern_SimpleAssertion("b");
		$assertion->setRepetition("*");
	}
	
	/**
     * @expectedException REBuilder_Exception_Generic
     */
	public function testExceptionMissingIdentifier ()
	{
		$assertion = new REBuilder_Pattern_SimpleAssertion();
		$assertion->render();
	}
}
