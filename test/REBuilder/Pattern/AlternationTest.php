<?php
class AlternationTest extends PHPUnit_Framework_TestCase
{
    public function testGeneratedStructure ()
    {
        $regex = REBuilder\REBuilder::parse("/a|b|c/");
        $this->assertInstanceOf("REBuilder\Pattern\Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(1, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\AlternationGroup", $children[0]);
        $children = $children[0]->getChildren();
        $this->assertSame(3, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\Alternation", $children[0]);
        $this->assertInstanceOf("REBuilder\Pattern\Alternation", $children[1]);
        $this->assertInstanceOf("REBuilder\Pattern\Alternation", $children[2]);
        $this->assertSame(1, count($children[0]->getChildren()));
        $this->assertSame(1, count($children[1]->getChildren()));
        $this->assertSame(1, count($children[2]->getChildren()));
        $this->assertSame("/(?:a|b|c)/", $regex->render());
    }

    public function testAlternationInSubpattern ()
    {
        $regex = REBuilder\REBuilder::parse("/(a|b)/");
        $this->assertInstanceOf("REBuilder\Pattern\Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(1, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\SubPattern", $children[0]);
        $children = $children[0]->getChildren();
        $this->assertSame(1, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\AlternationGroup", $children[0]);
        $children = $children[0]->getChildren();
        $this->assertSame(2, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\Alternation", $children[0]);
        $this->assertInstanceOf("REBuilder\Pattern\Alternation", $children[1]);
        $this->assertSame(1, count($children[0]->getChildren()));
        $this->assertSame(1, count($children[1]->getChildren()));
        $this->assertSame("/((?:a|b))/", $regex->render());
    }
    
    public function testAlternationWithAnchors ()
    {
        $regex = REBuilder\REBuilder::parse("/^a|b$|^c$/");
        $this->assertInstanceOf("REBuilder\Pattern\Regex", $regex);
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
     * @expectedException REBuilder\Exception\Generic
     */
    public function testAlternationGroupNotSupportsAnchors ($fn)
    {
        $group = new REBuilder\Pattern\AlternationGroup();
        $group->$fn(true);
    }

    /**
     * @expectedException REBuilder\Exception\InvalidRepetition
     */
    public function testNotSupportsReptetitionOnParse ()
    {
        REBuilder\REBuilder::parse("/a|*/");
    }

    /**
     * @expectedException REBuilder\Exception\InvalidRepetition
     */
    public function testNotSupportsReptetition ()
    {
        $alternation = new REBuilder\Pattern\Alternation;
        $alternation->setRepetition("*");
    }

    /**
     * @expectedException REBuilder\Exception\Generic
     */
    public function testAlternationNotInAlternationGroupException ()
    {
        $alternation = new REBuilder\Pattern\Alternation;
        $subpattern = new REBuilder\Pattern\SubPattern;
        $subpattern->addChild($alternation);
    }

    /**
     * @expectedException REBuilder\Exception\Generic
     */
    public function testAlternationGroupCanContainOnlyAlternations ()
    {
        $alternationGroup = new REBuilder\Pattern\AlternationGroup;
        $alternationGroup->addChild(new REBuilder\Pattern\Char);
    }

    public function testObjectGeneration ()
    {
        $regex = REBuilder\REBuilder::create();
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
