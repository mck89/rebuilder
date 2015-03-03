<?php
class CharClassRangeTest extends PHPUnit_Framework_TestCase
{
    public function testGeneratedStructure ()
    {
        $regex = REBuilder::parse('/[a-\xFFz]/');
        $this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(1, count($children));
        $this->assertInstanceOf("REBuilder_Pattern_CharClass", $children[0]);
        $children = $children[0]->getChildren();
        $this->assertSame(2, count($children));
        $this->assertInstanceOf("REBuilder_Pattern_Char", $children[1]);
        $this->assertSame("z", $children[1]->getChar());
        $this->assertInstanceOf("REBuilder_Pattern_CharClassRange", $children[0]);
        $children = $children[0]->getChildren();
        $this->assertSame(2, count($children));
        $this->assertInstanceOf("REBuilder_Pattern_Char", $children[0]);
        $this->assertSame("a", $children[0]->getChar());
        $this->assertInstanceOf("REBuilder_Pattern_HexChar", $children[1]);
        $this->assertSame("FF", $children[1]->getChar());
        $this->assertSame('/[a-\xFFz]/', $regex->render());
    }
    
    public function testNotDashInterpretedAsRange ()
    {
        $regex = REBuilder::parse('/[\w-a]/');
        $this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(1, count($children));
        $this->assertInstanceOf("REBuilder_Pattern_CharClass", $children[0]);
        $children = $children[0]->getChildren();
        $this->assertSame(2, count($children));
        $this->assertInstanceOf("REBuilder_Pattern_GenericCharType", $children[0]);
        $this->assertSame("w", $children[0]->getIdentifier());
        $this->assertInstanceOf("REBuilder_Pattern_Char", $children[1]);
        $this->assertSame("-a", $children[1]->getChar());
        $this->assertSame('/[\w\-a]/', $regex->render());
    }
    
    public function testEndClassAfterDash ()
    {
        $regex = REBuilder::parse('/[a-]/');
        $this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(1, count($children));
        $this->assertInstanceOf("REBuilder_Pattern_CharClass", $children[0]);
        $children = $children[0]->getChildren();
        $this->assertSame(1, count($children));
        $this->assertInstanceOf("REBuilder_Pattern_Char", $children[0]);
        $this->assertSame("a-", $children[0]->getChar());
        $this->assertSame('/[a\-]/', $regex->render());
    }
    
    /**
     * @expectedException REBuilder_Exception_Generic
     */
    public function testInvalidCharClassRange ()
    {
        REBuilder::parse("/[a-\w]/");
    }
    
    public function invalidCharClassRangeContent ()
    {
        return array(
            array("REBuilder_Pattern_Dot"),
            array("REBuilder_Pattern_Assertion"),
            array("REBuilder_Pattern_BackReference")
        );
    }

    /**
     * @dataProvider invalidCharClassRangeContent
     * @expectedException REBuilder_Exception_Generic
     */
    public function testInvalidCharClassRangeContent ($class)
    {
        $range = new REBuilder_Pattern_CharClassRange();
        $range->addChild(new $class);
    }
    
    /**
     * @expectedException REBuilder_Exception_Generic
     */
    public function testInvalidNumberOfChildren ()
    {
        $range = new REBuilder_Pattern_CharClassRange();
        $range->addCharAndContinue("a")
              ->addCharAndContinue("b")
              ->addCharAndContinue("c");
    }
    
    /**
     * @expectedException REBuilder_Exception_Generic
     */
    public function testInvalidNumberOfChildrenOnRender ()
    {
        $range = new REBuilder_Pattern_CharClassRange();
        $range->addChar("a");
        $range->render();
    }
    
    public function testObjectGeneration ()
    {
        $regex = REBuilder::create();
        $regex
                ->addCharClass()
                    ->addCharClassRange()
                        ->addCharAndContinue("a")
                        ->addCharAndContinue("z");
        $this->assertSame("/[a-z]/", $regex->render());
    }
}
