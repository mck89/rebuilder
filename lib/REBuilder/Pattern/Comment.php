<?php
/**
 * This file is part of the REBuilder package
 *
 * (c) Marco Marchiò <marco.mm89@gmail.com>
 *
 * For the full copyright and license information refer to the LICENSE file
 * distributed with this source code
 */

namespace REBuilder\Pattern;

/**
 * Represents a comment
 * 
 * @author Marco Marchiò <marco.mm89@gmail.com>
 * 
 * @link http://php.net/manual/en/regexp.reference.internal-options.php
 */
class Comment extends AbstractPattern
{
    /**
     * Comment
     * 
     * @var string
     */
    protected $_comment = "";

    /**
     * Flag that identifies if the pattern supports repetitions
     * 
     * @var bool
     */
    protected $_supportsRepetition = false;

    /**
     * Constructor
     * 
     * @param string $comment Comment
     */
    public function __construct ($comment = "")
    {
        $this->setComment($comment);
    }

    /**
     * Sets the comment
     * 
     * @param string $comment Comment
     * 
     * @return Comment
     */
    public function setComment ($comment)
    {
        $this->_comment = $comment;
        return $this;
    }

    /**
     * Returns the comment
     * 
     * @return string
     */
    public function getComment ()
    {
        return $this->_comment;
    }

    /**
     * Returns the string representation of the class
     * 
     * @return string
     */
    public function render ()
    {
        return "(?#" . str_replace(")", "", $this->getComment()) . ")";
    }
}