<?php
namespace Phpro\DatatablesBundle\Element;

interface ElementInterface
{
    const TYPE_DANGER   = 'danger';
    const TYPE_PRIMARY  = 'primary';
    const TYPE_SUCCESS  = 'success';
    const TYPE_INFO     = 'info';
    const TYPE_WARNING  = 'warning';
    const TYPE_DEFAULT  = 'default';

    /**
     * Generates HTML for a table element (button or label)s
     *
     * @param array $options
     * @return string
     */
    public static function generate(array $options = []);
}
