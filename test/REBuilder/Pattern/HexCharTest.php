<?php
class HexCharTest extends AbstractTest
{
	public function testGeneratedStructure ()
	{
		$validCharacters = array("", "a", "1b");
		foreach ($validCharacters as $c) {
			$regex = REBuilder::parse("/\x$c/");
			$this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
			$children = $regex->getChildren();
			$this->assertSame(1, count($children));
			$this->assertInstanceOf("REBuilder_Pattern_HexChar", $children[0]);
			$this->assertSame($c, $children[0]->getChar());
			$this->assertSame("/\x$c/", $regex->render());
		}
	}
	
	/**
     * @expectedException REBuilder_Exception_Generic
     */
    public function testInvalidCharException ()
    {
		$char = new REBuilder_Pattern_HexChar();
		$char->setChar("z");
    }
	
	public function testObjectGeneration ()
	{
		$regex = REBuilder::create();
		$regex->addHexChar("aa");
		$this->assertSame("/\\xaa/", $regex->render());
	}
}
