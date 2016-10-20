<?php
namespace Avdb\DatatablesBundle\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class Column
 * Base Column class, can be extended or configured
 *
 * @package Avdb\DatatablesBundle\Column
 */
class Column implements ColumnInterface
{
    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var string
     */
    private $name;

    /**
     * Column constructor.
     *
     * @param string $name
     * @param array $options
     */
    public function __construct($name, array $options = [])
    {
        $this->options  = $options; // options need to be accessible by the resolver to check if property is passed
        $this->name     = $name;
        $this->options  = $this->optionsResolver()->resolve($options);
    }

    /**
     * This method can be overridden if extra options are to be added to the resolver.
     *
     * @return OptionsResolver
     */
    protected function optionsResolver()
    {
        $property = isset($this->options['property']) ? $this->options['property'] : $this->name;
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'property'   => $this->name,
            'extractor'  => new PropertyExtractor($property),
            'attributes' => ['data-name' => $this->name],
            'label'      => ucfirst($this->name)
        ]);

        $resolver->setAllowedTypes('label', ['string']);
        $resolver->setAllowedTypes('property', ['string']);
        $resolver->setAllowedTypes('attributes', ['array']);
        $resolver->setAllowedValues('extractor', function ($value) {
            return is_callable($value);
        });

        return $resolver;
    }

    /**
     * Extracts the value from a target
     *
     * @param mixed $target
     * @return string
     * @throws \Avdb\DatatablesBundle\Exception\InvalidArgumentException
     */
    public function extractValue($target)
    {
        /** @var callable $extractor */
        $extractor = $this->getOption('extractor');
        return $extractor($target);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->getOption('label', ucfirst($this->name));
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getOption($name, $default = null)
    {
        if (!array_key_exists($name, $this->options)) {
            return $default;
        } else {
            return $this->options[$name];
        }
    }

    /**
     * @param string $name
     * @param $value
     */
    public function setAttribute($name, $value)
    {
        $this->options['attributes'][$name] = $value;
    }
}
