<?php

namespace Phpro\DatatablesBundle\Tests\Exception;

use Phpro\DatatablesBundle\Exception\DatatablesException;
use Phpro\DatatablesBundle\Exception\RuntimeException;
use Phpro\DatatablesBundle\Tests\DatatablesTestCase;

class RuntimeExceptionTest extends DatatablesTestCase
{
    public function testIsInitializable()
    {
        $exception = new RuntimeException('', 0);
        $this->assertInstanceOf(RuntimeException::class, $exception);
        $this->assertInstanceOf(DatatablesException::class, $exception);
        $this->assertInstanceOf(\Exception::class, $exception);
        $this->assertInstanceOf(\Throwable::class, $exception);
    }
}
