<?php
class OptionalTest extends PHPUnit_Framework_TestCase
{
    public function testGeneratedStructure ()
    {
        $regex = REBuilder\REBuilder::parse("/a?/");
        $this->assertInstanceOf("REBuilder\Pattern\Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(1, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\Repetition\Optional", $children[0]->getRepetition());
        $this->assertSame(false, $children[0]->getRepetition()->getLazy());
    }
}
