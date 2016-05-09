<?php
declare(strict_types = 1);
namespace phpro\DatatablesBundle\Manager;

use phpro\DatatablesBundle\Datatable\DatatableInterface;
use phpro\DatatablesBundle\Exception\DatatableNotFoundException;
use phpro\DatatablesBundle\Exception\RuntimeException;

/**
 * Interface DatatableManagerInterface
 *
 * @package phpro\DatatablesBundle\Manager
 */
interface DatatableManagerInterface
{
    /**
     * Checks if the manager has a Datatable with given alias
     *
     * @param $alias
     * @return bool
     */
    public function has($alias) : bool;

    /**
     * Returns the Datatable registered with the given alias
     *
     * @param $alias
     * @return DatatableInterface
     * @throws DatatableNotFoundException
     */
    public function get($alias) : DatatableInterface;

    /**
     * Registers a datatable under its current alias
     *
     * @param DatatableInterface $datatable
     * @throws RuntimeException
     */
    public function add(DatatableInterface $datatable) : void;
}
