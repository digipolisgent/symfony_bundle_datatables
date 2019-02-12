<?php

namespace DigipolisGent\DatatablesBundle\Tests\Exception;

use DigipolisGent\DatatablesBundle\Exception\DatatablesException;
use DigipolisGent\DatatablesBundle\Exception\RuntimeException;
use DigipolisGent\DatatablesBundle\Tests\DatatablesTestCase;

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
