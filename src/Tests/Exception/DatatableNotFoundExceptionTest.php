<?php
namespace Avdb\DatatablesBundle\Tests\Exception;

use Avdb\DatatablesBundle\Exception\DatatableNotFoundException;
use Avdb\DatatablesBundle\Exception\DatatablesException;
use Avdb\DatatablesBundle\Tests\DatatablesTestCase;

class DatatableNotFoundExceptionTest extends DatatablesTestCase
{
    public function testIsInitializable()
    {
        $exception = new DatatableNotFoundException('', 0);
        $this->assertInstanceOf(DatatableNotFoundException::class, $exception);
        $this->assertInstanceOf(DatatablesException::class, $exception);
        $this->assertInstanceOf(\Exception::class, $exception);
        $this->assertInstanceOf(\Throwable::class, $exception);
    }

    public function testFromAlias()
    {
        $exception = DatatableNotFoundException::fromAlias($alias = 'some_alias');
        $this->assertInstanceOf(DatatableNotFoundException::class, $exception);
        $this->assertContains($alias, $exception->getMessage());
    }
}
