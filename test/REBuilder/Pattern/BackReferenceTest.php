<?php
class BackReferenceTest extends PHPUnit_Framework_TestCase
{
    public function validBackReferences ()
    {
        return array(
            array('\1', '1'),
            array('\g1', '1'),
            array('\g{1}', '1'),
            array('\g{-1}', '-1'),
            array('\g{ref}', 'ref'),
            array('\k<ref>', 'ref'),
            array('\k\'ref\'', 'ref'),
            array('\k{ref}', 'ref'),
            array('(?P=ref)', 'ref'),
        );
    }

    /**
     * @dataProvider validBackReferences
     */
    public function testGeneratedStructure ($pattern, $reference)
    {
        $regex = REBuilder\REBuilder::parse("/(?<ref>a)$pattern/");
        $this->assertInstanceOf("REBuilder\Pattern\Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(2, count($children));
        $this->assertInstanceOf("REBuilder\Pattern\BackReference", $children[1]);
        $this->assertSame($reference, $children[1]->getReference());
        $this->assertSame("/(?<ref>a)\g{" . $reference . "}/", $regex->render());
    }

    public function invalidBackReferences ()
    {
        return array(
            array('\2'),
            array('\g'),
            array('\g{noref}'),
            array('\g{1}'),
            array('\g{-1}'),
            array('\g2'),
            array('\k<noref'),
            array('\k<noref>'),
            array('(?P=noref)'),
        );
    }

    /**
     * @dataProvider invalidBackReferences
     * @expectedException REBuilder\Exception\Generic
     */
    public function testInvalidReferenceException ($pattern)
    {
        REBuilder\REBuilder::parse("/$pattern/");
    }

    /**
     * @expectedException REBuilder\Exception\Generic
     */
    public function testMissingReferenceException ()
    {
        $ref = new REBuilder\Pattern\BackReference;
        $ref->render();
    }

    public function testObjectGeneration ()
    {
        $regex = REBuilder\REBuilder::create();
        $regex->addBackReference("1");
        $this->assertSame('/\g{1}/', $regex->render());
    }
}
