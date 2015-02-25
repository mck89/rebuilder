<?php
class AlternationTest extends AbstractTest
{
    public function testGeneratedStructure ()
    {
        $regex = REBuilder::parse("/a|b|c/");
        $this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(1, count($children));
        $this->assertInstanceOf("REBuilder_Pattern_AlternationGroup", $children[0]);
        $children = $children[0]->getChildren();
        $this->assertSame(3, count($children));
        $this->assertInstanceOf("REBuilder_Pattern_Alternation", $children[0]);
        $this->assertInstanceOf("REBuilder_Pattern_Alternation", $children[1]);
        $this->assertInstanceOf("REBuilder_Pattern_Alternation", $children[2]);
        $this->assertSame(1, count($children[0]->getChildren()));
        $this->assertSame(1, count($children[1]->getChildren()));
        $this->assertSame(1, count($children[2]->getChildren()));
        $this->assertSame("/(?:a|b|c)/", $regex->render());
    }

    public function testAlternationInSubpattern ()
    {
        $regex = REBuilder::parse("/(a|b)/");
        $this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(1, count($children));
        $this->assertInstanceOf("REBuilder_Pattern_SubPattern", $children[0]);
        $children = $children[0]->getChildren();
        $this->assertSame(1, count($children));
        $this->assertInstanceOf("REBuilder_Pattern_AlternationGroup", $children[0]);
        $children = $children[0]->getChildren();
        $this->assertSame(2, count($children));
        $this->assertInstanceOf("REBuilder_Pattern_Alternation", $children[0]);
        $this->assertInstanceOf("REBuilder_Pattern_Alternation", $children[1]);
        $this->assertSame(1, count($children[0]->getChildren()));
        $this->assertSame(1, count($children[1]->getChildren()));
        $this->assertSame("/((?:a|b))/", $regex->render());
    }

    /**
     * @expectedException REBuilder_Exception_InvalidRepetition
     */
    public function testNotSupportsReptetitionOnParse ()
    {
        REBuilder::parse("/a|*/");
    }

    /**
     * @expectedException REBuilder_Exception_InvalidRepetition
     */
    public function testNotSupportsReptetition ()
    {
        $alternation = new REBuilder_Pattern_Alternation;
        $alternation->setRepetition("*");
    }

    /**
     * @expectedException REBuilder_Exception_Generic
     */
    public function testAlternationNotInAlternationGroupException ()
    {
        $alternation = new REBuilder_Pattern_Alternation;
        $subpattern = new REBuilder_Pattern_SubPattern;
        $subpattern->addChild($alternation);
    }

    /**
     * @expectedException REBuilder_Exception_Generic
     */
    public function testAlternationGroupCanContainOnlyAlternations ()
    {
        $alternationGroup = new REBuilder_Pattern_AlternationGroup;
        $alternationGroup->addChild(new REBuilder_Pattern_Char);
    }

    public function testObjectGeneration ()
    {
        $regex = REBuilder::create();
        $regex->addAlternationGroup()
                    ->addAlternation()
                        ->addCharAndContinue("a")
                    ->getParent()
                    ->addAlternation()
                        ->addCharAndContinue("b")
                    ->getParent()
                    ->setRepetition("*");
        $this->assertSame("/(?:a|b)*/", $regex->render());
    }
}
