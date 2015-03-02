<?php
class PosixCharClassTest extends PHPUnit_Framework_TestCase
{
    public function testGeneratedStructure ()
    {
        $regex = REBuilder::parse('/[01[:alpha:]%]/');
        $this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(1, count($children));
        $this->assertInstanceOf("REBuilder_Pattern_CharClass", $children[0]);
        $children = $children[0]->getChildren();
        $this->assertSame(3, count($children));
        $this->assertInstanceOf("REBuilder_Pattern_Char", $children[0]);
        $this->assertSame("01", $children[0]->getChar());
        $this->assertInstanceOf("REBuilder_Pattern_PosixCharClass", $children[1]);
        $this->assertSame("alpha", $children[1]->getClass());
        $this->assertSame(false, $children[1]->getNegate());
        $this->assertInstanceOf("REBuilder_Pattern_Char", $children[2]);
        $this->assertSame("%", $children[2]->getChar());
        $this->assertSame('/[01[:alpha:]%]/', $regex->render());
    }
    
    public function testNegatedPosixCharClass ()
    {
        $regex = REBuilder::parse("/[[:^alnum:]]/");
        $this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(1, count($children));
        $this->assertInstanceOf("REBuilder_Pattern_CharClass", $children[0]);
        $children = $children[0]->getChildren();
        $this->assertSame(1, count($children));
        $this->assertInstanceOf("REBuilder_Pattern_PosixCharClass", $children[0]);
        $this->assertSame("alnum", $children[0]->getClass());
        $this->assertSame(true, $children[0]->getNegate());
        $this->assertSame("/[[:^alnum:]]/", $regex->render());
    }
    
    /**
     * @expectedException REBuilder_Exception_Generic
     */
    public function testWrongPosixCharClass ()
    {
        REBuilder::parse("/[[:wrong:]]/");
    }
    
    /**
     * @expectedException REBuilder_Exception_Generic
     */
    public function testCanBeAddedOnlyToCharClasses ()
    {
        $pattern = new REBuilder_Pattern_SubPattern;
        $class = new REBuilder_Pattern_PosixCharClass;
        $pattern->addChild($class);
    }
    
    /**
     * @expectedException REBuilder_Exception_Generic
     */
    public function testMissingClassException ()
    {
        $class = new REBuilder_Pattern_PosixCharClass;
        $class->render();
    }
    
    public function testObjectGeneration ()
    {
        $regex = REBuilder::create();
        $regex
                ->addCharClass()
                    ->addCharAndContinue("a")
                    ->addPosixCharClass("ascii");
        $this->assertSame("/[a[:ascii:]]/", $regex->render());
    }
}
