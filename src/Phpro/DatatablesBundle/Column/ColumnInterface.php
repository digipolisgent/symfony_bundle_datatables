<?php
namespace Phpro\DatatablesBundle\Column;

/**
 * Interface ColumnInterface
 *
 * @package Phpro\DatatablesBundle\Column
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
