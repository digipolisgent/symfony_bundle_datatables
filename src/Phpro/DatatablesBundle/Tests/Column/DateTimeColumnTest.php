<?php
namespace Phpro\DatatablesBundle\Tests\Column;

use Phpro\DatatablesBundle\Column\ColumnInterface;
use Phpro\DatatablesBundle\Column\DateTimeColumn;
use Phpro\DatatablesBundle\Exception\RuntimeException;
use Phpro\DatatablesBundle\Tests\DatatablesTestCase;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

class DateTimeColumnTest extends DatatablesTestCase
{
    public function testCanInstantiate()
    {
        $column = new DateTimeColumn('created_at');
        $this->assertInstanceOf(ColumnInterface::class, $column);
        $this->assertInstanceOf(DateTimeColumn::class, $column);
    }

    public function testCanSetAllowNullOption()
    {
        $column = new DateTimeColumn('create_at', ['allow_null' => false]);
        $this->assertEquals($column->getOption('allow_null'), false);
    }

    public function testCannotSetAllowNullOptionOtherThanBool()
    {
        $this->expectException(InvalidOptionsException::class);
        new DateTimeColumn('create_at', ['allow_null' => null]);
    }

    public function testCanSetFormatOption()
    {
        $column = new DateTimeColumn('created_at', ['format' => 'd-m-Y']);
        $this->assertEquals($column->getOption('format'), 'd-m-Y');
    }

    public function testCannotSetNonStringFormatOption()
    {
        $this->expectException(InvalidOptionsException::class);
        new DateTimeColumn('created_at', ['format' => 25]);
    }

    public function testCanExtractBasicDateTimeValue()
    {
        $object = new \stdClass();
        $object->created_at = new \DateTime('17 march 2016');

        $column = new DateTimeColumn('created_at', ['format' => 'd-m-Y']);
        $this->assertEquals($column->extractValue($object), '17-03-2016');
    }

    public function testThrowsExceptionWhenAllowNullIsFalseAndDateTimeIsNotPresent()
    {
        $object = new \stdClass();
        $object->created_at = null;

        $column = new DateTimeColumn('created_at', ['allow_null' => false]);
        $this->expectException(RuntimeException::class);
        $column->extractValue($object);
    }

    public function testReturnsEmptyStringWhenDateTimeIsNull()
    {
        $object = new \stdClass();
        $object->created_at = null;

        $column = new DateTimeColumn('created_at', ['format' => 'd-m-Y']);
        $this->assertEquals($column->extractValue($object), '');
    }
}
