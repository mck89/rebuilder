<?php
class HexCharTest extends PHPUnit_Framework_TestCase
{
    public function testGeneratedStructure ()
    {
        $validCharacters = array("", "a", "1b");
        foreach ($validCharacters as $c) {
            $regex = REBuilder\REBuilder::parse("/\x$c/");
            $this->assertInstanceOf("REBuilder\Pattern\Regex", $regex);
            $children = $regex->getChildren();
            $this->assertSame(1, count($children));
            $this->assertInstanceOf("REBuilder\Pattern\HexChar", $children[0]);
            $this->assertSame($c, $children[0]->getChar());
            $this->assertSame("/\x$c/", $regex->render());
        }
    }
    
    public function testAlternativeSyntax ()
    {
        $regex = REBuilder\REBuilder::parse('/\x{61}/');
        $this->assertInstanceOf("REBuilder\Pattern\Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(1, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\HexChar", $children[0]);
        $this->assertSame("61", $children[0]->getChar());
        $this->assertSame('/\x61/', $regex->render());
    }

    public function invalidHexChars ()
    {
        return array(
            array("z"),
            array("abc")
        );
    }

    /**
     * @dataProvider invalidHexChars
     * @expectedException REBuilder\Exception\Generic
     */
    public function testInvalidCharException ($chars)
    {
        $char = new REBuilder\Pattern\HexChar();
        $char->setChar($chars);
    }
    
    /**
     * @expectedException REBuilder\Exception\Generic
     */
    public function testInvalidAlternativeSyntax ()
    {
        REBuilder\REBuilder::parse('/\x{61/');
    }

    public function testObjectGeneration ()
    {
        $regex = REBuilder\REBuilder::create();
        $regex->addHexChar("aa")->setRepetition(1, 2);
        $this->assertSame("/\\xaa{1,2}/", $regex->render());
    }
}
