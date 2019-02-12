<?php
namespace DigipolisGent\DatatablesBundle\Manager;

use DigipolisGent\DatatablesBundle\Datatable\DatatableInterface;
use DigipolisGent\DatatablesBundle\Exception\DatatableNotFoundException;
use DigipolisGent\DatatablesBundle\Exception\RuntimeException;

/**
 * Interface DatatableManagerInterface
 *
 * @package DigipolisGent\DatatablesBundle\Manager
 */
interface DatatableManagerInterface
{
    /**
     * Checks if the manager has a Datatable with given alias
     *
     * @param $alias
     * @return bool
     */
    public function has($alias);

    /**
     * Returns the Datatable registered with the given alias
     *
     * @param $alias
     * @return DatatableInterface
     * @throws DatatableNotFoundException
     */
    public function get($alias);

    /**
     * Registers a datatable under its current alias
     *
     * @param DatatableInterface $datatable
     * @throws RuntimeException
     */
    public function add(DatatableInterface $datatable);
}
