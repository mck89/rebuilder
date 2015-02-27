<?php
class CommentTest extends PHPUnit_Framework_TestCase
{
    public function testGeneratedStructure ()
    {
        $regex = REBuilder::parse("/a(?#this is a comment)*/");
        $this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(2, count($children));
        $this->assertInstanceOf("REBuilder_Pattern_Char", $children[0]);
        $this->assertInstanceOf("REBuilder_Pattern_Repetition_ZeroOrMore", $children[0]->getRepetition());
        $this->assertInstanceOf("REBuilder_Pattern_Comment", $children[1]);
        $this->assertSame("this is a comment", $children[1]->getComment());
        $this->assertSame("/a*(?#this is a comment)/", $regex->render());
    }

    public function testGeneratedStructureWithExtendedModifier ()
    {
        $pattern = "/a b #comment 1
                    c d#comment 2/x";
        $pattern = str_replace("\r", "", $pattern);
        $regex = REBuilder::parse($pattern);
        $this->assertInstanceOf("REBuilder_Pattern_Regex", $regex);
        $children = $regex->getChildren();
        $this->assertSame(4, count($children));
        $this->assertInstanceOf("REBuilder_Pattern_Char", $children[0]);
        $this->assertSame("ab", $children[0]->getChar());
        $this->assertInstanceOf("REBuilder_Pattern_Comment", $children[1]);
        $this->assertSame("comment 1", $children[1]->getComment());
        $this->assertInstanceOf("REBuilder_Pattern_Char", $children[2]);
        $this->assertSame("cd", $children[2]->getChar());
        $this->assertInstanceOf("REBuilder_Pattern_Comment", $children[3]);
        $this->assertSame("comment 2", $children[3]->getComment());
        $this->assertSame("/ab(?#comment 1)cd(?#comment 2)/x", $regex->render());
    }

    /**
     * @expectedException REBuilder_Exception_Generic
     */
    public function testUnclosedCommentException ()
    {
        REBuilder::parse("/a(?#test/");
    }

    public function testObjectGeneration ()
    {
        $regex = REBuilder::create();
        $regex
                ->addChar("a")
                    ->setRepetition("*")
                ->getParent()
                ->addComment("comment");
        $this->assertSame("/a*(?#comment)/", $regex->render());
    }
}
