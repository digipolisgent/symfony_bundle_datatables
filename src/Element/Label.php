<?php
namespace Avdb\DatatablesBundle\Element;

/**
 * Class Label
 *
 * Creates a HTML-Label for Bootstrap3
 *
 * @package Avdb\DatatablesBundle\Element
 */
class Label implements ElementInterface
{
    /**
     * Label template
     *
     * @var string
     */
    protected static $template = '<span class="label label-%s %s">%s</span>';

    /**
     * Default label options
     *
     * @var array
     */
    protected static $defaultOptions = [
        'type'  => 'success',
        'class' => null,
        'text'  => 'yes'
    ];

    /**
     * Generates a label HTML element
     *
     * @param array $options
     * @return string
     */
    public static function generate(array $options = [])
    {
        $options = array_merge(self::$defaultOptions, $options);

        return sprintf(
            self::$template,
            (string)$options['type'],
            (string)$options['class'],
            (string)$options['text']
        );
    }
}
