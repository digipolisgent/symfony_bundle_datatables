<?php

namespace Avdb\DatatablesBundle\Tests\Exception;

use Avdb\DatatablesBundle\Exception\DatatablesException;
use Avdb\DatatablesBundle\Exception\InvalidArgumentException;
use Avdb\DatatablesBundle\Tests\DatatablesTestCase;

class InvalidArgumentExceptionTest extends DatatablesTestCase
{
    public function testIsInitializable()
    {
        $exception = new InvalidArgumentException('', 0);
        $this->assertInstanceOf(InvalidArgumentException::class, $exception);
        $this->assertInstanceOf(DatatablesException::class, $exception);
        $this->assertInstanceOf(\Exception::class, $exception);
        $this->assertInstanceOf(\Throwable::class, $exception);
    }
}
