<?php
class RecursivePatternTest extends PHPUnit_Framework_TestCase
{
	public function validRecursivePatterns ()
    {
		return array(
			array('(?R)', 'R', true),
			array('(?1)', '1', true),
			array('(?-1)', '-1', true),
			array('(?P>ref)', 'ref', false),
			array('(?&ref)', 'ref', false)
		);
	}
    
    /**
	 * @dataProvider validRecursivePatterns
     */
	public function testGeneratedStructure ($pattern, $reference, $numeric)
	{
		$regex = REBuilder::parse("/(?<ref>a)$pattern/");
		$this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
		$children = $regex->getChildren();
		$this->assertSame(2, count($children));
		$this->assertInstanceOf("REBuilder_Pattern_RecursivePattern", $children[1]);
		$this->assertSame($reference, $children[1]->getReference());
        if ($numeric) {
            $this->assertSame("/(?<ref>a)(?$reference)/", $regex->render());
        } else {
            $this->assertSame("/(?<ref>a)(?P>$reference)/", $regex->render());
        }
	}
    
	public function invalidRecursivePatterns ()
    {
		return array(
			array('(?R)'),
			array('(?1)'),
			array('(?P>noref)'),
			array('(?&noref)'),
		);
	}
	
	/**
	 * @dataProvider invalidRecursivePatterns
     * @expectedException REBuilder_Exception_Generic
     */
    public function testInvalidRecursivePatterns ($pattern)
    {
		REBuilder::parse("/$pattern/");
    }
    
    /**
     * @expectedException REBuilder_Exception_Generic
     */
    public function testMissingReferenceException ()
    {
		$ref = new REBuilder_Pattern_RecursivePattern;
        $ref->render();
    }
	
	public function testObjectGeneration ()
	{
		$regex = REBuilder::create();
		$regex->addRecursivePattern("1");
		$this->assertSame('/(?1)/', $regex->render());
	}
}
