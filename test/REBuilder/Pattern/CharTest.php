<?php
class CharTest extends PHPUnit_Framework_TestCase
{
    public function testGeneratedStructure ()
    {
        $regex = REBuilder\REBuilder::parse("/a/i");
        $this->assertInstanceOf("REBuilder\Pattern\Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(1, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\Char", $children[0]);
        $this->assertSame("a", $children[0]->getChar());
        $this->assertSame(null, $children[0]->getRepetition());
        $this->assertSame("/a/i", $regex->render());
    }

    /**
     * @expectedException REBuilder\Exception\Generic
     */
    public function testInvalidCharException ()
    {
        $char = new REBuilder\Pattern\Char("");
        $char->render();
    }

    public function testRepetition ()
    {
        $regex = REBuilder\REBuilder::parse("/a*/i");
        $children = $regex->getChildren();
        $this->assertSame(1, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\Char", $children[0]);
        $this->assertInstanceOf("REBuilder\Pattern\Repetition\ZeroOrMore", $children[0]->getRepetition());
        $this->assertSame("/a*/i", $regex->render());
    }

    public function testParseMultiChar ()
    {
        $regex = REBuilder\REBuilder::parse("/abc/i");
        $children = $regex->getChildren();
        $this->assertSame(1, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\Char", $children[0]);
        $this->assertSame("abc", $children[0]->getChar());
        $this->assertSame("/abc/i", $regex->render());
    }

    public function testParseMultiCharWithRepetition ()
    {
        $regex = REBuilder\REBuilder::parse("/abc*/i");
        $children = $regex->getChildren();
        $this->assertSame(2, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\Char", $children[0]);
        $this->assertSame("ab", $children[0]->getChar());
        $this->assertSame(null, $children[0]->getRepetition());
        $this->assertInstanceOf("REBuilder\Pattern\Char", $children[1]);
        $this->assertSame("c", $children[1]->getChar());
        $this->assertInstanceOf("REBuilder\Pattern\Repetition\ZeroOrMore", $children[1]->getRepetition());
        $this->assertSame("/abc*/i", $regex->render());
    }

    public function testCharEscaped ()
    {
        $regex = REBuilder\REBuilder::parse("/\*/i");
        $children = $regex->getChildren();
        $this->assertSame(1, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\Char", $children[0]);
        $this->assertSame("*", $children[0]->getChar());
        $this->assertSame("/\*/i", $regex->render());
    }

    public function testMultiCharWithRepetition ()
    {
        $regex = REBuilder\REBuilder::create("i");
        $regex->addChar("abc")->setRepetition("*");
        $this->assertSame("/(?:abc)*/i", $regex->render());
    }

    public function testMoveToDifferentParent ()
    {
        $regex = REBuilder\REBuilder::create();
        $regex2 = REBuilder\REBuilder::create();
        $char = $regex->addChar("a");
        $regex2->addChild($char);
        $this->assertSame($regex2, $char->getParent());
    }

    public function testObjectGeneration ()
    {
        $regex = REBuilder\REBuilder::create("i");
        $regex->addChar("abc");
        $this->assertSame("/abc/i", $regex->render());
    }
}
