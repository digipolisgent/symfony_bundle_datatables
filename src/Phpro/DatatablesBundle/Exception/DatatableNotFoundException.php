<?php
namespace Phpro\DatatablesBundle\Exception;

/**
 * Class DatatableNotFoundException
 *
 * @package Phpro\DatatablesBundle\Exception
 */
class DatatableNotFoundException extends DatatablesException
{
    /**
     * Creates a new DatatableNotFoundException from an alias
     *
     * @param $alias
     * @return DatatableNotFoundException
     */
    public static function fromAlias($alias)
    {
        return new static(
            "Datatable $alias was not registered as a datatable, did you forget to tag the datatable ?"
        );
    }
}
