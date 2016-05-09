<?php
declare(strict_type=1);
namespace Phpro\DatatablesBundle\Datatable;

use Phpro\DatatablesBundle\Column\ColumnInterface;
use Phpro\DatatablesBundle\Request\RequestInterface;
use Phpro\DatatablesBundle\Response\Response;

/**
 * Interface DatatableInterface
 *
 * @package Phpro\DatatablesBundle\Datatable
 */
interface DatatableInterface
{
    /**
     * Generates the correct API-response for the DataTable,
     * should be indulged into a JSONResponse Object by the controller
     *
     * @param RequestInterface $request
     * @return Response
     */
    public function buildResponse(RequestInterface $request) : Response;

    /**
     * Adds a Column element to the DataTable's columns
     *
     * @param ColumnInterface $column
     * @return DataTableInterface
     */
    public function addColumn(ColumnInterface $column) : DatatableInterface;

    /**
     * Creates a Column element and adds it tho the DataTable's columns
     *
     * @param string $name
     * @param array $options
     * @return DataTableInterface
     */
    public function createColumn(string $name, array $options = []) : DatatableInterface;

    /**
     * Returns all columns that are present in the table
     *
     * @return array|ColumnInterface[]
     */
    public function getColumns() : array;

    /**
     * Returns the alias of the table for which it is registered in the manager
     *
     * @return string
     */
    public function getAlias() : string;
}
