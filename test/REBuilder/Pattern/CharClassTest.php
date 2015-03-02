<?php
class CharClassTest extends PHPUnit_Framework_TestCase
{
    public function testGeneratedStructure ()
    {
        $regex = REBuilder::parse('/[]a$^?\w\111\G\8]]/');
        $this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(2, count($children));
        $this->assertInstanceOf("REBuilder_Pattern_Char", $children[1]);
        $this->assertSame("]", $children[1]->getChar());
        $this->assertInstanceOf("REBuilder_Pattern_CharClass", $children[0]);
        $children = $children[0]->getChildren();
        $this->assertSame(4, count($children));
        $this->assertInstanceOf("REBuilder_Pattern_Char", $children[0]);
        $this->assertSame("]a$^?", $children[0]->getChar());
        $this->assertInstanceOf("REBuilder_Pattern_GenericCharType", $children[1]);
        $this->assertSame("w", $children[1]->getIdentifier());
        $this->assertInstanceOf("REBuilder_Pattern_OctalChar", $children[2]);
        $this->assertSame("111", $children[2]->getChar());
        $this->assertInstanceOf("REBuilder_Pattern_Char", $children[3]);
        $this->assertSame("G8", $children[3]->getChar());
        $this->assertSame('/[\]a\$\^\?\w\111G8]\]/', $regex->render());
    }
    
    public function testNegatedCharClass ()
    {
        $regex = REBuilder::parse("/[^a\][]*/");
        $this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(1, count($children));
        $this->assertInstanceOf("REBuilder_Pattern_CharClass", $children[0]);
        $this->assertSame(true, $children[0]->getNegate());
        $this->assertInstanceOf("REBuilder_Pattern_Repetition_ZeroOrMore", $children[0]->getRepetition());
        $children = $children[0]->getChildren();
        $this->assertSame(1, count($children));
        $this->assertInstanceOf("REBuilder_Pattern_Char", $children[0]);
        $this->assertSame("a][", $children[0]->getChar());
        $this->assertSame("/[^a\]\[]*/", $regex->render());
    }
    
    /**
     * @expectedException REBuilder_Exception_Generic
     */
    public function testUnclosedCharClass ()
    {
        REBuilder::parse("/[a/");
    }
    
    public function invalidCharClassContent ()
    {
        return array(
            array("REBuilder_Pattern_Dot"),
            array("REBuilder_Pattern_Assertion"),
            array("REBuilder_Pattern_BackReference")
        );
    }

    /**
     * @dataProvider invalidCharClassContent
     * @expectedException REBuilder_Exception_Generic
     */
    public function testInvalidCharClassContent ($class)
    {
        $charClass = new REBuilder_Pattern_CharClass();
        $charClass->addChild(new $class);
    }
    
    public function testObjectGeneration ()
    {
        $regex = REBuilder::create();
        $regex
                ->addCharClass(true)
                ->setRepetition("+")
                    ->addChar("a");
        $this->assertSame("/[^a]+/", $regex->render());
    }
}
