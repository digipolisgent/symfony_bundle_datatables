<?php
declare(strict_types = 1);
namespace phpro\DatatablesBundle\Column;

use phpro\DatatablesBundle\Exception\RuntimeException;

/**
 * Class DateTimeColumn
 * DateTimeColumn displays a DateTime object as a string with the defined format
 *
 * @package phpro\DatatablesBundle\Column
 */
class DateTimeColumn extends Column
{
    /**
     * The default \DateTime format
     *
     * @var string
     */
    private $format = 'd-m-Y H:i:s';

    /**
     * DateTimeColumn constructor.
     * Sets the default \DateTime format, and adds it to the options resolver before resolving.
     *
     * @param string $name
     * @param array $options
     */
    public function __construct($name, array $options)
    {
        parent::__construct($name, []);

        $this->resolver->setDefault('format', $this->format);
        $this->resolver->setDefault('allow_null', true);
        $this->resolver->setAllowedValues('allow_null', [true, false]);

        $this->options = $this->resolver->resolve($options);
    }

    /**
     * Extracts a \DateTime object from the target and formats it in the correct format.
     *
     * @param mixed $target
     * @return string
     * @throws RuntimeException
     */
    public function extractValue($target) : string
    {
        /** @var callable $extractor */
        $extractor = $this->getOption('extractor');
        $dateTime = $extractor($target);

        if(null === $dateTime && true === $this->getOption('allow_null')) {
            return '';
        }

        if(!$dateTime instanceof \DateTime) {
            throw new RuntimeException(
                'Expected the extractor to extract a \DateTime object, got instance of ' .
                get_class($dateTime) . ' instead'
            );
        }

        return $dateTime->format($this->getOption('format'));
    }
}
