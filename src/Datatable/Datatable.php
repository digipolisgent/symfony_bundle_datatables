<?php
namespace DigipolisGent\DatatablesBundle\Datatable;

use DigipolisGent\DatatablesBundle\Column\Column;
use DigipolisGent\DatatablesBundle\Column\ColumnInterface;
use DigipolisGent\DatatablesBundle\DataExtractor\DataExtractorInterface;
use DigipolisGent\DatatablesBundle\DataExtractor\ExtractionInterface;
use DigipolisGent\DatatablesBundle\Exception\RuntimeException;
use DigipolisGent\DatatablesBundle\Request\RequestInterface;
use DigipolisGent\DatatablesBundle\Response\Response;

/**
 * Class Datatable
 *
 * @package DigipolisGent\DatatablesBundle\Datatable
 */
class Datatable implements DatatableInterface
{
    /**
     * @var DataExtractorInterface
     */
    private $extractor;

    /**
     * @var string
     */
    private $alias;

    /**
     * @var array|ColumnInterface[]
     */
    private $columns;

    /**
     * Datatable constructor.
     *
     * @param string $alias
     * @param DataExtractorInterface $extractor
     */
    public function __construct($alias, DataExtractorInterface $extractor)
    {
        $this->extractor = $extractor;
        $this->alias = $alias;
        $this->columns = [];
    }

    /**
     * Generates the correct API-response for the DataTable,
     * should be indulged into a JSONResponse Object by the controller
     *
     * @param RequestInterface $request
     * @return Response
     * @throws RuntimeException
     */
    public function buildResponse(RequestInterface $request)
    {
        $result = $this->extractor->extract($request);

        if(!$result instanceof ExtractionInterface) {
            throw new RuntimeException(
                'Expected instanceof ExtractionInterface from DataExtractor  (' . get_class($this->extractor) . ').'
            );
        }

        $data = [];

        foreach ($result->getData() as $target) {
            $row = [];
            foreach ($this->columns as $column) {
                $row[] = $column->extractValue($target);
            }
            $data[] = $row;
        }

        return new Response($data, $result->getTotalRecords(), $request->getDraw() + 1);
    }

    /**
     * Adds a Column element to the DataTable's columns
     *
     * @param ColumnInterface $column
     * @return DataTableInterface
     */
    public function addColumn(ColumnInterface $column)
    {
        $this->columns[$column->getName()] = $column;
        return $this;
    }

    /**
     * Creates a Column element and adds it tho the DataTable's columns
     *
     * @param string $name
     * @param array $options
     * @return DataTableInterface
     */
    public function createColumn($name, array $options = [])
    {
        return $this->addColumn(new Column($name, $options));
    }

    /**
     * Returns all columns that are present in the table
     *
     * @return array|ColumnInterface[]
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Returns the alias of the table for which it is registered in the manager
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }
}
