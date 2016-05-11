<?php
declare(strict_types=1);
namespace Phpro\DatatablesBundle\Element;

/**
 * Class Button
 *
 * Creates a HTML-button with bootstrap markup
 *
 * @package Phpro\DatatablesBundle\Element
 */
class Button implements ElementInterface
{
    /**
     * Button Template
     *
     * @var string
     */
    protected static $template = '<a href="%s" class="btn btn-%s btn-flat %s">%s</a>';

    /**
     * Default button options
     *
     * @var array
     */
    protected static $defaultOptions = [
        'link'  => '#',
        'type'  => 'danger',
        'class' => null,
        'text'  => 'Edit',
    ];

    /**
     * Generates a button in HTML to use in the DataTable
     *
     * @param array $options
     * @return string
     */
    public static function generate(array $options = []) : string
    {
        $options = array_merge(self::$defaultOptions, $options);

        return sprintf(
            self::$template,
            (string)$options['link'],
            (string)$options['type'],
            (string)$options['class'],
            (string)$options['text']
        );
    }
}
