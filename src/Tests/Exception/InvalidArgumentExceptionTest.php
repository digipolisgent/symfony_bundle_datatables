<?php

namespace DigipolisGent\DatatablesBundle\Tests\Exception;

use DigipolisGent\DatatablesBundle\Exception\DatatablesException;
use DigipolisGent\DatatablesBundle\Exception\InvalidArgumentException;
use DigipolisGent\DatatablesBundle\Tests\DatatablesTestCase;

class InvalidArgumentExceptionTest extends DatatablesTestCase
{
    public function testIsInitializable()
    {
        $exception = new InvalidArgumentException('', 0);
        $this->assertInstanceOf(InvalidArgumentException::class, $exception);
        $this->assertInstanceOf(DatatablesException::class, $exception);
        $this->assertInstanceOf(\Exception::class, $exception);
    }
}
