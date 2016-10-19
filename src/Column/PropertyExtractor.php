<?php
namespace Avdb\DatatablesBundle\Column;

use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Class PropertyExtractor
 * Extracts the property for the column
 * 
 * @package Avdb\DatatablesBundle\Column
 */
class PropertyExtractor
{
    /**
     * @var string
     */
    private $property;

    /**
     * @var PropertyAccess
     */
    private $accessor;

    /**
     * AttributeExtractor constructor.
     *
     * @param string $property
     */
    public function __construct($property)
    {
        $this->property  = $property;
        $this->accessor  = PropertyAccess::createPropertyAccessor();
    }

    /**
     * Extracts the property from the given target
     *
     * shop.owner.name => $target->getShop()->getOwner()->getName()
     *
     * @param $target
     * @return mixed
     *
     * @throws \Symfony\Component\PropertyAccess\Exception\UnexpectedTypeException
     * @throws \Symfony\Component\PropertyAccess\Exception\InvalidArgumentException
     * @throws \Symfony\Component\PropertyAccess\Exception\AccessException
     */
    public function __invoke($target)
    {
        if ($this->accessor->isReadable($target, $this->property)) {
            return $this->accessor->getValue($target, $this->property);
        }

        return null;
    }
}
