<?php

namespace Avdb\DatatablesBundle\Tests\Exception;

use Avdb\DatatablesBundle\Exception\DatatablesException;
use Avdb\DatatablesBundle\Tests\DatatablesTestCase;

class DatatablesExceptionTest extends DatatablesTestCase
{
    public function testIsInitializable()
    {
        $exception = new DatatablesException;
        $this->assertInstanceOf(DatatablesException::class, $exception);
        $this->assertInstanceOf(\Exception::class, $exception);
        $this->assertInstanceOf(\Throwable::class, $exception);
    }
}
