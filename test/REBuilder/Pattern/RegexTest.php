<?php
class RegexTest extends AbstractTest
{
	public function testGeneratedStructure ()
	{
		$regex = REBuilder::parse("/a/i");
		
		$this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
		$this->assertSame("/", $regex->getStartDelimiter());
		$this->assertSame("/", $regex->getEndDelimiter());
		$this->assertSame("i", $regex->getModifiers());
		$this->assertSame("/a/i", $regex->render());
	}
	
	public function invalidDelimitersProvider () {
		return array(
			array("\\"),
			array("1"),
			array("a"),
			array(" ")
		);
	}
	
	/**
	 * @dataProvider invalidDelimitersProvider
     * @expectedException REBuilder_Exception_InvalidDelimiter
     */
    public function testInvalidDelimiterException ($delimiter)
    {
		new REBuilder_Pattern_Regex($delimiter);
    }
	
	public function testObjectGeneration ()
	{
		$regex = new REBuilder_Pattern_Regex("#", "i");
		$this->assertSame("##i", $regex->render());
	}
}
