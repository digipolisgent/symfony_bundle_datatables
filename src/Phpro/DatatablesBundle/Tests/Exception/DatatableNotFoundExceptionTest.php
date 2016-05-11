<?php
namespace Phpro\DatatablesBundle\Tests\Exception;

use Phpro\DatatablesBundle\Exception\DatatableNotFoundException;
use Phpro\DatatablesBundle\Exception\DatatablesException;
use Phpro\DatatablesBundle\Tests\DatatablesTestCase;

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
