<?php
declare(strict_types = 1);
namespace Phpro\DatatablesBundle\Manager;

use Phpro\DatatablesBundle\Datatable\DatatableInterface;
use Phpro\DatatablesBundle\Exception\DatatableNotFoundException;
use Phpro\DatatablesBundle\Exception\RuntimeException;

/**
 * Interface DatatableManagerInterface
 *
 * @package Phpro\DatatablesBundle\Manager
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
    public function add(DatatableInterface $datatable);
}
