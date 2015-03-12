<?php
class RangeTest extends PHPUnit_Framework_TestCase
{
    public function testGeneratedStructure ()
    {
        $regex = REBuilder\REBuilder::parse("/a{1,20}/");
        $this->assertInstanceOf("REBuilder\Pattern\Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(1, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\Repetition\Range", $children[0]->getRepetition());
        $this->assertEquals(1, $children[0]->getRepetition()->getMin());
        $this->assertEquals(20, $children[0]->getRepetition()->getMax());
    }

    public function testGeneratedStructureNoEndLimit ()
    {
        $regex = REBuilder\REBuilder::parse("/a{1,}/");
        $this->assertInstanceOf("REBuilder\Pattern\Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(1, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\Repetition\Range", $children[0]->getRepetition());
        $this->assertEquals(1, $children[0]->getRepetition()->getMin());
        $this->assertSame(null, $children[0]->getRepetition()->getMax());
    }

    public function testInvalidNumberNotParsedAsRepetition ()
    {
        $regex = REBuilder\REBuilder::parse("/a{1,/");
        $this->assertInstanceOf("REBuilder\Pattern\Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(1, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\Char", $children[0]);
        $this->assertSame("a{1,", $children[0]->getChar());
    }
}
