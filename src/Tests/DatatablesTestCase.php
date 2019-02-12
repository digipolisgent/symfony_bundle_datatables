<?php

namespace DigipolisGent\DatatablesBundle\Tests;

use DigipolisGent\DatatablesBundle\Datatable\DatatableInterface;

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
     * @param string $class
     * @return mixed|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getMockObject($class)
    {
        $mocker = $this->getMockBuilder($class);
        $mocker->disableOriginalConstructor();

        return $mocker->getMock();
    }

    /**
     * Mocks a DatatableObject with a specific alias
     *
     * @param $alias
     * @return \PHPUnit_Framework_MockObject_MockObject|DatatableInterface
     */
    public function mockTable($alias)
    {
        $table = $this->getMockObject(DatatableInterface::class);

        $table
            ->method('getAlias')
            ->willReturn($alias);

        return $table;
    }
}
