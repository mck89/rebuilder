<?php
class ZeroOrMoreTest extends PHPUnit_Framework_TestCase
{
    public function testGeneratedStructure ()
    {
        $regex = REBuilder\REBuilder::parse("/a*/");
        $this->assertInstanceOf("REBuilder\Pattern\Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(1, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\Repetition\ZeroOrMore", $children[0]->getRepetition());
        $this->assertSame(false, $children[0]->getRepetition()->getLazy());
    }

    public function testGeneratedStructureLazy ()
    {
        $regex = REBuilder\REBuilder::parse("/a*?/");
        $this->assertInstanceOf("REBuilder\Pattern\Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(1, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\Repetition\ZeroOrMore", $children[0]->getRepetition());
        $this->assertSame(true, $children[0]->getRepetition()->getLazy());
        $this->assertSame("*?", $children[0]->getRepetition() . "");
    }

    public function invalidRepetitions ()
    {
        return array(
            array(".*??"),
            array("?"),
            array(".*+")
        );
    }

    /**
     * @dataProvider invalidRepetitions
     * @expectedException REBuilder\Exception\InvalidRepetition
     */
    public function testInvalidRepetition ($pattern)
    {
        REBuilder\REBuilder::parse("/$pattern/");
    }

    /**
     * @expectedException REBuilder\Exception\InvalidRepetition
     */
    public function testInvalidRepetitionWithFunction ()
    {
        $char = new REBuilder\Pattern\Char();
        $char->setRepetition("a");
    }
}
