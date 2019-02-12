<?php
namespace DigipolisGent\DatatablesBundle\Column;

use DigipolisGent\DatatablesBundle\Exception\RuntimeException;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DateTimeColumn
 * DateTimeColumn displays a DateTime object as a string with the defined format
 *
 * @package DigipolisGent\DatatablesBundle\Column
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
     * Adds two extra options on top of the default options in the resolver
     *
     * @return OptionsResolver
     */
    protected function optionsResolver()
    {
        $resolver = parent::optionsResolver();
        $resolver->setDefault('allow_null', true);
        $resolver->setDefault('format', $this->format);

        $resolver->setAllowedTypes('format', ['string']);
        $resolver->setAllowedTypes('allow_null', ['boolean']);

        return $resolver;
    }

    /**
     * Extracts a \DateTime object from the target and formats it in the correct format.
     *
     * @param mixed $target
     * @return string
     * @throws RuntimeException
     */
    public function extractValue($target)
    {
        /** @var callable $extractor */
        $extractor = $this->options['extractor'];
        $dateTime = $extractor($target);

        if(null === $dateTime && true === $this->options['allow_null']) {
            return '';
        }

        if(!$dateTime instanceof \DateTime) {
            throw new RuntimeException(
                'Expected the extractor to extract a \DateTime object, got instance of ' .
                get_class($dateTime) . ' instead'
            );
        }

        return $dateTime->format($this->options['format']);
    }
}
