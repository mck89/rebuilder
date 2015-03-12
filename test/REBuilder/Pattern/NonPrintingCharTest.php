<?php
class NonPrintingCharTest extends PHPUnit_Framework_TestCase
{
    public function testGeneratedStructure ()
    {
        $regex = REBuilder\REBuilder::parse("/\a/i");
        $this->assertInstanceOf("REBuilder\Pattern\Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(1, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\NonPrintingChar", $children[0]);
        $this->assertSame("a", $children[0]->getIdentifier());
    }

    public function validIdentifiersProvider ()
    {
        return array(
            array("a"),
            array("e"),
            array("f"),
            array("n"),
            array("r"),
            array("t")
        );
    }

    /**
     * @dataProvider validIdentifiersProvider
     */
    public function testValidIdentifierException ($identifier)
    {
        $char = new REBuilder\Pattern\NonPrintingChar($identifier);
        $this->assertSame($identifier, $char->getIdentifier());
    }


    public function invalidIdentifiersProvider ()
    {
        return array(
            array("A"),
            array("2"),
            array("."),
            array("@")
        );
    }

    /**
     * @dataProvider invalidIdentifiersProvider
     * @expectedException REBuilder\Exception\Generic
     */
    public function testInvalidIdentifierException ($identifier)
    {
        $char = new REBuilder\Pattern\NonPrintingChar($identifier);
        $char->render();
    }

    /**
     * @expectedException REBuilder\Exception\Generic
     */
    public function testExceptionMissingIdentifier ()
    {
        $assertion = new REBuilder\Pattern\NonPrintingChar();
        $assertion->render();
    }

    public function testObjectGeneration ()
    {
        $regex = REBuilder\REBuilder::create();
        $regex->addNonPrintingChar("a")->setRepetition(2);
        $this->assertSame("/\a{2}/", $regex->render());
    }
}
