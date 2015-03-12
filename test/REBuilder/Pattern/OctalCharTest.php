<?php
class OctalCharTest extends PHPUnit_Framework_TestCase
{
    public function testGeneratedStructure ()
    {
        $regex = REBuilder\REBuilder::parse('/\111/');
        $this->assertInstanceOf("REBuilder\Pattern\Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(1, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\OctalChar", $children[0]);
        $this->assertSame("111", $children[0]->getChar());
        $this->assertSame('/\111/', $regex->render());
    }

    public function testOctalCharWithLeadingZero ()
    {
        $regex = REBuilder\REBuilder::parse('/\01/');
        $this->assertInstanceOf("REBuilder\Pattern\Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(1, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\OctalChar", $children[0]);
        $this->assertSame("01", $children[0]->getChar());
        $this->assertSame('/\01/', $regex->render());
    }

    public function testOctalCharFollowedByNumber ()
    {
        $regex = REBuilder\REBuilder::parse('/\0111/');
        $this->assertInstanceOf("REBuilder\Pattern\Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(2, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\OctalChar", $children[0]);
        $this->assertSame("011", $children[0]->getChar());
        $this->assertInstanceOf("REBuilder\Pattern\Char", $children[1]);
        $this->assertSame("1", $children[1]->getChar());
        $this->assertSame('/\0111/', $regex->render());
    }

    public function invalidCharsProvider ()
    {
        return array(
            array("2"),
            array("789"),
            array("11111"),
            array("")
        );
    }

    /**
     * @dataProvider invalidCharsProvider
     * @expectedException REBuilder\Exception\Generic
     */
    public function testInvalidCharException ($char)
    {
        $octal = new REBuilder\Pattern\OctalChar($char);
        $octal->render();
    }

    /**
     * @expectedException REBuilder\Exception\Generic
     */
    public function testMissingCharException ()
    {
        $ref = new REBuilder\Pattern\OctalChar;
        $ref->render();
    }

    public function testObjectGeneration ()
    {
        $regex = REBuilder\REBuilder::create();
        $regex
                ->addOctalChar("01");
        $this->assertSame('/\01/', $regex->render());
    }
}
