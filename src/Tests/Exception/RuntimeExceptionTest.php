<?php

namespace Avdb\DatatablesBundle\Tests\Exception;

use Avdb\DatatablesBundle\Exception\DatatablesException;
use Avdb\DatatablesBundle\Exception\RuntimeException;
use Avdb\DatatablesBundle\Tests\DatatablesTestCase;

class RuntimeExceptionTest extends DatatablesTestCase
{
    public function testIsInitializable()
    {
        $exception = new RuntimeException('', 0);
        $this->assertInstanceOf(RuntimeException::class, $exception);
        $this->assertInstanceOf(DatatablesException::class, $exception);
        $this->assertInstanceOf(\Exception::class, $exception);
    }
}
