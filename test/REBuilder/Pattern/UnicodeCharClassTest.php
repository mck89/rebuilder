<?php
class UnicodeCharClassTest extends AbstractTest
{
	public function testGeneratedStructure ()
	{
		$validClasses = array("C", "{Me}", "{Cyrillic}");
		$identifiers = array("p", "P");
		foreach ($validClasses as $c) {
			foreach ($identifiers as $identifier) {
				$negate = $identifier === "P";
				$regex = REBuilder::parse("/\\$identifier$c/");
				$this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
				$children = $regex->getChildren();
				$this->assertSame(1, count($children));
				$this->assertInstanceOf("REBuilder_Pattern_UnicodeCharClass", $children[0]);
				$this->assertSame(str_replace(array("{", "}"), "", $c), $children[0]->getClass());
				$this->assertSame($negate, $children[0]->getNegate());
				if ($c === "C") {
					$c = "{" . $c . "}";
				}
				$this->assertSame("/\\$identifier$c/", $regex->render());
			}
		}
	}
	
	/**
     * @expectedException REBuilder_Exception_Generic
     */
    public function testInvalidCharException ()
    {
		$char = new REBuilder_Pattern_UnicodeCharClass();
		$char->setClass("invalid");
    }
	
	public function invalidRegexProvider () {
		return array(
			array("/\p/"),
			array("/\p{C"),
			array("\p{Invalid}")
		);
	}
	
	/**
	 * @dataProvider invalidRegexProvider
     * @expectedException REBuilder_Exception_Generic
     */
    public function testInvalidRegexException ()
    {
		$regex = REBuilder::parse("/\p/");
    }
	
	public function testObjectGeneration ()
	{
		$regex = new REBuilder_Pattern_Regex("#", "i");
		$regex->addChild(new REBuilder_Pattern_UnicodeCharClass("C"));
		$this->assertSame("#\p{C}#i", $regex->render());
	}
}
