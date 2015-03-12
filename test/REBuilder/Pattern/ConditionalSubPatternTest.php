<?php
class ConditionalSubPatternTest extends PHPUnit_Framework_TestCase
{
    public function testGeneratedStructure ()
    {
        $regex = REBuilder\REBuilder::parse("/a(?(?=a)a)+/");
        $this->assertInstanceOf("REBuilder\Pattern\Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(2, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\Char", $children[0]);
        $this->assertSame("a", $children[0]->getChar());
        $this->assertInstanceOf("REBuilder\Pattern\ConditionalSubPattern", $children[1]);
        $this->assertInstanceOf("REBuilder\Pattern\Repetition\OneOrMore", $children[1]->getRepetition());
        $children = $children[1]->getChildren();
        $this->assertSame(2, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\Assertion", $children[0]);
        $this->assertSame(true, $children[0]->getLookahead());
        $this->assertSame(false, $children[0]->getNegate());
        $assertionChildren = $children[0]->getChildren();
        $this->assertInstanceOf("REBuilder\Pattern\Char", $assertionChildren[0]);
        $this->assertSame("a", $assertionChildren[0]->getChar());
        $this->assertInstanceOf("REBuilder\Pattern\ConditionalThen", $children[1]);
        $thenChildren = $children[1]->getChildren();
        $this->assertInstanceOf("REBuilder\Pattern\Char", $thenChildren[0]);
        $this->assertSame("a", $thenChildren[0]->getChar());
        $this->assertSame("/a(?(?=a)a)+/", $regex->render());
    }
    
    public function testGeneratedStructureWithElse ()
    {
        $regex = REBuilder\REBuilder::parse("/a(?(?=a)a|b)+/");
        $this->assertInstanceOf("REBuilder\Pattern\Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(2, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\Char", $children[0]);
        $this->assertSame("a", $children[0]->getChar());
        $this->assertInstanceOf("REBuilder\Pattern\ConditionalSubPattern", $children[1]);
        $this->assertInstanceOf("REBuilder\Pattern\Repetition\OneOrMore", $children[1]->getRepetition());
        $children = $children[1]->getChildren();
        $this->assertSame(3, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\Assertion", $children[0]);
        $this->assertSame(true, $children[0]->getLookahead());
        $this->assertSame(false, $children[0]->getNegate());
        $assertionChildren = $children[0]->getChildren();
        $this->assertInstanceOf("REBuilder\Pattern\Char", $assertionChildren[0]);
        $this->assertSame("a", $assertionChildren[0]->getChar());
        $this->assertInstanceOf("REBuilder\Pattern\ConditionalThen", $children[1]);
        $thenChildren = $children[1]->getChildren();
        $this->assertInstanceOf("REBuilder\Pattern\Char", $thenChildren[0]);
        $this->assertSame("a", $thenChildren[0]->getChar());
        $this->assertInstanceOf("REBuilder\Pattern\ConditionalElse", $children[2]);
        $elseChildren = $children[2]->getChildren();
        $this->assertInstanceOf("REBuilder\Pattern\Char", $elseChildren[0]);
        $this->assertSame("b", $elseChildren[0]->getChar());
        $this->assertSame("/a(?(?=a)a|b)+/", $regex->render());
    }
    
    public function testIfThenElse ()
    {
        $cond = new REBuilder\Pattern\ConditionalSubPattern;
        
        $if = new REBuilder\Pattern\Assertion;
        $if2 = new REBuilder\Pattern\Assertion(false);
        $then = new REBuilder\Pattern\ConditionalThen;
        $else = new REBuilder\Pattern\ConditionalElse;
        
        $this->assertSame(null, $cond->getIf());
        $this->assertSame(null, $cond->getThen());
        $this->assertSame(null, $cond->getElse());
        
        $cond->setThen($then);
        
        $this->assertSame(null, $cond->getIf());
        $this->assertInstanceOf("REBuilder\Pattern\ConditionalThen", $cond->getThen());
        $this->assertSame(null, $cond->getElse());
        
        $cond->setElse($else);
        
        $this->assertSame(null, $cond->getIf());
        $this->assertInstanceOf("REBuilder\Pattern\ConditionalThen", $cond->getThen());
        $this->assertInstanceOf("REBuilder\Pattern\ConditionalElse", $cond->getElse());
        
        $cond->setIf($if);
        
        $this->assertInstanceOf("REBuilder\Pattern\Assertion", $cond->getIf());
        $this->assertInstanceOf("REBuilder\Pattern\ConditionalThen", $cond->getThen());
        $this->assertInstanceOf("REBuilder\Pattern\ConditionalElse", $cond->getElse());
        $this->assertSame(true, $cond->getIf()->getLookahead());
        
        $cond->setIf($if2);
        
        $this->assertInstanceOf("REBuilder\Pattern\Assertion", $cond->getIf());
        $this->assertInstanceOf("REBuilder\Pattern\ConditionalThen", $cond->getThen());
        $this->assertInstanceOf("REBuilder\Pattern\ConditionalElse", $cond->getElse());
        $this->assertSame(false, $cond->getIf()->getLookahead());
    }
    
    /**
     * @expectedException REBuilder\Exception\Generic
     */
    public function testInvalidChild ()
    {
        $cond = new REBuilder\Pattern\ConditionalSubPattern;
        $cond->addChild(new REBuilder\Pattern\Char);
    }
    
    /**
     * @expectedException REBuilder\Exception\Generic
     */
    public function testRepeatedChild ()
    {
        $cond = new REBuilder\Pattern\ConditionalSubPattern;
        $cond->addChild(new REBuilder\Pattern\Assertion);
        $cond->addChild(new REBuilder\Pattern\Assertion);
    }
    
    /**
     * @expectedException REBuilder\Exception\Generic
     */
    public function testInvalidPart ()
    {
        $cond = new REBuilder\Pattern\ConditionalSubPattern;
        $cond->setIf(new REBuilder\Pattern\ConditionalThen);
    }
    
    /**
     * @expectedException REBuilder\Exception\Generic
     */
    public function testMissingParts ()
    {
        $cond = new REBuilder\Pattern\ConditionalSubPattern;
        $cond->render();
    }

    public function testObjectGeneration ()
    {
        $regex = REBuilder\REBuilder::create();
        $regex
                ->addCharAndContinue("a")
                ->addConditionalSubPattern()
                    ->addAssertion()
                        ->addCharAndContinue("c")
                    ->getParent()
                    ->addConditionalThen()
                        ->addCharAndContinue("c")
                    ->getParent()
                    ->addConditionalElse()
                        ->addCharAndContinue("d");
        $this->assertSame("/a(?(?=c)c|d)/", $regex . "");
    }
}
