<?php
namespace Phpro\DatatablesBundle\Tests\Column;

use Phpro\DatatablesBundle\Column\Column;
use Phpro\DatatablesBundle\Column\ColumnInterface;
use Phpro\DatatablesBundle\Column\PropertyExtractor;
use Phpro\DatatablesBundle\Tests\DatatablesTestCase;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;

class ColumnTest extends DatatablesTestCase
{
    public function testCanInstantiate()
    {
        $column = new Column('test');
        $this->assertInstanceOf(ColumnInterface::class, $column);
    }

    public function testDefaultOptions()
    {
        $name   = 'name';
        $column = new Column($name);

        $this->assertEquals($column->getOption('property'), $name);
        $this->assertEquals($column->getLabel(), ucfirst($name));
        $this->assertEquals($column->getOption('attributes'), ['data-name' => $name]);
        $this->assertInstanceOf(PropertyExtractor::class, $column->getOption('extractor'));
    }

    public function testCanSetOptions()
    {
        $options = [
            'property'      => 'lastName',
            'extractor'     => function($entity){ return $entity->lastName; },
            'attributes'    => ['data-something' => 'test'],
            'label'         => 'label.last_name',
        ];

        $column = new Column('name', $options);
        $this->assertEquals($column->getOption('property'), 'lastName');
        $this->assertEquals($column->getOption('extractor'), $options['extractor']);
        $this->assertEquals($column->getOption('attributes'), ['data-something' => 'test']);
        $this->assertEquals($column->getOption('label'), 'label.last_name');

        $this->assertEquals($column->getLabel(), 'label.last_name');
    }

    public function testCannotSetInvalidOption()
    {
        $this->expectException(UndefinedOptionsException::class);
        new Column('name', ['invalid' => 'something']);
    }

    public function testCanSetExtractorOption()
    {
        $callable = function($entity){ return $entity->name; };

        $column = new Column('name', ['extractor' => $callable]);
        $this->assertEquals($column->getOption('extractor'), $callable);
    }

    public function testCannotSetExtractorThatIsNotCallable()
    {
        $this->expectException(InvalidOptionsException::class);

        $extractor = false;
        new Column('name', ['extractor' => $extractor]);
    }

    public function testCannotSetNonArrayAsAttributes()
    {
        $this->expectException(InvalidOptionsException::class);
        new Column('col', ['attributes' => 'invalid']);
    }

    public function testColumnExtractsValue()
    {
        $object = new \stdClass();
        $object->name = 'Albert';

        $column = new Column('name');
        $this->assertEquals($column->extractValue($object), 'Albert');
    }
}
