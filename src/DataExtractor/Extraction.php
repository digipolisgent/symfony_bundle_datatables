<?php
namespace DigipolisGent\DatatablesBundle\DataExtractor;

/**
 * Class Extraction
 * Holds the data and the total records count from the DataExtractor
 *
 * @package DigipolisGent\DatatablesBundle\DataExtractor
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
    public function __construct(array $data, $totalRecords)
    {
        $this->data = (array) $data;
        $this->totalRecords = (int) $totalRecords;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return int
     */
    public function getTotalRecords()
    {
        return $this->totalRecords;
    }
}
