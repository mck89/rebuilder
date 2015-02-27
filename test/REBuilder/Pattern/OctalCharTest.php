<?php
class OctalCharTest extends PHPUnit_Framework_TestCase
{
	public function testGeneratedStructure ()
	{
		$regex = REBuilder::parse('/\111/');
		$this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
		$children = $regex->getChildren();
		$this->assertSame(1, count($children));
		$this->assertInstanceOf("REBuilder_Pattern_OctalChar", $children[0]);
		$this->assertSame("111", $children[0]->getChar());
		$this->assertSame('/\111/', $regex->render());
	}
    
    public function testOctalCharWithLeadingZero ()
	{
		$regex = REBuilder::parse('/\01/');
		$this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
		$children = $regex->getChildren();
		$this->assertSame(1, count($children));
		$this->assertInstanceOf("REBuilder_Pattern_OctalChar", $children[0]);
		$this->assertSame("01", $children[0]->getChar());
		$this->assertSame('/\01/', $regex->render());
	}
    
    public function testOctalCharFollowedByNumber ()
	{
		$regex = REBuilder::parse('/\0111/');
		$this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
		$children = $regex->getChildren();
		$this->assertSame(2, count($children));
		$this->assertInstanceOf("REBuilder_Pattern_OctalChar", $children[0]);
		$this->assertSame("011", $children[0]->getChar());
		$this->assertInstanceOf("REBuilder_Pattern_Char", $children[1]);
		$this->assertSame("1", $children[1]->getChar());
		$this->assertSame('/\0111/', $regex->render());
	}
	
	public function invalidCharsProvider () {
		return array(
			array("2"),
			array("789"),
			array("11111"),
			array("")
		);
	}
	
	/**
	 * @dataProvider invalidCharsProvider
     * @expectedException REBuilder_Exception_Generic
     */
    public function testInvalidCharException ($char)
    {
		$octal = new REBuilder_Pattern_OctalChar($char);
        $octal->render();
    }
    
    /**
     * @expectedException REBuilder_Exception_Generic
     */
    public function testMissingCharException ()
    {
		$ref = new REBuilder_Pattern_OctalChar;
        $ref->render();
    }
	
	public function testObjectGeneration ()
	{
		$regex = REBuilder::create();
		$regex
				->addOctalChar("01");
		$this->assertSame('/\01/', $regex->render());
	}
}
