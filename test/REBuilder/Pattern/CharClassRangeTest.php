<?php
class CharClassRangeTest extends PHPUnit_Framework_TestCase
{
    public function testGeneratedStructure ()
    {
        $regex = REBuilder\REBuilder::parse('/[a-\xFFz]/');
        $this->assertInstanceOf("REBuilder\Pattern\Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(1, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\CharClass", $children[0]);
        $children = $children[0]->getChildren();
        $this->assertSame(2, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\Char", $children[1]);
        $this->assertSame("z", $children[1]->getChar());
        $this->assertInstanceOf("REBuilder\Pattern\CharClassRange", $children[0]);
        $children = $children[0]->getChildren();
        $this->assertSame(2, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\Char", $children[0]);
        $this->assertSame("a", $children[0]->getChar());
        $this->assertInstanceOf("REBuilder\Pattern\HexChar", $children[1]);
        $this->assertSame("FF", $children[1]->getChar());
        $this->assertSame('/[a-\xFFz]/', $regex->render());
    }
    
    public function testNotDashInterpretedAsRange ()
    {
        $regex = REBuilder\REBuilder::parse('/[\w-a]/');
        $this->assertInstanceOf("REBuilder\Pattern\Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(1, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\CharClass", $children[0]);
        $children = $children[0]->getChildren();
        $this->assertSame(2, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\GenericCharType", $children[0]);
        $this->assertSame("w", $children[0]->getIdentifier());
        $this->assertInstanceOf("REBuilder\Pattern\Char", $children[1]);
        $this->assertSame("-a", $children[1]->getChar());
        $this->assertSame('/[\w\-a]/', $regex->render());
    }
    
    public function testEndClassAfterDash ()
    {
        $regex = REBuilder\REBuilder::parse('/[a-]/');
        $this->assertInstanceOf("REBuilder\Pattern\Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(1, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\CharClass", $children[0]);
        $children = $children[0]->getChildren();
        $this->assertSame(1, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\Char", $children[0]);
        $this->assertSame("a-", $children[0]->getChar());
        $this->assertSame('/[a\-]/', $regex->render());
    }
    
    /**
     * @expectedException REBuilder\Exception\Generic
     */
    public function testInvalidCharClassRange ()
    {
        REBuilder\REBuilder::parse("/[a-\w]/");
    }
    
    public function invalidCharClassRangeContent ()
    {
        return array(
            array("REBuilder\Pattern\Dot"),
            array("REBuilder\Pattern\Assertion"),
            array("REBuilder\Pattern\BackReference")
        );
    }

    /**
     * @dataProvider invalidCharClassRangeContent
     * @expectedException REBuilder\Exception\Generic
     */
    public function testInvalidCharClassRangeContent ($class)
    {
        $range = new REBuilder\Pattern\CharClassRange();
        $range->addChild(new $class);
    }
    
    /**
     * @expectedException REBuilder\Exception\Generic
     */
    public function testInvalidNumberOfChildren ()
    {
        $range = new REBuilder\Pattern\CharClassRange();
        $range->addCharAndContinue("a")
              ->addCharAndContinue("b")
              ->addCharAndContinue("c");
    }
    
    /**
     * @expectedException REBuilder\Exception\Generic
     */
    public function testInvalidNumberOfChildrenOnRender ()
    {
        $range = new REBuilder\Pattern\CharClassRange();
        $range->addChar("a");
        $range->render();
    }
    
    public function testEndStart ()
    {
        $regex = REBuilder\REBuilder::create();
        $range = $regex
                        ->addCharClass()
                            ->addCharClassRange();
        $char1 = new REBuilder\Pattern\Char("a");
        $char2 = new REBuilder\Pattern\Char("b");
        $this->assertSame(null, $range->getEnd());
        $range->setEnd($char2);
        $this->assertSame(null, $range->getStart());
        $range->setStart($char1);
        $this->assertSame($char1->getChar(), $range->getStart()->getChar());
        $this->assertSame($char2->getChar(), $range->getEnd()->getChar());
        $this->assertSame("/[a-b]/", $regex->render());
    }

    /**
     * @expectedException REBuilder\Exception\Generic
     */
    public function testInvalidStart ()
    {
        $range = new REBuilder\Pattern\CharClassRange();
        $range->setStart("a");
    }

    /**
     * @expectedException REBuilder\Exception\Generic
     */
    public function testInvalidStartPattern ()
    {
        $range = new REBuilder\Pattern\CharClassRange();
        $range->setStart(new REBuilder\Pattern\GenericCharType);
    }
    
    public function testObjectGeneration ()
    {
        $regex = REBuilder\REBuilder::create();
        $regex
                ->addCharClass()
                    ->addCharClassRange()
                        ->addCharAndContinue("a")
                        ->addCharAndContinue("z");
        $this->assertSame("/[a-z]/", $regex->render());
    }
}
