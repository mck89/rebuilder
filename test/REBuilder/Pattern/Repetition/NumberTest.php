<?php
class NumberTest extends AbstractTest
{
    public function testGeneratedStructure ()
    {
        $regex = REBuilder::parse("/a{1}/");
        $this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(1, count($children));
        $this->assertInstanceOf("REBuilder_Pattern_Repetition_Number", $children[0]->getRepetition());
        $this->assertEquals(1, $children[0]->getRepetition()->getNumber());
    }

    public function testInvalidNumberNotParsedAsRepetition ()
    {
        $regex = REBuilder::parse("/a{b}/");
        $this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(1, count($children));
        $this->assertInstanceOf("REBuilder_Pattern_Char", $children[0]);
        $this->assertSame("a{b}", $children[0]->getChar());
    }
}
