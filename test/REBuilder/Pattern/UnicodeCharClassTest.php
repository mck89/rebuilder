<?php
class UnicodeCharClassTest extends PHPUnit_Framework_TestCase
{
    public function testGeneratedStructure ()
    {
        $validClasses = array("C", "{Me}", "{Cyrillic}");
        $identifiers = array("p", "P");
        foreach ($validClasses as $c) {
            foreach ($identifiers as $identifier) {
                $negate = $identifier === "P";
                $regex = REBuilder::parse("/\\$identifier$c/");
                $this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
                $children = $regex->getChildren();
                $this->assertSame(1, count($children));
                $this->assertInstanceOf("REBuilder_Pattern_UnicodeCharClass", $children[0]);
                $this->assertSame(str_replace(array("{", "}"), "", $c), $children[0]->getClass());
                $this->assertSame($negate, $children[0]->getNegate());
                if ($c === "C") {
                    $c = "{" . $c . "}";
                }
                $this->assertSame("/\\$identifier$c/", $regex->render());
            }
        }
    }

    public function testExtendedUnicodeSequenceGeneratedStructure ()
    {
        $regex = REBuilder::parse("/\X/");
        $this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(1, count($children));
        $this->assertInstanceOf("REBuilder_Pattern_UnicodeCharClass", $children[0]);
        $this->assertSame("X", $children[0]->getClass());
        $this->assertSame(false, $children[0]->getNegate());
        $this->assertSame("/\X/", $regex->render());
    }

    /**
     * @expectedException REBuilder_Exception_Generic
     */
    public function testInvalidClassException ()
    {
        $char = new REBuilder_Pattern_UnicodeCharClass();
        $char->setClass("invalid");
    }

    /**
     * @expectedException REBuilder_Exception_Generic
     */
    public function testEmptyClassException ()
    {
        $char = new REBuilder_Pattern_UnicodeCharClass();
        $char->render();
    }

    /**
     * @expectedException REBuilder_Exception_Generic
     */
    public function testExtendedUnicodeSequenceNegateException ()
    {
        $char = new REBuilder_Pattern_UnicodeCharClass("X");
        $char->setNegate(true);
        $char->render();
    }

    public function invalidRegexProvider ()
    {
        return array(
            array("\p"),
            array("\p{C"),
            array("\p{Invalid}")
        );
    }

    /**
     * @dataProvider invalidRegexProvider
     * @expectedException REBuilder_Exception_Generic
     */
    public function testInvalidRegexException ($invalid)
    {
        REBuilder::parse("/$invalid/");
    }

    public function testObjectGeneration ()
    {
        $regex = REBuilder::create();
        $regex->addUnicodeCharClass("C");
        $this->assertSame("/\p{C}/", $regex->render());
    }

    public function testObjectGenerationNegate ()
    {
        $regex = REBuilder::create();
        $regex->addUnicodeCharClass("C", true)->setRepetition("?");
        $this->assertSame("/\P{C}?/", $regex->render());
    }
}
