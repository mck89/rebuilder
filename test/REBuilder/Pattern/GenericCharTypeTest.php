<?php
class GenericCharTypeTest extends PHPUnit_Framework_TestCase
{
    public function testGeneratedStructure ()
    {
        $regex = REBuilder\REBuilder::parse("/\d\w\s/i");
        $this->assertInstanceOf("REBuilder\Pattern\Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(3, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\GenericCharType", $children[0]);
        $this->assertSame("d", $children[0]->getIdentifier());
        $this->assertInstanceOf("REBuilder\Pattern\GenericCharType", $children[1]);
        $this->assertSame("w", $children[1]->getIdentifier());
        $this->assertInstanceOf("REBuilder\Pattern\GenericCharType", $children[2]);
        $this->assertSame("s", $children[2]->getIdentifier());
        $this->assertSame("/\d\w\s/i", $regex->render());
    }

    public function validIdentifiersProvider ()
    {
        return array(
            array("d"),
            array("D"),
            array("h"),
            array("H"),
            array("s"),
            array("S"),
            array("v"),
            array("V"),
            array("W"),
            array("w")
        );
    }

    /**
     * @dataProvider validIdentifiersProvider
     */
    public function testValidIdentifierException ($identifier)
    {
        $char = new REBuilder\Pattern\GenericCharType($identifier);
        $this->assertSame($identifier, $char->getIdentifier());
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
        $char = new REBuilder\Pattern\GenericCharType($identifier);
        $char->render();
    }

    /**
     * @expectedException REBuilder\Exception\Generic
     */
    public function testExceptionMissingIdentifier ()
    {
        $assertion = new REBuilder\Pattern\GenericCharType();
        $assertion->render();
    }

    public function testObjectGeneration ()
    {
        $regex = REBuilder\REBuilder::create();
        $regex->addGenericCharType("w");
        $this->assertSame("/\w/", $regex->render());
    }
}
