<?php
namespace DigipolisGent\DatatablesBundle\Tests\Exception;

use DigipolisGent\DatatablesBundle\Exception\DatatableNotFoundException;
use DigipolisGent\DatatablesBundle\Exception\DatatablesException;
use DigipolisGent\DatatablesBundle\Tests\DatatablesTestCase;

class DatatableNotFoundExceptionTest extends DatatablesTestCase
{
    public function testIsInitializable()
    {
        $exception = new DatatableNotFoundException('', 0);
        $this->assertInstanceOf(DatatableNotFoundException::class, $exception);
        $this->assertInstanceOf(DatatablesException::class, $exception);
        $this->assertInstanceOf(\Exception::class, $exception);
    }

    public function testFromAlias()
    {
        $exception = DatatableNotFoundException::fromAlias($alias = 'some_alias');
        $this->assertInstanceOf(DatatableNotFoundException::class, $exception);
        $this->assertContains($alias, $exception->getMessage());
    }
}
