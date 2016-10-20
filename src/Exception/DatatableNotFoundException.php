<?php
namespace Avdb\DatatablesBundle\Exception;

/**
 * Class DatatableNotFoundException
 *
 * @package Avdb\DatatablesBundle\Exception
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
        return new self(sprintf(
            'Datatable %s was not registered as a datatable, did you forget to tag the datatable ?',
            $alias
        ));

    }
}
