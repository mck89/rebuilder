<?php
class ControlCharTest extends PHPUnit_Framework_TestCase
{
    public function testGeneratedStructure ()
    {
        $regex = REBuilder\REBuilder::parse("/\c;+/i");
        $this->assertInstanceOf("REBuilder\Pattern\Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(1, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\ControlChar", $children[0]);
        $this->assertSame(";", $children[0]->getChar());
        $this->assertInstanceOf("REBuilder\Pattern\Repetition\OneOrMore", $children[0]->getRepetition());
        $this->assertSame("/\c;+/i", $regex->render());
    }

    /**
     * @expectedException REBuilder\Exception\Generic
     */
    public function testInvalidCharException ()
    {
        $char = new REBuilder\Pattern\ControlChar();
        $char->setChar("abc");
    }

    /**
     * @expectedException REBuilder\Exception\Generic
     */
    public function testInvalidEmptyCharException ()
    {
        $char = new REBuilder\Pattern\ControlChar();
        $char->setChar("");
    }

    /**
     * @expectedException REBuilder\Exception\Generic
     */
    public function testInvalidRegexException ()
    {
        REBuilder\REBuilder::parse("/\c/");
    }

    public function testEscapedChar ()
    {
        $regex = REBuilder\REBuilder::parse("/\c\+/i");
        $this->assertInstanceOf("REBuilder\Pattern\Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(1, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\ControlChar", $children[0]);
        $this->assertSame("+", $children[0]->getChar());
        $this->assertSame(null, $children[0]->getRepetition());
        $this->assertSame("/\c\+/i", $regex->render());
    }

    public function testObjectGeneration ()
    {
        $regex = REBuilder\REBuilder::create();
        $regex->addControlChar("a");
        $this->assertSame("/\ca/", $regex->render());
    }
}
