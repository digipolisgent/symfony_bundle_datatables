<?php

namespace Avdb\DatatablesBundle\Tests;

use Avdb\DatatablesBundle\Datatable\DatatableInterface;

abstract class DatatablesTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests if an array has only instances of a certain class
     * 
     * @param $class
     * @param $array
     */
    public static function assertInstancesOf($class, $array)
    {
        if(!is_array($array)){
            throw new \InvalidArgumentException('Expected array, got '  . get_class($array));
        }

        foreach($array as $v) {
            self::assertInstanceOf($class, $v);
        }
    }

    /**
     * Mocks a DatatableObject with a specific alias
     *
     * @param $alias
     * @return \PHPUnit_Framework_MockObject_MockObject|DatatableInterface
     */
    public function mockTable($alias)
    {
        $table = $this->getMock(DatatableInterface::class);

        $table
            ->method('getAlias')
            ->willReturn($alias);

        return $table;
    }
}
