<?php
class ByteTest extends PHPUnit_Framework_TestCase
{
    public function testGeneratedStructure ()
    {
        $regex = REBuilder\REBuilder::parse("/\C*/i");
        $this->assertInstanceOf("REBuilder\Pattern\Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(1, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\Byte", $children[0]);
        $this->assertInstanceOf("REBuilder\Pattern\Repetition\ZeroOrMore", $children[0]->getRepetition());
        $this->assertSame("/\C*/i", $regex->render());
    }

    public function testObjectGeneration ()
    {
        $regex = REBuilder\REBuilder::create("x");
        $regex->addByte()->setRepetition("*");
        $this->assertSame("/\C*/x", $regex->render());
    }
}
