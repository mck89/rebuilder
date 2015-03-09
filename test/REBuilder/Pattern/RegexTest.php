<?php
class RegexTest extends PHPUnit_Framework_TestCase
{
    public function testGeneratedStructure ()
    {
        $regex = REBuilder::parse("/a/i");

        $this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
        $this->assertSame("/", $regex->getStartDelimiter());
        $this->assertSame("/", $regex->getEndDelimiter());
        $this->assertSame("i", $regex->getModifiers());
        $this->assertSame("/a/i", $regex->render());
    }

    public function testBracketStyledelimiters ()
    {
        $regex = REBuilder::parse("(a)i");
        $this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
        $this->assertSame("(", $regex->getStartDelimiter());
        $this->assertSame(")", $regex->getEndDelimiter());
        $this->assertSame("i", $regex->getModifiers());
        $this->assertSame("(a)i", $regex->render());
    }
    
    public function anchoredRegex ()
    {
        return array(
            array("^abc", true, false),
            array("abc$", false, true),
            array("^abc$", true, true),
            array('$abc', false, false),
            array('abc^', false, false),
            array('ab$c', false, false)
        );
    }
    
    /**
     * @dataProvider anchoredRegex
     */
    public function testRegexWithAnchors ($pattern, $start, $end)
    {
        $regex = REBuilder::parse("/$pattern/");
        $this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
        $this->assertSame($start, $regex->getStartAnchored());
        $this->assertSame($end, $regex->getEndAnchored());
        $render = $start || $end ? $pattern : "abc";
        $this->assertSame("/$render/", $regex->render());
    }

    public function invalidDelimitersProvider ()
    {
        return array(
            array("\\"),
            array("1"),
            array("a"),
            array(" ")
        );
    }

    /**
     * @dataProvider invalidDelimitersProvider
     * @expectedException REBuilder_Exception_InvalidDelimiter
     */
    public function testInvalidDelimiterException ($delimiter)
    {
        new REBuilder_Pattern_Regex("", $delimiter);
    }

    /**
     * @expectedException REBuilder_Exception_InvalidDelimiter
     */
    public function testMissingDelimiterException ()
    {
        REBuilder::parse("#a");
    }

    /**
     * @expectedException REBuilder_Exception_InvalidDelimiter
     */
    public function testEscapedDelimiterException ()
    {
        REBuilder::parse("#a\#");
    }

    /**
     * @expectedException REBuilder_Exception_InvalidDelimiter
     */
    public function testUnescapedDelimiterInsideRegexException ()
    {
        REBuilder::parse("#a#b#");
    }

    /**
     * @expectedException REBuilder_Exception_InvalidModifier
     */
    public function testInvalidModifierException ()
    {
        REBuilder::create("$");
    }

    /**
     * @expectedException REBuilder_Exception_EmptyRegex
     */
    public function testEmptyRegexException ()
    {
        REBuilder::parse("");
    }

    /**
     * @expectedException REBuilder_Exception_InvalidRepetition
     */
    public function testNotSupportRepetitionException ()
    {
        REBuilder::create()->setRepetition("*");
    }

    public function testObjectGeneration ()
    {
        $regex = REBuilder::create("i", "#");
        $regex->addChar("a");
        $this->assertSame("#a#i", $regex->render());
        $regex->addChildAt(new REBuilder_Pattern_Char("b"), 0);
        $this->assertSame("#ba#i", $regex->render());
    }
    
    public function testTestMethod ()
    {
        $regex = REBuilder::create();
        $regex
                ->setStartAnchored(true)
                ->setEndAnchored(true)
                ->addCharClass()
                    ->setRepetition("+")
                    ->addCharAndContinue("abc")
                ->getParent()
                ->addChar("d");
        $this->assertSame("/^[abc]+d$/", $regex->render());
        $this->assertSame(true, $regex->test("aaaaad"));
        $this->assertSame(false, $regex->test("zbd"));
    }
    
    public function testExecMethod ()
    {
        $regex = REBuilder::create();
        $regex
                ->setStartAnchored(true)
                ->setEndAnchored(true)
                ->addCharAndContinue("this is a ")
                ->addSubPattern()
                    ->addAlternationGroup()
                        ->addAlternation()
                            ->addCharAndContinue("match")
                        ->getParent()
                        ->addAlternation()
                            ->addCharAndContinue("string")
                        ->getParent();
        $this->assertSame("/^this\ is\ a\ ((?:match|string))$/", $regex->render());
        
        $matches = $regex->exec("this is a match");
        $this->assertSame(true, is_array($matches));
        $this->assertSame(2, count($matches));
        $this->assertSame("this is a match", $matches[0][0]);
        $this->assertSame("match", $matches[1][0]);
        
        $matches = $regex->exec("this is a string", true, true);
        $this->assertSame(true, is_array($matches));
        $this->assertSame(1, count($matches));
        $this->assertSame("this is a string", $matches[0][0][0]);
        $this->assertSame(0, $matches[0][0][1]);
        $this->assertSame("string", $matches[0][1][0]);
        $this->assertSame(10, $matches[0][1][1]);
        
        $matches = $regex->exec("this is a fail", true);
        $this->assertSame(null, $matches);
    }
    
    public function testGrepMethod ()
    {
        $regex = REBuilder::create();
        $regex
                ->setStartAnchored(true)
                ->setEndAnchored(true)
                ->addGenericCharType("d")
                    ->setRepetition(2, 3);
        
        $this->assertSame("/^\d{2,3}$/", $regex->render());
        
        $testArray = array("1", "12", "100", "1a", "a", "abc");
        $this->assertSame(array("12", "100"), array_values($regex->grep($testArray)));
        $this->assertSame(array("1", "1a", "a", "abc"), array_values($regex->grep($testArray, true)));
    }
    
    public function testSplitMethod ()
    {
        $regex = REBuilder::create();
        $regex
                ->addSubPattern()
                    ->addGenericCharType("s")
                    ->setRepetition("+");
        
        $this->assertSame("/(\s+)/", $regex->render());

        $this->assertSame(array("this", "is", "a", "test", ""), $regex->split("this is a test "));
        $this->assertSame(array("this", "is a test "), $regex->split("this is a test ", 2));
        $this->assertSame(array("this", "is", "a", "test"), $regex->split("this is a test ", null, true));
        $this->assertSame(array("this", " ", "is", " ", "a", " ", "test", " "), $regex->split("this is a test ", null, true, true));
        $this->assertSame(array(array("test", 0), array("me", 5)), $regex->split("test me", null, false, false, true));
    }
}
