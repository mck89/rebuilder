<?php
class OptionalTest extends PHPUnit_Framework_TestCase
{
    public function testGeneratedStructure ()
    {
        $regex = REBuilder::parse("/a?/");
        $this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(1, count($children));
        $this->assertInstanceOf("REBuilder_Pattern_Repetition_Optional", $children[0]->getRepetition());
        $this->assertSame(false, $children[0]->getRepetition()->getLazy());
    }
}
