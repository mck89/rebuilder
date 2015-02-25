<?php
class InternalOptionTest extends AbstractTest
{
    public function testGeneratedStructure ()
    {
        $regex = REBuilder::parse("/a(?m-i)b/i");
        $this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(3, count($children));
        $this->assertInstanceOf("REBuilder_Pattern_Char", $children[0]);
        $this->assertInstanceOf("REBuilder_Pattern_InternalOption", $children[1]);
        $this->assertSame("m-i", $children[1]->getModifiers());
        $this->assertSame("/a(?m-i)b/i", $regex->render());
    }

    /**
     * @expectedException REBuilder_Exception_InvalidModifier
     */
    public function testInvalidModifierException ()
    {
        new REBuilder_Pattern_InternalOption("$");
    }

    /**
     * @expectedException REBuilder_Exception_InvalidRepetition
     */
    public function testRepetitionNotAllowedOnParse ()
    {
        REBuilder::parse("/(?i)*/");
    }

    /**
     * @expectedException REBuilder_Exception_InvalidRepetition
     */
    public function testRepetitionNotAllowedOnGeneration ()
    {
        $option = new REBuilder_Pattern_InternalOption();
        $option->setRepetition("*");
    }

    public function testObjectGeneration ()
    {
        $regex = REBuilder::create();
        $regex
                ->addInternalOptionAndContinue("i-m")
                ->addChar("test");
        $this->assertSame("/(?i-m)test/", $regex->render());
    }
}
