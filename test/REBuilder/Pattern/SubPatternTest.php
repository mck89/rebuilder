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
	
	public function testGroupMatches ()
	{
		$regex = REBuilder::parse("/(?|a)*/");
		$this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
		$children = $regex->getChildren();
		$this->assertSame(1, count($children));
		$this->assertInstanceOf("REBuilder_Pattern_SubPattern", $children[0]);
		$this->assertSame(false, $children[0]->getCapture());
		$this->assertSame(true, $children[0]->getGroupMatches());
		$this->assertSame("/(?|a)*/", $regex->render());
	}
	
	public function testOnceOnly ()
	{
		$regex = REBuilder::parse("/(?>a)/");
		$this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
		$children = $regex->getChildren();
		$this->assertSame(1, count($children));
		$this->assertInstanceOf("REBuilder_Pattern_SubPattern", $children[0]);
		$this->assertSame(false, $children[0]->getCapture());
		$this->assertSame(true, $children[0]->getOnceOnly());
		$this->assertSame("/(?>a)/", $regex->render());
	}
	
	public function testNestedSubpatterns ()
	{
		$regex = REBuilder::parse("/(?:a(b))*/");
		$this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
		$children = $regex->getChildren();
		$this->assertSame(1, count($children));
		$this->assertInstanceOf("REBuilder_Pattern_SubPattern", $children[0]);
		$this->assertSame(false, $children[0]->getCapture());
		$children = $children[0]->getChildren();
		$this->assertSame(2, count($children));
		$this->assertInstanceOf("REBuilder_Pattern_Char", $children[0]);
		$this->assertInstanceOf("REBuilder_Pattern_SubPattern", $children[1]);
		$children = $children[1]->getChildren();
		$this->assertSame(1, count($children));
		$this->assertSame("/(?:a(b))*/", $regex->render());
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
	public function testInvaliSubpattern ($pattern)
	{
		REBuilder::parse("/$pattern/");
	}
	
	/**
     * @expectedException REBuilder_Exception_InvalidRepetition
     */
	public function testInvalidRepetition ()
	{
		REBuilder::parse("/(*)/");
	}
	
	/**
     * @expectedException REBuilder_Exception_Generic
     */
	public function testInvalidNameException ()
	{
		REBuilder::create()
				->addSubpattern()
					->setName("%");
	}
	
	/**
     * @expectedException REBuilder_Exception_InvalidModifier
     */
	public function testInvalidModifierException ()
	{
		REBuilder::create()
				->addSubpattern()
					->setModifiers("?");
	}
	
	public function combinedOptions () {
		return array(
			array(true, "name", "", false, false, "(?<name>a)"),
			array(false, "name", "", false, false, "(?:a)"),
			array(false, "", "i", false, false, "(?i:a)"),
			array(true, "", "i", false, false, "((?i:a))"),
			array(true, "", "i", true, false, "((?|(?i:a)))"),
			array(true, "", "", false, true, "((?>a))"),
			array(true, "", "i", true, true, "((?|(?>(?i:a))))"),
			array(false, "", "i", true, true, "(?|(?>(?i:a)))"),
			array(false, "", "", true, false, "(?|a)"),
			array(false, "", "", false, true, "(?>a)")
		);
	}
	
	/**
	 * @dataProvider combinedOptions
     */
	public function testCombinedOptions ($capture, $name, $modifiers,
										 $groupMatches, $onceOnly, $testCode)
	{
		$subpattern = new REBuilder_Pattern_SubPattern($capture, $name,
													   $modifiers, $groupMatches,
													   $onceOnly);
		$subpattern->addChild(new REBuilder_Pattern_Char("a"));
		$render = $subpattern->render();
		$this->assertSame($testCode, $render);
		$this->assertSame(1, preg_match("/" . $render . "/", "a"));
	}
	
	public function testObjectGeneration ()
	{
		$regex = REBuilder::create();
		$regex
				->addSubpattern(false)
					->addControlChar(";")
						->setRepetition("+", true);
		$this->assertSame("/(?:\c;+?)/", $regex . "");
	}
}
