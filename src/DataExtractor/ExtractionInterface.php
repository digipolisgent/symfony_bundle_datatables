<?php
namespace DigipolisGent\DatatablesBundle\DataExtractor;

/**
 * Class ExtractionInterface
 * Holds the data and the total records count from the DataExtractor
 *
 * @package DigipolisGent\DatatablesBundle\DataExtractor
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
