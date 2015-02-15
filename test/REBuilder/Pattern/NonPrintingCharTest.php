<?php
class NonPrintingCharTest extends AbstractTest
{
	public function testGeneratedStructure ()
	{
		$regex = REBuilder::parse("/\a/i");
		$this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
		$children = $regex->getChildren();
		$this->assertSame(1, count($children));
		$this->assertInstanceOf("REBuilder_Pattern_NonPrintingChar", $children[0]);
		$this->assertSame("a", $children[0]->getIdentifier());
	}
	
	public function validIdentifiersProvider () {
		return array(
			array("a"),
			array("e"),
			array("f"),
			array("n"),
			array("r"),
			array("t")
		);
	}
	
	/**
	 * @dataProvider validIdentifiersProvider
     */
    public function testValidIdentifierException ($identifier)
    {
		$char = new REBuilder_Pattern_NonPrintingChar($identifier);
		$this->assertSame($identifier, $char->getIdentifier());
    }
	
	
	public function invalidIdentifiersProvider () {
		return array(
			array("A"),
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
		$char = new REBuilder_Pattern_NonPrintingChar($identifier);
		$char->render();
    }
	
	/**
     * @expectedException REBuilder_Exception_Generic
     */
	public function testExceptionMissingIdentifier ()
	{
		$assertion = new REBuilder_Pattern_NonPrintingChar();
		$assertion->render();
	}
	
	public function testObjectGeneration ()
	{
		$regex = REBuilder::create();
		$regex->addNonPrintingChar("a");
		$this->assertSame("/\a/", $regex->render());
	}
}
