<?php
declare(strict_types = 1);
namespace Phpro\DatatablesBundle\Exception;

/**
 * Class DatatableNotFoundException
 *
 * @package Phpro\DatatablesBundle\Exception
 */
class DatatableNotFoundException extends DatatablesException
{
    public static function fromAlias($alias) : DatatableNotFoundException
    {
        return new static("Datatable $alias was not registered as a datatable, did you forget to tag the datatable ?");
    }
}
