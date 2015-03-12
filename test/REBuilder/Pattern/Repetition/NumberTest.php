<?php
class NumberTest extends PHPUnit_Framework_TestCase
{
    public function testGeneratedStructure ()
    {
        $regex = REBuilder\REBuilder::parse("/a{1}/");
        $this->assertInstanceOf("REBuilder\Pattern\Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(1, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\Repetition\Number", $children[0]->getRepetition());
        $this->assertEquals(1, $children[0]->getRepetition()->getNumber());
    }

    public function testInvalidNumberNotParsedAsRepetition ()
    {
        $regex = REBuilder\REBuilder::parse("/a{b}/");
        $this->assertInstanceOf("REBuilder\Pattern\Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(1, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\Char", $children[0]);
        $this->assertSame("a{b}", $children[0]->getChar());
    }
}
