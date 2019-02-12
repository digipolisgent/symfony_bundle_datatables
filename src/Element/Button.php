<?php
namespace DigipolisGent\DatatablesBundle\Element;

/**
 * Class Button
 *
 * Creates a HTML-button with bootstrap markup
 *
 * @package DigipolisGent\DatatablesBundle\Element
 */
class Button implements ElementInterface
{
    /**
     * Button Template
     *
     * @var string
     */
    protected static $template = '<a href="%s" class="btn btn-%s btn-flat %s" %s>%s</a>';

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
        'attributes' => []
    ];

    /**
     * Generates a button in HTML to use in the DataTable
     *
     * @param array $options
     * @return string
     */
    public static function generate(array $options = [])
    {
        $options = array_merge(self::$defaultOptions, $options);

        $formatted = [];

        foreach($options['attributes'] as $attribute => $value) {
            $formatted[$attribute] = "$attribute=\"$value\"";
        }

        $options['attributes'] = $formatted;

        return sprintf(
            self::$template,
            (string)$options['link'],
            (string)$options['type'],
            (string)$options['class'],
            (string)implode($options['attributes'], ' '),
            (string)$options['text']
        );
    }
}
