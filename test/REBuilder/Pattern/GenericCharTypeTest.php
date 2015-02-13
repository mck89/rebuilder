<?php
class GenericCharTypeTest extends AbstractTest
{
	public function testGeneratedStructure ()
	{
		$regex = REBuilder::parse("/\d\w\s/i");
		$this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
		$children = $regex->getChildren();
		$this->assertSame(3, count($children));
		$this->assertInstanceOf("REBuilder_Pattern_GenericCharType", $children[0]);
		$this->assertSame("d", $children[0]->getIdentifier());
		$this->assertInstanceOf("REBuilder_Pattern_GenericCharType", $children[1]);
		$this->assertSame("w", $children[1]->getIdentifier());
		$this->assertInstanceOf("REBuilder_Pattern_GenericCharType", $children[2]);
		$this->assertSame("s", $children[2]->getIdentifier());
		$this->assertSame("/\d\w\s/i", $regex->render());
	}
	
	public function validIdentifiersProvider () {
		return array(
			array("d"),
			array("D"),
			array("h"),
			array("H"),
			array("s"),
			array("S"),
			array("v"),
			array("V"),
			array("W"),
			array("w")
		);
	}
	
	/**
	 * @dataProvider validIdentifiersProvider
     */
    public function testValidIdentifierException ($identifier)
    {
		$char = new REBuilder_Pattern_GenericCharType($identifier);
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
		$char = new REBuilder_Pattern_GenericCharType($identifier);
		$char->render();
    }
	
	public function testObjectGeneration ()
	{
		$regex = new REBuilder_Pattern_Regex("#", "i");
		$regex->addChild(new REBuilder_Pattern_GenericCharType("w"));
		$this->assertSame("#\w#i", $regex->render());
	}
	
	/**
     * @expectedException REBuilder_Exception_Generic
     */
	public function testExceptionMissingIdentifier ()
	{
		$assertion = new REBuilder_Pattern_GenericCharType();
		$assertion->render();
	}
}
