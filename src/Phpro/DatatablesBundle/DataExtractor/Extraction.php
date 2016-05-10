<?php
declare(strict_types = 1);
namespace Phpro\DatatablesBundle\DataExtractor;

/**
 * Class Extraction
 * Holds the data and the total records count from the DataExtractor
 *
 * @package Phpro\DatatablesBundle\DataExtractor
 */
class Extraction implements ExtractionInterface
{
    /**
     * Array of extracted data for the requested page
     *
     * @var array
     */
    private $data = [];

    /**
     * The count of all records, not just this page.
     *
     * @var integer
     */
    private $totalRecords = 0;

    /**
     * ExtractedData constructor.
     *
     * @param array $data
     * @param int $totalRecords
     */
    public function __construct(array $data, int $totalRecords)
    {
        $this->data = $data;
        $this->totalRecords = $totalRecords;
    }

    /**
     * @return array
     */
    public function getData() : array
    {
        return $this->data;
    }

    /**
     * @return int
     */
    public function getTotalRecords() : int
    {
        return $this->totalRecords;
    }
}
