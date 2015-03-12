<?php
class InternalOptionTest extends PHPUnit_Framework_TestCase
{
    public function testGeneratedStructure ()
    {
        $regex = REBuilder\REBuilder::parse("/a(?m-i)b/i");
        $this->assertInstanceOf("REBuilder\Pattern\Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(3, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\Char", $children[0]);
        $this->assertInstanceOf("REBuilder\Pattern\InternalOption", $children[1]);
        $this->assertSame("m-i", $children[1]->getModifiers());
        $this->assertSame("/a(?m-i)b/i", $regex->render());
    }

    /**
     * @expectedException REBuilder\Exception\InvalidModifier
     */
    public function testInvalidModifierException ()
    {
        new REBuilder\Pattern\InternalOption("$");
    }

    /**
     * @expectedException REBuilder\Exception\InvalidRepetition
     */
    public function testRepetitionNotAllowedOnParse ()
    {
        REBuilder\REBuilder::parse("/(?i)*/");
    }

    /**
     * @expectedException REBuilder\Exception\InvalidRepetition
     */
    public function testRepetitionNotAllowedOnGeneration ()
    {
        $option = new REBuilder\Pattern\InternalOption();
        $option->setRepetition("*");
    }

    public function testObjectGeneration ()
    {
        $regex = REBuilder\REBuilder::create();
        $regex
                ->addInternalOptionAndContinue("i-m")
                ->addChar("test");
        $this->assertSame("/(?i-m)test/", $regex->render());
    }
}
