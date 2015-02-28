<?php
class AlternationTest extends PHPUnit_Framework_TestCase
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
    
    public function testAlternationWithAnchors ()
    {
        $regex = REBuilder::parse("/^a|b$|^c$/");
        $this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(1, count($children));
        $children = $children[0]->getChildren();
        $this->assertSame(3, count($children));
        $this->assertSame(true, $children[0]->getStartAnchored());
        $this->assertSame(false, $children[0]->getEndAnchored());
        $this->assertSame(false, $children[1]->getStartAnchored());
        $this->assertSame(true, $children[1]->getEndAnchored());
        $this->assertSame(true, $children[2]->getStartAnchored());
        $this->assertSame(true, $children[2]->getEndAnchored());
        $this->assertSame("/(?:^a|b$|^c$)/", $regex->render());
    }
    
    public function invalidAnchorMethods ()
    {
        return array(
            array("setStartAnchored"),
            array("setEndAnchored"),
        );
    }
    
    /**
     * @dataProvider invalidAnchorMethods
     * @expectedException REBuilder_Exception_Generic
     */
    public function testAlternationGroupNotSupportsAnchors ($fn)
    {
        $group = new REBuilder_Pattern_AlternationGroup();
        $group->$fn(true);
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
