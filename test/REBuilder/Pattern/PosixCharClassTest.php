<?php
class PosixCharClassTest extends PHPUnit_Framework_TestCase
{
    public function testGeneratedStructure ()
    {
        $regex = REBuilder\REBuilder::parse('/[01[:alpha:]%]/');
        $this->assertInstanceOf("REBuilder\Pattern\Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(1, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\CharClass", $children[0]);
        $children = $children[0]->getChildren();
        $this->assertSame(3, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\Char", $children[0]);
        $this->assertSame("01", $children[0]->getChar());
        $this->assertInstanceOf("REBuilder\Pattern\PosixCharClass", $children[1]);
        $this->assertSame("alpha", $children[1]->getClass());
        $this->assertSame(false, $children[1]->getNegate());
        $this->assertInstanceOf("REBuilder\Pattern\Char", $children[2]);
        $this->assertSame("%", $children[2]->getChar());
        $this->assertSame('/[01[:alpha:]%]/', $regex->render());
    }
    
    public function testNegatedPosixCharClass ()
    {
        $regex = REBuilder\REBuilder::parse("/[[:^alnum:]]/");
        $this->assertInstanceOf("REBuilder\Pattern\Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(1, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\CharClass", $children[0]);
        $children = $children[0]->getChildren();
        $this->assertSame(1, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\PosixCharClass", $children[0]);
        $this->assertSame("alnum", $children[0]->getClass());
        $this->assertSame(true, $children[0]->getNegate());
        $this->assertSame("/[[:^alnum:]]/", $regex->render());
    }
    
    /**
     * @expectedException REBuilder\Exception\Generic
     */
    public function testWrongPosixCharClass ()
    {
        REBuilder\REBuilder::parse("/[[:wrong:]]/");
    }
    
    /**
     * @expectedException REBuilder\Exception\Generic
     */
    public function testCanBeAddedOnlyToCharClasses ()
    {
        $pattern = new REBuilder\Pattern\SubPattern;
        $class = new REBuilder\Pattern\PosixCharClass;
        $pattern->addChild($class);
    }
    
    /**
     * @expectedException REBuilder\Exception\Generic
     */
    public function testMissingClassException ()
    {
        $class = new REBuilder\Pattern\PosixCharClass;
        $class->render();
    }
    
    public function testObjectGeneration ()
    {
        $regex = REBuilder\REBuilder::create();
        $regex
                ->addCharClass()
                    ->addCharAndContinue("a")
                    ->addPosixCharClass("ascii");
        $this->assertSame("/[a[:ascii:]]/", $regex->render());
    }
}
