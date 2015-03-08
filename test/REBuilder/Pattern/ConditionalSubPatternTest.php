<?php
class ConditionalSubPatternTest extends PHPUnit_Framework_TestCase
{
    public function testGeneratedStructure ()
    {
        $regex = REBuilder::parse("/a(?(?=a)a)+/");
        $this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(2, count($children));
        $this->assertInstanceOf("REBuilder_Pattern_Char", $children[0]);
        $this->assertSame("a", $children[0]->getChar());
        $this->assertInstanceOf("REBuilder_Pattern_ConditionalSubPattern", $children[1]);
        $this->assertInstanceOf("REBuilder_Pattern_Repetition_OneOrMore", $children[1]->getRepetition());
        $children = $children[1]->getChildren();
        $this->assertSame(2, count($children));
        $this->assertInstanceOf("REBuilder_Pattern_Assertion", $children[0]);
        $this->assertSame(true, $children[0]->getLookahead());
        $this->assertSame(false, $children[0]->getNegate());
        $assertionChildren = $children[0]->getChildren();
        $this->assertInstanceOf("REBuilder_Pattern_Char", $assertionChildren[0]);
        $this->assertSame("a", $assertionChildren[0]->getChar());
        $this->assertInstanceOf("REBuilder_Pattern_ConditionalThen", $children[1]);
        $thenChildren = $children[1]->getChildren();
        $this->assertInstanceOf("REBuilder_Pattern_Char", $assertionChildren[0]);
        $this->assertSame("a", $assertionChildren[0]->getChar());
        $this->assertSame("/a(?(?=a)a)+/", $regex->render());
    }
    
    public function testGeneratedStructureWithElse ()
    {
        $regex = REBuilder::parse("/a(?(?=a)a|b)+/");
        $this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(2, count($children));
        $this->assertInstanceOf("REBuilder_Pattern_Char", $children[0]);
        $this->assertSame("a", $children[0]->getChar());
        $this->assertInstanceOf("REBuilder_Pattern_ConditionalSubPattern", $children[1]);
        $this->assertInstanceOf("REBuilder_Pattern_Repetition_OneOrMore", $children[1]->getRepetition());
        $children = $children[1]->getChildren();
        $this->assertSame(3, count($children));
        $this->assertInstanceOf("REBuilder_Pattern_Assertion", $children[0]);
        $this->assertSame(true, $children[0]->getLookahead());
        $this->assertSame(false, $children[0]->getNegate());
        $assertionChildren = $children[0]->getChildren();
        $this->assertInstanceOf("REBuilder_Pattern_Char", $assertionChildren[0]);
        $this->assertSame("a", $assertionChildren[0]->getChar());
        $this->assertInstanceOf("REBuilder_Pattern_ConditionalThen", $children[1]);
        $thenChildren = $children[1]->getChildren();
        $this->assertInstanceOf("REBuilder_Pattern_Char", $thenChildren[0]);
        $this->assertSame("a", $thenChildren[0]->getChar());
        $this->assertInstanceOf("REBuilder_Pattern_ConditionalElse", $children[2]);
        $elseChildren = $children[2]->getChildren();
        $this->assertInstanceOf("REBuilder_Pattern_Char", $elseChildren[0]);
        $this->assertSame("b", $elseChildren[0]->getChar());
        $this->assertSame("/a(?(?=a)a|b)+/", $regex->render());
    }
    
    public function testIfThenElse ()
    {
        $cond = new REBuilder_Pattern_ConditionalSubPattern;
        
        $if = new REBuilder_Pattern_Assertion;
        $if2 = new REBuilder_Pattern_Assertion(false);
        $then = new REBuilder_Pattern_ConditionalThen;
        $else = new REBuilder_Pattern_ConditionalElse;
        
        $this->assertSame(null, $cond->getIf());
        $this->assertSame(null, $cond->getThen());
        $this->assertSame(null, $cond->getElse());
        
        $cond->setThen($then);
        
        $this->assertSame(null, $cond->getIf());
        $this->assertInstanceOf("REBuilder_Pattern_ConditionalThen", $cond->getThen());
        $this->assertSame(null, $cond->getElse());
        
        $cond->setElse($else);
        
        $this->assertSame(null, $cond->getIf());
        $this->assertInstanceOf("REBuilder_Pattern_ConditionalThen", $cond->getThen());
        $this->assertInstanceOf("REBuilder_Pattern_ConditionalElse", $cond->getElse());
        
        $cond->setIf($if);
        
        $this->assertInstanceOf("REBuilder_Pattern_Assertion", $cond->getIf());
        $this->assertInstanceOf("REBuilder_Pattern_ConditionalThen", $cond->getThen());
        $this->assertInstanceOf("REBuilder_Pattern_ConditionalElse", $cond->getElse());
        $this->assertSame(true, $cond->getIf()->getLookahead());
        
        $cond->setIf($if2);
        
        $this->assertInstanceOf("REBuilder_Pattern_Assertion", $cond->getIf());
        $this->assertInstanceOf("REBuilder_Pattern_ConditionalThen", $cond->getThen());
        $this->assertInstanceOf("REBuilder_Pattern_ConditionalElse", $cond->getElse());
        $this->assertSame(false, $cond->getIf()->getLookahead());
    }
    
    /**
     * @expectedException REBuilder_Exception_Generic
     */
    public function testInvalidChild ()
    {
        $cond = new REBuilder_Pattern_ConditionalSubPattern;
        $cond->addChild(new REBuilder_Pattern_Char);
    }
    
    /**
     * @expectedException REBuilder_Exception_Generic
     */
    public function testRepeatedChild ()
    {
        $cond = new REBuilder_Pattern_ConditionalSubPattern;
        $cond->addChild(new REBuilder_Pattern_Assertion);
        $cond->addChild(new REBuilder_Pattern_Assertion);
    }
    
    /**
     * @expectedException REBuilder_Exception_Generic
     */
    public function testInvalidPart ()
    {
        $cond = new REBuilder_Pattern_ConditionalSubPattern;
        $cond->setIf(new REBuilder_Pattern_ConditionalThen);
    }
    
    /**
     * @expectedException REBuilder_Exception_Generic
     */
    public function testMissingParts ()
    {
        $cond = new REBuilder_Pattern_ConditionalSubPattern;
        $cond->render();
    }

    public function testObjectGeneration ()
    {
        $regex = REBuilder::create();
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
