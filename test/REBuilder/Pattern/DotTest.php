<?php
class DotTest extends PHPUnit_Framework_TestCase
{
    public function testGeneratedStructure ()
    {
        $regex = REBuilder\REBuilder::parse("/.*/i");
        $this->assertInstanceOf("REBuilder\Pattern\Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(1, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\Dot", $children[0]);
        $this->assertInstanceOf("REBuilder\Pattern\Repetition\ZeroOrMore", $children[0]->getRepetition());
        $this->assertSame("/.*/i", $regex->render());
    }

    public function testObjectGeneration ()
    {
        $regex = REBuilder\REBuilder::create("m");
        $regex->addDot()->setRepetition("*");
        $this->assertSame("/.*/m", $regex->render());
    }
}
