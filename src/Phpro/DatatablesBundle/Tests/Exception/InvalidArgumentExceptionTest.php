<?php

namespace Phpro\DatatablesBundle\Tests\Exception;

use Phpro\DatatablesBundle\Exception\DatatablesException;
use Phpro\DatatablesBundle\Exception\InvalidArgumentException;
use Phpro\DatatablesBundle\Tests\DatatablesTestCase;

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
