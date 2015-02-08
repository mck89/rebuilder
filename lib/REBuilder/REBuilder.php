<?php
/**
 * Main class of REBuilder library
 * 
 * @author Marco Marchiò
 */
class REBuilder
{
	/**
	 * Parses the given regular expression and returns its structure
	 * 
	 * @param string $regex The regular expression to parse
	 * @return REBuilder_Pattern_Regex
	 * @static
	 */
	public static function parse ($regex)
	{
		$parser = new REBuilder_Parser_Parser($regex);
		return $parser->parse();
	}
}