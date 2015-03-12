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
 * Represents an internal option (?i). This allows to set or subtract modifiers
 * at a certain point of a regex
 * 
 * @author Marco Marchiò <marco.mm89@gmail.com>
 * 
 * @link http://php.net/manual/en/regexp.reference.internal-options.php
 */
class InternalOption extends AbstractPattern
{
    /**
     * Internal option mopdifiers
     * 
     * @var string
     */
    protected $_modifiers = "";

    /**
     * Flag that identifies if the pattern supports repetitions
     * 
     * @var bool
     */
    protected $_supportsRepetition = false;

    /**
     * Constructor
     * 
     * @param string $modifiers Internal option modifiers
     */
    public function __construct ($modifiers = null)
    {
        if ($modifiers !== null) {
            $this->setModifiers($modifiers);
        }
    }

    /**
     * Sets internal option modifiers
     * 
     * @param string $modifiers Internal option modifiers
     * 
     * @return InternalOption
     * 
     * @throws \REBuilder\Exception\InvalidModifier
     */
    public function setModifiers ($modifiers)
    {
        if (!\REBuilder\Parser\Rules::validateModifiers(
                str_replace("-", "", $modifiers),
                $wrongModifier
            )) {
            throw new \REBuilder\Exception\InvalidModifier(
                "Invalid modifier '$wrongModifier'"
            );
        }
        $this->_modifiers = $modifiers;
        return $this;
    }

    /**
     * Returns internal option modifiers
     * 
     * @return string
     */
    public function getModifiers ()
    {
        return $this->_modifiers;
    }

    /**
     * Returns the string representation of the class
     * 
     * @return string
     */
    public function render ()
    {
        return "(?" . $this->getModifiers() . ")";
    }
}