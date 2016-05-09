<?php
declare(strict_types = 1);
namespace Phpro\DatatablesBundle\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class Column
 * Base Column class, can be extended or configured
 *
 * @package Phpro\DatatablesBundle\Column
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
     * @var OptionsResolver
     */
    protected $resolver;

    /**
     * Column constructor.
     *
     * @param $name
     * @param array $options
     */
    public function __construct(string $name, array $options = [])
    {
        $this->resolver = new OptionsResolver();

        $this->resolver->setDefaults([
            'property'   => $name,
            'extractor'  => new PropertyExtractor($name),
            'attributes' => ['data-name' => $name],
            'label'      => ucfirst($name)
        ]);

        $this->resolver->setAllowedTypes('label', ['string']);
        $this->resolver->setAllowedTypes('property', ['string']);
        $this->resolver->setAllowedTypes('attributes', ['array']);
        $this->resolver->setAllowedValues('extractor', function ($value) {
            return is_callable($value);
        });

        $this->options = $this->resolver->resolve($options);
        $this->name = $name;
    }

    /**
     * Extracts the value from a target
     *
     * @param mixed $target
     * @return string
     * @throws \Phpro\DatatablesBundle\Exception\InvalidArgumentException
     */
    public function extractValue($target) : string
    {
        /** @var callable $extractor */
        $extractor = $this->getOption('extractor');
        return (string) $extractor($target);
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getLabel() : string
    {
        return $this->getOption('label', ucfirst($this->name));
    }

    /**
     * @return array
     */
    public function getOptions() : array
    {
        return $this->options;
    }

    /**
     * @param $name
     * @param null $default
     * @return mixed
     */
    public function getOption(string $name, $default = null)
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
    public function setAttribute(string $name, string $value)
    {
        $this->options['attributes'][$name] = $value;
    }
}
