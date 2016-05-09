<?php
declare(strict_types = 1);
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
    public function extractValue($target) : string;

    /**
     * Returns the column name
     *
     * @return string
     */
    public function getName() : string;

    /**
     * Returns the column label
     *
     * @return string
     */
    public function getLabel() : string;

    /**
     * Returns all options
     * 
     * @return array
     */
    public function getOptions() : array;
}
