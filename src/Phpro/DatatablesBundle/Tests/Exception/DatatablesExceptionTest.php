<?php

namespace Phpro\DatatablesBundle\Tests\Exception;

use Phpro\DatatablesBundle\Exception\DatatablesException;
use Phpro\DatatablesBundle\Tests\DatatablesTestCase;

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
