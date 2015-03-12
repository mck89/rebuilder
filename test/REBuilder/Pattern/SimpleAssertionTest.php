<?php
class SimpleAssertionTest extends PHPUnit_Framework_TestCase
{
    public function testGeneratedStructure ()
    {
        $regex = REBuilder\REBuilder::parse("/\bword\b/i");
        $this->assertInstanceOf("REBuilder\Pattern\Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(3, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\SimpleAssertion", $children[0]);
        $this->assertSame("b", $children[0]->getIdentifier());
        $this->assertInstanceOf("REBuilder\Pattern\Char", $children[1]);
        $this->assertSame("word", $children[1]->getChar());
        $this->assertInstanceOf("REBuilder\Pattern\SimpleAssertion", $children[2]);
        $this->assertSame("b", $children[2]->getIdentifier());
        $this->assertSame("/\bword\b/i", $regex->render());
    }

    public function validIdentifiersProvider ()
    {
        return array(
            array("b"),
            array("B"),
            array("A"),
            array("Z"),
            array("z"),
            array("G"),
            array("Q"),
            array("E"),
            array("K")
        );
    }

    /**
     * @dataProvider validIdentifiersProvider
     */
    public function testValidIdentifierException ($identifier)
    {
        $assertion = new REBuilder\Pattern\SimpleAssertion($identifier);
        $this->assertSame($identifier, $assertion->getIdentifier());
    }

    public function invalidIdentifiersProvider ()
    {
        return array(
            array("a"),
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
        $assertion = new REBuilder\Pattern\SimpleAssertion($identifier);
        $assertion->render();
    }

    /**
     * @expectedException REBuilder\Exception\InvalidRepetition
     */
    public function testRepetitionNotAllowedOnParse ()
    {
        REBuilder\REBuilder::parse("/\b*/i");
    }

    /**
     * @expectedException REBuilder\Exception\InvalidRepetition
     */
    public function testRepetitionNotAllowedOnGeneration ()
    {
        $assertion = new REBuilder\Pattern\SimpleAssertion("b");
        $assertion->setRepetition("*");
    }

    /**
     * @expectedException REBuilder\Exception\Generic
     */
    public function testExceptionMissingIdentifier ()
    {
        $assertion = new REBuilder\Pattern\SimpleAssertion();
        $assertion->render();
    }

    public function testObjectGeneration ()
    {
        $regex = REBuilder\REBuilder::create();
        $regex
                ->addSimpleAssertionAndContinue("b")
                ->addChar("abc");
        $this->assertSame("/\babc/", $regex->render());
    }
}
