<?php
namespace Phpro\DatatablesBundle\DataExtractor;

/**
 * Class ExtractionInterface
 * Holds the data and the total records count from the DataExtractor
 *
 * @package Phpro\DatatablesBundle\DataExtractor
 */
interface ExtractionInterface
{
    /**
     * @return array
     */
    public function getData();

    /**
     * @return int
     */
    public function getTotalRecords();
}
