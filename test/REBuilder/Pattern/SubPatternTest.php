<?php
class SubPatternTest extends AbstractTest
{
	public function testSimpleSubpattern ()
	{
		$regex = REBuilder::parse("/(a)*/");
		$this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
		$children = $regex->getChildren();
		$this->assertSame(1, count($children));
		$this->assertInstanceOf("REBuilder_Pattern_SubPattern", $children[0]);
		$this->assertSame(true, $children[0]->getCapture());
		$this->assertSame("", $children[0]->getName());
		$this->assertSame("", $children[0]->getModifiers());
		$children = $children[0]->getChildren();
		$this->assertInstanceOf("REBuilder_Pattern_Char", $children[0]);
		$this->assertSame("a", $children[0]->getChar());
		$this->assertSame("/(a)*/", $regex->render());
	}
	
	public function testNonCapturingSubpattern ()
	{
		$regex = REBuilder::parse("/(?:a)*/");
		$this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
		$children = $regex->getChildren();
		$this->assertSame(1, count($children));
		$this->assertInstanceOf("REBuilder_Pattern_SubPattern", $children[0]);
		$this->assertSame(false, $children[0]->getCapture());
		$this->assertSame("/(?:a)*/", $regex->render());
	}
	
	public function testNonCapturingSubpatternWithModifiers ()
	{
		$regex = REBuilder::parse("/(?i:a)*/");
		$this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
		$children = $regex->getChildren();
		$this->assertSame(1, count($children));
		$this->assertInstanceOf("REBuilder_Pattern_SubPattern", $children[0]);
		$this->assertSame(false, $children[0]->getCapture());
		$this->assertSame("i", $children[0]->getModifiers());
		$this->assertSame("/(?i:a)*/", $regex->render());
	}
	
	public function validNamedSubpatterns () {
		return array(
			array("P<name>"),
			array("<name>"),
			array("'name'")
		);
	}
	
	/**
	 * @dataProvider validNamedSubpatterns
     */
	public function testNamedSubpattern ($pattern)
	{
		$regex = REBuilder::parse("/(?" . $pattern . "a)*/");
		$this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
		$children = $regex->getChildren();
		$this->assertSame(1, count($children));
		$this->assertInstanceOf("REBuilder_Pattern_SubPattern", $children[0]);
		$this->assertSame(true, $children[0]->getCapture());
		$this->assertSame("name", $children[0]->getName());
		$this->assertSame("/(?<name>a)*/", $regex->render());
	}
	
	public function invalidSubpatterns () {
		return array(
			array("(a"),
			array("(?a)"),
			array("(?1:a)"),
			array("(?<invalid?>)"),
			array("(?<invalid)")
		);
	}
	/**
	 * @dataProvider invalidSubpatterns
     * @expectedException REBuilder_Exception_Generic
     */
	public function testErrorSubpattern ($pattern)
	{
		$regex = REBuilder::parse("/$pattern/");
	}
}
