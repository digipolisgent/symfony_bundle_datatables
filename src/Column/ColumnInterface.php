<?php
namespace DigipolisGent\DatatablesBundle\Column;

/**
 * Interface ColumnInterface
 *
 * @package DigipolisGent\DatatablesBundle\Column
 */
interface ColumnInterface
{
    /**
     * Generates the column value for a given target
     *
     * @param mixed $target
     * @return string
     */
    public function extractValue($target);

    /**
     * Returns the column name
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the column label
     *
     * @return string
     */
    public function getLabel();

    /**
     * Returns all options
     * 
     * @return array
     */
    public function getOptions();
}
